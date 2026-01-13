<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SchedulingRun;
use App\Models\ScheduleEntry;
use App\Models\GradeLevel;
use App\Models\Teacher;

class SchedulingRunController extends Controller
{
    public function show($id)
    {
        $run = SchedulingRun::findOrFail($id);

        // load all entries for this run
        $entries = ScheduleEntry::with(['section','subject','teacher'])
            ->where('scheduling_run_id', $run->id)
            ->get();

        // group entries by section for quick lookup
        $entriesBySection = $entries->groupBy('section_id');

        // days and max periods
        $days = range(1,5);
        $maxPeriod = $entries->max('period') ?? 8;

        // load grade levels and their sections
        $gradeLevels = GradeLevel::with('sections')->orderBy('id')->get();

        // teachers list for the Per Teacher tab
        $teachers = Teacher::orderBy('name')->get(['id','name']);

        return view('admin.it.scheduler.show', compact('run','gradeLevels','entriesBySection','days','maxPeriod','teachers'));
    }

    public function teacherSchedule($runId, $teacherId)
    {
        $run = SchedulingRun::findOrFail($runId);
        $teacher = Teacher::findOrFail($teacherId);

        $entries = ScheduleEntry::with(['section','subject'])
            ->where('scheduling_run_id', $run->id)
            ->where('teacher_id', $teacher->id)
            ->get();

        $days = range(1,5);
        $maxPeriod = $entries->max('period') ?? 8;

        $entriesBySlot = [];
        foreach ($entries as $e) {
            $entriesBySlot[$e->day][$e->period] = $e;
        }

        return view('admin.it.scheduler.teacher', compact('run','teacher','entriesBySlot','days','maxPeriod'));
    }

    public function substitutions($runId)
    {
        $run = SchedulingRun::findOrFail($runId);

        // recent absences and any applied substitutions
        $absences = \App\Models\Absence::with('teacher','substitutions.substitute')->orderBy('date','desc')->limit(50)->get();

        return view('admin.it.scheduler.substitutions', compact('run','absences'));
    }

    public function generate(Request $request, $runId)
    {
        $run = SchedulingRun::findOrFail($runId);

        // Generation may take longer than PHP's default execution time.
        // Allow this controller action to run without a hard time limit.
        if (function_exists('set_time_limit')) {
            @set_time_limit(0);
        }
        @ini_set('max_execution_time', '0');
        // read run meta for periods and available days
        $meta = $run->meta ?? [];
        $periodObjs = $meta['periods'] ?? null;
        // synthesize if not present: use periods_per_day to create uniform period objects
        if (!is_array($periodObjs)) {
            $pps = isset($meta['periods_per_day']) ? (int)$meta['periods_per_day'] : 9;
            $periodObjs = [];
            for ($i = 1; $i <= $pps; $i++) {
                $periodObjs[] = ['index' => $i, 'label' => (string)$i, 'start' => null, 'end' => null, 'duration' => null, 'is_break' => false];
            }
        }
        // ordered list of all period indexes and teachable (non-break) indexes
        $allPeriodIndexes = array_map(fn($p) => (int)($p['index'] ?? 0), $periodObjs);
        $teachableIndexes = array_map(fn($p) => (int)($p['index'] ?? 0), array_filter($periodObjs, fn($p) => empty($p['is_break'])));

        // available days (1=Mon..7=Sun)
        $availableDays = $meta['available_days'] ?? [1,2,3,4,5];

        // clear existing entries for the run
        ScheduleEntry::where('scheduling_run_id', $run->id)->delete();

        // load all sections
        $sections = \App\Models\Section::with('gradeLevel')->get();

        foreach ($sections as $section) {
            // determine grade stage normalized (jhs/shs)
            $grade = $section->gradeLevel;
            $rawStage = strtolower($grade->school_stage ?? 'jhs');
            if (strpos($rawStage, 'jhs') !== false || strpos($rawStage, 'jun') !== false) {
                $stageKey = 'jhs';
            } elseif (strpos($rawStage, 'shs') !== false || strpos($rawStage, 'sen') !== false) {
                $stageKey = 'shs';
            } else {
                $stageKey = 'jhs';
            }

            // eligible subjects for this section's grade: prefer subjects attached to the grade level,
            // otherwise include only subjects whose `stage` explicitly matches the grade's stage.
            // Do NOT include subjects with NULL/empty stage to avoid cross-stage assignments.
            $subjects = \App\Models\Subject::whereHas('gradeLevels', function ($q) use ($section) {
                $q->where('grade_levels.id', $section->grade_level_id);
            })->orWhere(function($q) use ($stageKey) {
                $q->where('stage', $stageKey);
            })->orderBy('name')->get();

            // Enforce policy: for Junior High regular sections (not special),
            // exclude subjects whose `type` is 'special subjects' so only
            // designated special sections can receive those subjects.
            if ($stageKey === 'jhs' && !(isset($section->is_special) && $section->is_special)) {
                $subjects = $subjects->filter(function($s){
                    $t = strtolower(trim($s->type ?? ''));
                    return $t !== 'special subjects';
                })->values();
            }

            // Quick-fix policy (matrix mode):
            // - choose N distinct subjects per section (default 8)
            // - SHS sections remain at 8; JHS special sections may have 9 subjects
            // - assign each chosen subject to a unique period (no duplicates per section)
            // - one period (if periods > subjects) will remain empty
            // - for each assigned period pick the lowest-load available teacher (or leave null)
            $desiredSubjects = 8;
            // Allow junior-high special sections to have 9 subjects (user-requested special subjects)
            if ($stageKey === 'jhs' && isset($section->is_special) && $section->is_special) {
                $desiredSubjects = 9;
            }

            // build slots for each available day and period using session templates
            // Session templates are stored in run meta under `sessions` (regular/shorten).
            // Choose the template per-day using `meta['shorten_days']` when `shorten_on` is enabled.
            $slots = [];
            $sessionsMeta = $meta['sessions'] ?? [];
            $shortenOn = !empty($meta['shorten_on']);
            $shortenDays = is_array($meta['shorten_days']) ? $meta['shorten_days'] : ($meta['shorten_days'] ? [$meta['shorten_days']] : []);
            $regularDays = is_array($meta['regular_days']) ? $meta['regular_days'] : (isset($meta['regular_days']) && $meta['regular_days'] ? [$meta['regular_days']] : []);

            foreach ($availableDays as $d) {
                // decide which session template to use for this day
                // Priority:
                // 1) explicit `regular_days` (if present) — those days use regular, others use shorten
                // 2) if `regular_days` not present and `shorten_on` is enabled, use `shorten_days` to mark shorten days
                // 3) fallback to meta['default_session'] or 'regular'
                if (!empty($regularDays)) {
                    $sessionType = in_array((int)$d, $regularDays) ? 'regular' : 'shorten';
                } elseif ($shortenOn && !empty($shortenDays)) {
                    $sessionType = in_array((int)$d, $shortenDays) ? 'shorten' : 'regular';
                } else {
                    $sessionType = $meta['default_session'] ?? 'regular';
                }
                // fallback order: chosen session -> regular -> meta.periods -> synthesized $periodObjs
                if (!empty($sessionsMeta[$sessionType]) && is_array($sessionsMeta[$sessionType])) {
                    $template = $sessionsMeta[$sessionType];
                } elseif (!empty($sessionsMeta['regular']) && is_array($sessionsMeta['regular'])) {
                    $template = $sessionsMeta['regular'];
                } elseif (!empty($meta['periods']) && is_array($meta['periods'])) {
                    $template = $meta['periods'];
                } else {
                    $template = $periodObjs;
                }

                foreach ($template as $pObj) {
                    $pp = (int) ($pObj['index'] ?? 0);
                    $isBreak = !empty($pObj['is_break']);
                    // store pointer to session type for debugging/inspection
                    $slots[] = ['day' => (int)$d, 'period' => $pp, 'is_break' => $isBreak, 'session' => $sessionType];
                }
            }

            // prepare subject list and candidates per subject
            $subjectArr = $subjects->values()->all();
            $subjectCandidates = [];
            foreach ($subjectArr as $subj) {
                // only consider teachers who teach the subject and are explicitly assigned to this section's grade level
                $cands = $subj->teachers()->with('gradeLevels')->get()->filter(function ($t) use ($section) {
                    // teacher must have gradeLevels defined and must include the section's grade level
                    if (!($t->gradeLevels && $t->gradeLevels->count())) return false;
                    if (!$t->gradeLevels->contains('id', $section->grade_level_id)) return false;
                    return true;
                })->values();
                $subjectCandidates[$subj->id] = $cands;
            }

            // select up to $desiredSubjects distinct subjects for this section
            // prefer subjects that have teacher candidates
            $withCandidates = array_filter($subjectArr, fn($s) => isset($subjectCandidates[$s->id]) && $subjectCandidates[$s->id]->count() > 0);
            $withoutCandidates = array_filter($subjectArr, fn($s) => !isset($subjectCandidates[$s->id]) || $subjectCandidates[$s->id]->count() == 0);

            $selectedSubjects = [];
            // For junior-high special sections: ensure AT MOST ONE special subject
            $isJhsSpecialSection = ($stageKey === 'jhs' && isset($section->is_special) && $section->is_special);

            if ($isJhsSpecialSection) {
                // Separate subjects with candidates into special and normal
                $specialWithCandidates = array_filter($withCandidates, function($s){
                    return strtolower(trim($s->type ?? '')) === 'special subjects';
                });
                $normalWithCandidates = array_filter($withCandidates, function($s){
                    return strtolower(trim($s->type ?? '')) !== 'special subjects';
                });

                // Pick one special subject (if available)
                if (count($specialWithCandidates) > 0) {
                    shuffle($specialWithCandidates);
                    $picked = array_shift($specialWithCandidates);
                    $selectedSubjects[] = $picked->id;
                }

                // Fill remaining slots from normal-with-candidates
                shuffle($normalWithCandidates);
                foreach ($normalWithCandidates as $s) {
                    if (count($selectedSubjects) >= $desiredSubjects) break;
                    if (!in_array($s->id, $selectedSubjects)) $selectedSubjects[] = $s->id;
                }

                // If still short, fall back to subjects without candidates (excluding special subjects)
                if (count($selectedSubjects) < $desiredSubjects) {
                    // exclude special subjects from fallback for JHS special sections' normal pool
                    $fallback = array_filter($withoutCandidates, function($s){ return strtolower(trim($s->type ?? '')) !== 'special subjects'; });
                    shuffle($fallback);
                    foreach ($fallback as $s) {
                        if (count($selectedSubjects) >= $desiredSubjects) break;
                        if (!in_array($s->id, $selectedSubjects)) $selectedSubjects[] = $s->id;
                    }
                }
            } else {
                // Non-special or other stages: pick from withCandidates first
                shuffle($withCandidates);
                foreach ($withCandidates as $s) {
                    if (count($selectedSubjects) >= $desiredSubjects) break;
                    $selectedSubjects[] = $s->id;
                }
                // if still short, take subjects without candidates to reach desired count
                if (count($selectedSubjects) < $desiredSubjects) {
                    shuffle($withoutCandidates);
                    foreach ($withoutCandidates as $s) {
                        if (count($selectedSubjects) >= $desiredSubjects) break;
                        $selectedSubjects[] = $s->id;
                    }
                }
            }

            // if still none selected (no subjects defined for grade) then leave all slots unassigned
            if (!count($selectedSubjects)) {
                foreach ($slots as $slot) {
                    ScheduleEntry::create([
                        'scheduling_run_id' => $run->id,
                        'section_id' => $section->id,
                        'day' => $slot['day'],
                        'period' => $slot['period'],
                        'subject_id' => null,
                        'teacher_id' => null,
                        'conflict' => null,
                    ]);
                }
                continue; // next section
            }

            // select unique slots (day+period) for the selected subjects (no duplicate subjects per section)
            $totalSlots = count($slots);
            $selectedCount = count($selectedSubjects);
            if ($selectedCount > $totalSlots) {
                $selectedSubjects = array_slice($selectedSubjects, 0, $totalSlots);
                $selectedCount = count($selectedSubjects);
            }
            // reserve period 9 as the empty period (P9 should always be empty in the matrix)
            // Exception: allow JHS special sections to use P9 so they can have 9 distinct subjects.
            $fixedEmptyPeriod = 9;
            if ($stageKey === 'jhs' && isset($section->is_special) && $section->is_special) {
                $fixedEmptyPeriod = null;
            }
            // build pool of available slots that are teachable and not the fixed empty period
            $availableSlots = array_values(array_filter($slots, function($s) use ($fixedEmptyPeriod) {
                if (!empty($s['is_break'])) return false;
                if ($fixedEmptyPeriod !== null && isset($s['period']) && $s['period'] == $fixedEmptyPeriod) return false;
                return true;
            }));
            shuffle($availableSlots);
            $assignedSlots = array_slice($availableSlots, 0, $selectedCount);
            shuffle($selectedSubjects);
            $subjectBySlot = [];
            foreach ($assignedSlots as $idx => $slotAssigned) {
                $key = $slotAssigned['day'] . ':' . $slotAssigned['period'];
                $subjectBySlot[$key] = $selectedSubjects[$idx] ?? null;
            }

            // track teacher loads to balance assignments
            $teacherLoads = [];
            $allTeacherIds = \App\Models\Teacher::pluck('id')->all();
            foreach ($allTeacherIds as $tid) { $teacherLoads[$tid] = ScheduleEntry::where('scheduling_run_id', $run->id)->where('teacher_id', $tid)->count(); }

            // helper: check teacher availability (consider period-only availability and existing assignments)
            $isTeacherAvailableAt = function($teacher, $period) use ($run) {
                if (is_array($teacher->availability) && count($teacher->availability)) {
                    $flat = [];
                    if (isset($teacher->availability['unavailable']) && is_array($teacher->availability['unavailable'])) {
                        $flat = $teacher->availability['unavailable'];
                    } else {
                        $flat = $teacher->availability;
                    }
                    foreach ($flat as $f) {
                        if (is_string($f) && (str_ends_with($f, ":{$period}") || (string)$f === (string)$period)) return false;
                    }
                }
                $exists = ScheduleEntry::where('scheduling_run_id', $run->id)
                    ->where('teacher_id', $teacher->id)
                    ->where('period', $period)
                    ->exists();
                if ($exists) return false;
                return true;
            };

            // create entries: assign selected subjects to their chosen periods; leave other periods empty
                foreach ($slots as $slot) {
                    $p = $slot['period'];
                    $slotKey = $slot['day'] . ':' . $p;
                    // if this slot is a break, leave empty
                    if (!empty($slot['is_break'])) {
                        $sid = null; $chosenTeacherId = null;
                    } else {
                        $sid = $subjectBySlot[$slotKey] ?? null;
                        $chosenTeacherId = null;
                        if ($sid) {
                            $cands = $subjectCandidates[$sid] ?? collect();
                            $available = $cands->filter(function($t) use ($p, $isTeacherAvailableAt) {
                                return $isTeacherAvailableAt($t, $p);
                            });
                            if ($available->count()) {
                                $chosen = null; $min = PHP_INT_MAX;
                                foreach ($available as $tch) {
                                    $load = $teacherLoads[$tch->id] ?? 0;
                                    if ($load < $min) { $min = $load; $chosen = $tch; }
                                }
                                if ($chosen) { $chosenTeacherId = $chosen->id; $teacherLoads[$chosenTeacherId] = ($teacherLoads[$chosenTeacherId] ?? 0) + 1; }
                            }
                        }
                    }

                    ScheduleEntry::create([
                        'scheduling_run_id' => $run->id,
                        'section_id' => $section->id,
                        'day' => $slot['day'],
                        'period' => $p,
                        'subject_id' => $sid,
                        'teacher_id' => $chosenTeacherId,
                        'conflict' => null,
                    ]);
                }
        }

        // run validation to detect conflicts and annotate entries
        $this->validateRun($run);

        // If the request expects JSON (AJAX from the matrix UI), return a JSON summary so the frontend can reload/update in-place.
        if ($request->expectsJson()) {
            $total = ScheduleEntry::where('scheduling_run_id', $run->id)->count();
            $conflicts = ScheduleEntry::where('scheduling_run_id', $run->id)->whereNotNull('conflict')->count();

            // prepare entries payload for client-side update
            $entries = ScheduleEntry::with(['section','subject','teacher'])
                ->where('scheduling_run_id', $run->id)
                ->get()
                ->map(function($e){
                    return [
                        'id' => $e->id,
                        'section_id' => $e->section_id,
                        'period' => $e->period,
                        'subject_id' => $e->subject_id,
                        'subject_name' => $e->subject ? $e->subject->name : null,
                        'teacher_id' => $e->teacher_id,
                        'teacher_name' => $e->teacher ? $e->teacher->name : null,
                        'conflict' => $e->conflict,
                    ];
                })->values();

            // compute initialCandidates similar to matrix() so frontend can populate selects instantly
            $initialCandidates = [];
            $periods = range(1,9);
            $assignedByPeriod = [];
            foreach ($periods as $p) {
                $assignedByPeriod[$p] = ScheduleEntry::where('scheduling_run_id', $run->id)
                    ->where('period', $p)
                    ->get()
                    ->groupBy('teacher_id');
            }
            $loads = ScheduleEntry::where('scheduling_run_id', $run->id)
                ->whereNotNull('teacher_id')
                ->groupBy('teacher_id')
                ->selectRaw('teacher_id, count(*) as cnt')
                ->pluck('cnt','teacher_id')
                ->all();

            $sections = \App\Models\Section::with('gradeLevel')->get();
            foreach ($sections as $section) {
                foreach ($periods as $p) {
                    $entry = ScheduleEntry::where('scheduling_run_id', $run->id)->where('section_id', $section->id)->where('period', $p)->first();
                    $subjectId = $entry ? $entry->subject_id : null;
                    if (!$subjectId) continue;

                    $candColl = \App\Models\Subject::find($subjectId) ? \App\Models\Subject::find($subjectId)->teachers()->with(['gradeLevels','subjects'])->get() : \App\Models\Teacher::with(['gradeLevels','subjects'])->get();
                    $assignedMap = $assignedByPeriod[$p] ?? collect();
                    $gradeLevelId = $section ? $section->grade_level_id : null;

                    $out = [];
                    foreach ($candColl as $t) {
                        $isAssigned = isset($assignedMap[$t->id]);
                        $load = (int) ($loads[$t->id] ?? 0);
                        $available = true;
                        if (is_array($t->availability) && count($t->availability)) {
                            $flat = [];
                            if (isset($t->availability['unavailable']) && is_array($t->availability['unavailable'])) {
                                $flat = $t->availability['unavailable'];
                            } else { $flat = $t->availability; }
                            foreach ($flat as $f) { if (is_string($f) && (str_ends_with($f, ":{$p}") || (string)$f === (string)$p)) { $available = false; break; } }
                        }
                        $teaches = $t->subjects->contains('id', $subjectId);
                        $gradeOk = true;
                        if ($gradeLevelId) { if (!($t->gradeLevels && $t->gradeLevels->count())) $gradeOk = false; elseif (!$t->gradeLevels->contains('id', $gradeLevelId)) $gradeOk = false; }
                        $eligible = $available && !$isAssigned && $teaches && $gradeOk;
                        $notes = null;
                        if (!$available) $notes = 'Unavailable'; elseif ($isAssigned) $notes = 'Already assigned at this period'; elseif (!$teaches) $notes = 'Does not teach selected subject'; elseif (!$gradeOk) $notes = 'Not assigned to this grade level';
                        $out[] = [ 'id'=>$t->id, 'name'=>$t->name, 'load'=>$load, 'is_assigned'=>(bool)$isAssigned, 'eligible'=>(bool)$eligible, 'grade_level_ids'=>$t->gradeLevels->pluck('id')->all(), 'subjects'=>$t->subjects->map(fn($s)=>['id'=>$s->id,'name'=>$s->name])->values()->all(), 'notes'=>$notes ];
                    }
                    usort($out, function($a,$b){ if ($a['eligible'] !== $b['eligible']) return $a['eligible'] ? -1 : 1; if ($a['load'] !== $b['load']) return $a['load'] <=> $b['load']; return strcmp($a['name'],$b['name']); });
                    $mapKey = intval($section->id) . ':' . intval($p) . ':' . intval($subjectId);
                    $initialCandidates[$mapKey] = $out;
                }
            }

            return response()->json([ 'success' => true, 'entries' => $total, 'conflicts' => $conflicts, 'entries_data' => $entries, 'initialCandidates' => $initialCandidates ]);
        }

        // Otherwise redirect to the compact matrix view so generated results are visible.
        return redirect()->route('admin.it.scheduler.matrix', ['run' => $run->id]);
    }

    public function matrix($runId)
    {
        $run = SchedulingRun::findOrFail($runId);
        $periods = range(1,9);

        // no day dimension in this view: fetch entries for the run across periods
        $entries = ScheduleEntry::with(['section','subject','teacher'])
            ->where('scheduling_run_id', $run->id)
            ->get();

        // group entries by section:period for quick lookup
        $entriesByKey = $entries->groupBy(fn($e) => $e->section_id . ':' . $e->period);

        $gradeLevels = GradeLevel::with(['sections'])->orderBy('id')->get();

        // build grid: [grade_id][period] => [sectionEntries]
        $grid = [];
        foreach ($gradeLevels as $grade) {
            $grid[$grade->id] = [];
            foreach ($periods as $p) {
                $cells = [];
                foreach ($grade->sections as $section) {
                    $key = $section->id . ':' . $p;
                    $entry = $entriesByKey->get($key) ? $entriesByKey->get($key)->first() : null;
                    $cells[] = [
                        'section' => $section,
                        'entry' => $entry,
                    ];
                }
                $grid[$grade->id][$p] = $cells;
            }
        }

        // subjects by stage for frontend filters (simplify to arrays with id/name to avoid Eloquent model serialization surprises)
        // Build subject lists for each stage by including:
        // - subjects with their `stage` set to the target stage
        // - subjects linked to a GradeLevel whose `school_stage` matches the target stage
        // - subjects with NULL/empty stage (fallback)
        // Strict stage filtering: include only subjects explicitly marked for the stage
        // or subjects assigned to grade levels of that stage. Do NOT include subjects
        // with NULL/empty stage here because they can belong to either list unpredictably.
        $jhsSubjects = \App\Models\Subject::where('stage','jhs')
            ->orWhereHas('gradeLevels', function($q){
                $q->where('school_stage','jhs');
            })->orderBy('name')->get(['id','name','type'])->map(fn($s) => ['id' => $s->id, 'name' => $s->name, 'type' => $s->type ?? null])->unique('id')->values();

        $shsSubjects = \App\Models\Subject::where('stage','shs')
            ->orWhereHas('gradeLevels', function($q){
                $q->where('school_stage','shs');
            })->orderBy('name')->get(['id','name','type'])->map(fn($s) => ['id' => $s->id, 'name' => $s->name, 'type' => $s->type ?? null])->unique('id')->values();

        $subjectsByStage = [
            'jhs' => $jhsSubjects,
            'shs' => $shsSubjects,
        ];

        // simple teacher list for server-side rendering of selects (id/name only)
        $allTeachers = \App\Models\Teacher::orderBy('name')->get(['id','name'])->map(fn($t) => ['id' => $t->id, 'name' => $t->name])->values();

        // entries grouped by teacher for sidebar/overview
        $entriesByTeacher = $entries->groupBy('teacher_id');

        // build subjects per grade id: include subjects explicitly attached to the grade level
        $subjectsByGradeId = [];
        foreach ($gradeLevels as $grade) {
            $raw = strtolower($grade->school_stage ?? 'jhs');
            if (str_contains($raw, 'jhs') || str_starts_with($raw, 'jun')) {
                $stageKey = 'jhs';
            } elseif (str_contains($raw, 'shs') || str_starts_with($raw, 'sen')) {
                $stageKey = 'shs';
            } else {
                $stageKey = 'jhs';
            }
            $subjects = \App\Models\Subject::where('stage', $stageKey)
                ->orWhereHas('gradeLevels', function($q) use ($grade){
                    $q->where('grade_levels.id', $grade->id);
                })->orderBy('name')->get(['id','name','type'])->map(fn($s)=>['id'=>$s->id,'name'=>$s->name,'type'=>$s->type ?? null])->unique('id')->values();
            $subjectsByGradeId[$grade->id] = $subjects;
        }

        // Precompute initial teacher candidates for visible slots so the page can render teacher lists instantly.
        $initialCandidates = [];
        // compute assigned map per period for this run
        $assignedByPeriod = [];
        foreach ($periods as $p) {
            $assignedByPeriod[$p] = ScheduleEntry::where('scheduling_run_id', $run->id)
                ->where('period', $p)
                ->get()
                ->groupBy('teacher_id');
        }
        // compute loads once
        $loads = ScheduleEntry::where('scheduling_run_id', $run->id)
            ->whereNotNull('teacher_id')
            ->groupBy('teacher_id')
            ->selectRaw('teacher_id, count(*) as cnt')
            ->pluck('cnt','teacher_id')
            ->all();

        // iterate visible grid slots and compute candidates when a subject is present
        foreach ($gradeLevels as $grade) {
            foreach ($grade->sections as $section) {
                foreach ($periods as $p) {
                    $key = $section->id . ':' . $p;
                    $entry = $entriesByKey->get($key) ? $entriesByKey->get($key)->first() : null;
                    $subjectId = $entry ? $entry->subject_id : null;
                    if (!$subjectId) continue;

                    $candidates = [];
                    $subject = \App\Models\Subject::find($subjectId);
                    if ($subject) {
                        $candColl = $subject->teachers()->with(['gradeLevels','subjects'])->get();
                    } else {
                        $candColl = \App\Models\Teacher::with(['gradeLevels','subjects'])->get();
                    }

                    $assignedMap = $assignedByPeriod[$p] ?? collect();
                    $gradeLevelId = $section ? $section->grade_level_id : null;

                    foreach ($candColl as $t) {
                        $isAssigned = isset($assignedMap[$t->id]);
                        $load = (int) ($loads[$t->id] ?? 0);

                        // availability
                        $available = true;
                        if (is_array($t->availability) && count($t->availability)) {
                            $flat = [];
                            if (isset($t->availability['unavailable']) && is_array($t->availability['unavailable'])) {
                                $flat = $t->availability['unavailable'];
                            } else {
                                $flat = $t->availability;
                            }
                            foreach ($flat as $f) {
                                if (is_string($f) && (str_ends_with($f, ":{$p}") || (string)$f === (string)$p)) { $available = false; break; }
                            }
                        }

                        $teaches = true;
                        if ($subjectId) $teaches = $t->subjects->contains('id', $subjectId);

                        $gradeOk = true;
                        if ($gradeLevelId) {
                            if (!($t->gradeLevels && $t->gradeLevels->count())) $gradeOk = false;
                            elseif (!$t->gradeLevels->contains('id', $gradeLevelId)) $gradeOk = false;
                        }

                        $eligible = $available && !$isAssigned && $teaches && $gradeOk;

                        $notes = null;
                        if (!$available) $notes = 'Unavailable';
                        elseif ($isAssigned) $notes = 'Already assigned at this period';
                        elseif (!$teaches) $notes = 'Does not teach selected subject';
                        elseif (!$gradeOk) $notes = 'Not assigned to this grade level';

                        $subs = $t->subjects->map(fn($s)=>['id'=>$s->id,'name'=>$s->name])->values()->all();
                        $gradeIds = $t->gradeLevels->pluck('id')->all();

                        $candidates[] = [
                            'id' => $t->id,
                            'name' => $t->name,
                            'load' => $load,
                            'is_assigned' => (bool) $isAssigned,
                            'eligible' => (bool) $eligible,
                            'grade_level_ids' => $gradeIds,
                            'subjects' => $subs,
                            'notes' => $notes,
                        ];
                    }

                    usort($candidates, function($a,$b){
                        if ($a['eligible'] !== $b['eligible']) return $a['eligible'] ? -1 : 1;
                        if ($a['load'] !== $b['load']) return $a['load'] <=> $b['load'];
                        return strcmp($a['name'], $b['name']);
                    });

                    $mapKey = intval($section->id) . ':' . intval($p) . ':' . intval($subjectId);
                    $initialCandidates[$mapKey] = $candidates;
                }
            }
        }

        return view('admin.it.scheduler.matrix', compact('run','gradeLevels','grid','periods','subjectsByStage','entriesByTeacher','allTeachers','subjectsByGradeId','initialCandidates'));
    }

    /**
     * Return subjects for a given stage (JHS / SHS) as JSON
     */
    public function subjectsByStage($runId, $stage)
    {
        $stage = strtolower($stage);
        $subs = \App\Models\Subject::where('stage', $stage)->orderBy('name')->get(['id','name']);
        return response()->json(['subjects' => $subs]);
    }

    /**
     * Return candidate teachers for a given subject and slot (day, period) filtered by availability and current assignments.
     */
    public function teachersForSlot($runId)
    {
        $run = SchedulingRun::findOrFail($runId);
        $subjectId = request('subject_id');
        $period = request('period');
        $sectionId = request('section_id');
        // a simple search query (new)
        $q = trim((string) request('q', ''));

        // load subject teachers or all teachers, eager-load gradeLevels & subjects for richer metadata
        if ($subjectId) {
            $subject = \App\Models\Subject::find($subjectId);
            $candidates = $subject ? $subject->teachers()->with(['gradeLevels','subjects'])->get() : collect();
        } else {
            $candidates = \App\Models\Teacher::with(['gradeLevels','subjects'])->get();
        }

        // build assigned map for the run to avoid double-booking at this period
        $assignedMap = ScheduleEntry::where('scheduling_run_id', $run->id)
            ->where('period', $period)
            ->get()
            ->groupBy('teacher_id');

        // compute load (number of assignments in this run) per teacher
        $loads = ScheduleEntry::where('scheduling_run_id', $run->id)
            ->whereNotNull('teacher_id')
            ->groupBy('teacher_id')
            ->selectRaw('teacher_id, count(*) as cnt')
            ->pluck('cnt','teacher_id')
            ->all();

        // determine section's grade_level for filtering
        $section = null;
        $gradeLevelId = null;
        if ($sectionId) {
            $section = \App\Models\Section::with('gradeLevel')->find($sectionId);
            $gradeLevelId = $section ? $section->grade_level_id : null;
        }

        // build enriched candidate objects
        $out = [];
        foreach ($candidates as $t) {
            // search filter
            if ($q !== '' && stripos($t->name, $q) === false) continue;

            $isAssigned = isset($assignedMap[$t->id]);
            $load = (int) ($loads[$t->id] ?? 0);

            // availability check (period-only matching)
            $available = true;
            if (is_array($t->availability) && count($t->availability)) {
                $flat = [];
                if (isset($t->availability['unavailable']) && is_array($t->availability['unavailable'])) {
                    $flat = $t->availability['unavailable'];
                } else {
                    $flat = $t->availability;
                }
                foreach ($flat as $f) {
                    if (is_string($f) && (str_ends_with($f, ":{$period}") || (string)$f === (string)$period)) { $available = false; break; }
                }
            }

            // teaches this subject?
            $teaches = true;
            if ($subjectId) {
                $teaches = $t->subjects->contains('id', $subjectId);
            }

            // grade-level membership
            $gradeOk = true;
            if ($gradeLevelId) {
                if (!($t->gradeLevels && $t->gradeLevels->count())) $gradeOk = false;
                elseif (!$t->gradeLevels->contains('id', $gradeLevelId)) $gradeOk = false;
            }

            // Hide if any of these are true (per user rule):
            // - unavailable for the period
            // - does not teach the selected subject
            // - not assigned to the section's grade level
            // Keep teachers visible even if they are already assigned at the same period;
            // ineligible state is used only for already-assigned.
            if (!$available || !$teaches || !$gradeOk) {
                continue;
            }

            // By this point teacher is available, teaches the subject, and is in grade.
            // Mark ineligible only when already assigned at this period in the run.
            $eligible = !$isAssigned;

            $notes = null;
            if ($isAssigned) $notes = 'Already assigned at this period';

            $subs = $t->subjects->map(fn($s)=>['id'=>$s->id,'name'=>$s->name])->values()->all();
            $gradeIds = $t->gradeLevels->pluck('id')->all();

            $out[] = [
                'id' => $t->id,
                'name' => $t->name,
                'load' => $load,
                'is_assigned' => (bool) $isAssigned,
                'eligible' => (bool) $eligible,
                'grade_level_ids' => $gradeIds,
                'subjects' => $subs,
                'notes' => $notes,
            ];
        }

        // Optionally include a specific teacher (useful when editing an existing assignment)
        $includeId = request('include_teacher');
        if ($includeId) {
            $found = collect($out)->first(fn($x) => (int)$x['id'] === (int)$includeId);
            if (!$found) {
                $inc = \App\Models\Teacher::with(['gradeLevels','subjects'])->find($includeId);
                if ($inc) {
                    $isAssigned = isset($assignedMap[$inc->id]);
                    $load = (int) ($loads[$inc->id] ?? 0);
                    $available = true;
                    if (is_array($inc->availability) && count($inc->availability)) {
                        $flat = [];
                        if (isset($inc->availability['unavailable']) && is_array($inc->availability['unavailable'])) {
                            $flat = $inc->availability['unavailable'];
                        } else {
                            $flat = $inc->availability;
                        }
                        foreach ($flat as $f) {
                            if (is_string($f) && (str_ends_with($f, ":{$period}") || (string)$f === (string)$period)) { $available = false; break; }
                        }
                    }
                    $teaches = true;
                    if ($subjectId) $teaches = $inc->subjects->contains('id', $subjectId);
                    $gradeOk = true;
                    if ($gradeLevelId) {
                        if (!($inc->gradeLevels && $inc->gradeLevels->count())) $gradeOk = false;
                        elseif (!$inc->gradeLevels->contains('id', $gradeLevelId)) $gradeOk = false;
                    }
                    // For included teacher (editing), mark ineligible only when
                    // already assigned at this period. However, enforce the same
                    // visibility/hide rule: do not include the teacher if they are
                    // unavailable OR do not teach the subject OR are not in grade.
                    $eligible = !$isAssigned;
                    $notes = null;
                    if ($isAssigned) $notes = 'Already assigned at this period';

                    if (!$available || !$teaches || !$gradeOk) {
                        // do not append the included teacher because they fail
                        // the visibility rules the user requested
                    } else {
                        $out[] = [
                            'id' => $inc->id,
                            'name' => $inc->name,
                            'load' => $load,
                            'is_assigned' => (bool) $isAssigned,
                            'eligible' => (bool) $eligible,
                            'grade_level_ids' => $inc->gradeLevels->pluck('id')->all(),
                            'subjects' => $inc->subjects->map(fn($s)=>['id'=>$s->id,'name'=>$s->name])->values()->all(),
                            'notes' => $notes,
                        ];
                    }
                }
            }
        }

        // sort: eligible first, then by load asc, then name
        usort($out, function($a,$b){
            if ($a['eligible'] !== $b['eligible']) return $a['eligible'] ? -1 : 1;
            if ($a['load'] !== $b['load']) return $a['load'] <=> $b['load'];
            return strcmp($a['name'], $b['name']);
        });

        return response()->json(['candidates' => $out]);
    }

    /**
     * Return candidate teachers for multiple slots in one request.
     * Expects POST JSON: { slots: [ { section_id, period, subject_id, key? }, ... ] }
     * Returns: { results: { "section:period:subject": [candidates], ... } }
     */
    public function teachersForSlots(Request $request, $runId)
    {
        $run = SchedulingRun::findOrFail($runId);
        $slots = $request->input('slots');
        if (!is_array($slots)) {
            return response()->json(['error' => 'Invalid payload, expected slots array'], 400);
        }

        // Precompute maps shared across slots to optimize repeated work
        // build assigned map per period
        $allPeriods = array_values(array_unique(array_map(fn($s)=>($s['period'] ?? null), $slots)));
        $assignedByPeriod = [];
        foreach ($allPeriods as $p) {
            $assignedByPeriod[$p] = ScheduleEntry::where('scheduling_run_id', $run->id)
                ->where('period', $p)
                ->get()
                ->groupBy('teacher_id');
        }

        // compute loads once for the run
        $loads = ScheduleEntry::where('scheduling_run_id', $run->id)
            ->whereNotNull('teacher_id')
            ->groupBy('teacher_id')
            ->selectRaw('teacher_id, count(*) as cnt')
            ->pluck('cnt','teacher_id')
            ->all();

        $results = [];
        foreach ($slots as $slot) {
            $sectionId = $slot['section_id'] ?? null;
            $period = $slot['period'] ?? null;
            $subjectId = $slot['subject_id'] ?? null;
            $key = $slot['key'] ?? null;
            $mapKey = $key ?: (intval($sectionId) . ':' . intval($period) . ':' . intval($subjectId));

            // load subject teachers or all teachers
            if ($subjectId) {
                $subject = \App\Models\Subject::find($subjectId);
                $candidates = $subject ? $subject->teachers()->with(['gradeLevels','subjects'])->get() : collect();
            } else {
                $candidates = \App\Models\Teacher::with(['gradeLevels','subjects'])->get();
            }

            // assigned map for this period
            $assignedMap = $assignedByPeriod[$period] ?? collect();

            $section = null;
            $gradeLevelId = null;
            if ($sectionId) {
                $section = \App\Models\Section::with('gradeLevel')->find($sectionId);
                $gradeLevelId = $section ? $section->grade_level_id : null;
            }

            $out = [];
            foreach ($candidates as $t) {
                $isAssigned = isset($assignedMap[$t->id]);
                $load = (int) ($loads[$t->id] ?? 0);

                // availability
                $available = true;
                if (is_array($t->availability) && count($t->availability)) {
                    $flat = [];
                    if (isset($t->availability['unavailable']) && is_array($t->availability['unavailable'])) {
                        $flat = $t->availability['unavailable'];
                    } else {
                        $flat = $t->availability;
                    }
                    foreach ($flat as $f) {
                        if (is_string($f) && (str_ends_with($f, ":{$period}") || (string)$f === (string)$period)) { $available = false; break; }
                    }
                }

                // teaches this subject?
                $teaches = true;
                if ($subjectId) {
                    $teaches = $t->subjects->contains('id', $subjectId);
                }

                // grade-level membership
                $gradeOk = true;
                if ($gradeLevelId) {
                    if (!($t->gradeLevels && $t->gradeLevels->count())) $gradeOk = false;
                    elseif (!$t->gradeLevels->contains('id', $gradeLevelId)) $gradeOk = false;
                }

                // Hide if any of these are true (per user rule):
                // - unavailable for the period
                // - does not teach the selected subject
                // - not assigned to the section's grade level
                if (!$available || !$teaches || !$gradeOk) {
                    continue;
                }

                // By this point teacher is available, teaches the subject, and is in grade.
                // Mark ineligible only when already assigned at this period in the run.
                $eligible = !$isAssigned;

                $notes = null;
                if ($isAssigned) $notes = 'Already assigned at this period';

                $subs = $t->subjects->map(fn($s)=>['id'=>$s->id,'name'=>$s->name])->values()->all();
                $gradeIds = $t->gradeLevels->pluck('id')->all();

                $out[] = [
                    'id' => $t->id,
                    'name' => $t->name,
                    'load' => $load,
                    'is_assigned' => (bool) $isAssigned,
                    'eligible' => (bool) $eligible,
                    'grade_level_ids' => $gradeIds,
                    'subjects' => $subs,
                    'notes' => $notes,
                ];
            }

            usort($out, function($a,$b){
                if ($a['eligible'] !== $b['eligible']) return $a['eligible'] ? -1 : 1;
                if ($a['load'] !== $b['load']) return $a['load'] <=> $b['load'];
                return strcmp($a['name'], $b['name']);
            });

            $results[$mapKey] = $out;
        }

        return response()->json(['results' => $results]);
    }

    /**
     * Persist an assignment change for a schedule entry (subject_id and/or teacher_id).
     */
    public function assignEntry(Request $request, $runId, $entryId)
    {
        $run = SchedulingRun::findOrFail($runId);
        $entry = ScheduleEntry::findOrFail($entryId);

        $request->validate([
            'subject_id' => 'nullable|integer|exists:subjects,id',
            'teacher_id' => 'nullable|integer|exists:teachers,id',
        ]);

        $subjectId = $request->input('subject_id');
        $teacherId = $request->input('teacher_id');

        // server-side validation: ensure subject fits grade stage and is unique per section
        if ($subjectId !== null) {
            $subject = \App\Models\Subject::find($subjectId);
            $section = $entry->section()->with('gradeLevel')->first();
            $gradeLevel = $section ? $section->gradeLevel : null;
            // check stage match
            if ($gradeLevel && $subject && $subject->stage && $gradeLevel->school_stage && $subject->stage !== $gradeLevel->school_stage) {
                return response()->json(['success'=>false,'errors'=>['subject_id' => ['Subject not valid for this grade stage']]], 422);
            }
                // Prevent assigning 'special subjects' to regular JHS sections
                if ($subject && strtolower(trim($subject->type ?? '')) === 'special subjects') {
                    $isSpecialSection = isset($section->is_special) && $section->is_special;
                    if (! $isSpecialSection) {
                        return response()->json(['success'=>false,'errors'=>['subject_id' => ['Special subjects may only be assigned to designated special sections']]], 422);
                    }
                }
            // uniqueness per section: no duplicate subject for other periods
            $exists = ScheduleEntry::where('scheduling_run_id', $run->id)
                ->where('section_id', $entry->section_id)
                ->where('subject_id', $subjectId)
                ->where('id', '<>', $entry->id)
                ->exists();
            if ($exists) {
                return response()->json(['success'=>false,'errors'=>['subject_id' => ['This subject is already assigned to another period for this section']]], 422);
            }
            $entry->subject_id = $subjectId;
        }

            if ($teacherId !== null) {
            $teacher = \App\Models\Teacher::with('subjects','gradeLevels')->find($teacherId);
            if (!$teacher) {
                return response()->json(['success'=>false,'errors'=>['teacher_id' => ['Invalid teacher']]], 422);
            }
            // teacher must teach this subject if subject selected
            if ($entry->subject_id && !$teacher->subjects->contains('id', $entry->subject_id)) {
                return response()->json(['success'=>false,'errors'=>['teacher_id' => ['Teacher does not teach selected subject']]], 422);
            }
            // teacher must be allowed for this grade level
            $section = $entry->section()->with('gradeLevel')->first();
            $gradeLevelId = $section ? $section->grade_level_id : null;
            // strict: teacher must be explicitly assigned to the grade level
            if ($gradeLevelId) {
                if (!($teacher->gradeLevels && $teacher->gradeLevels->count() && $teacher->gradeLevels->contains('id', $gradeLevelId))) {
                    return response()->json(['success'=>false,'errors'=>['teacher_id' => ['Teacher not assigned to this grade level']]], 422);
                }
            }
            // teacher availability: ensure not already assigned at this period in this run
            $conflict = ScheduleEntry::where('scheduling_run_id', $run->id)
                ->where('period', $entry->period)
                ->where('teacher_id', $teacherId)
                ->where('id', '<>', $entry->id)
                ->exists();
            if ($conflict) {
                return response()->json(['success'=>false,'errors'=>['teacher_id' => ['Teacher is already assigned at this period']]], 422);
            }
            $entry->teacher_id = $teacherId;
        }
        $entry->save();

        // re-run validation for the run to refresh conflicts
        $this->validateRun($run);

        return response()->json(['success' => true, 'entry' => $entry->fresh()]);
    }

    /**
     * Create a new schedule entry for a run (used by inline creation)
     */
    public function createEntry(Request $request, $runId)
    {
        $run = SchedulingRun::findOrFail($runId);

        $request->validate([
            'section_id' => 'required|integer|exists:sections,id',
            'period' => 'required|integer',
            'subject_id' => 'nullable|integer|exists:subjects,id',
            'teacher_id' => 'nullable|integer|exists:teachers,id',
        ]);

        $sectionId = $request->input('section_id');
        $period = $request->input('period');
        $subjectId = $request->input('subject_id');
        $teacherId = $request->input('teacher_id');

        // ensure no duplicate entry exists for this run/section/period
        $exists = ScheduleEntry::where('scheduling_run_id', $run->id)
            ->where('section_id', $sectionId)
            ->where('period', $period)
            ->exists();
        if ($exists) {
            return response()->json(['success' => false, 'errors' => ['slot' => ['Slot already has an assignment']]], 422);
        }

        $entry = ScheduleEntry::create([
            'scheduling_run_id' => $run->id,
            'section_id' => $sectionId,
            'day' => 1,
            'period' => $period,
            'subject_id' => $subjectId,
            'teacher_id' => $teacherId,
            'conflict' => null,
        ]);

        // If subject/teacher were provided, run same server-side validations as assignEntry
        if ($subjectId !== null || $teacherId !== null) {
            // reuse assignEntry's logic by calling it (but assignEntry expects an entry id)
            // so reload entry and run validation logic inline
            if ($subjectId !== null) {
                $subject = \App\Models\Subject::find($subjectId);
                $section = $entry->section()->with('gradeLevel')->first();
                $gradeLevel = $section ? $section->gradeLevel : null;
                if ($gradeLevel && $subject && $subject->stage && $gradeLevel->school_stage && $subject->stage !== $gradeLevel->school_stage) {
                    $entry->delete();
                    return response()->json(['success'=>false,'errors'=>['subject_id' => ['Subject not valid for this grade stage']]], 422);
                }
                // Prevent assigning 'special subjects' to regular JHS sections
                if ($subject && strtolower(trim($subject->type ?? '')) === 'special subjects') {
                    $isSpecialSection = isset($section->is_special) && $section->is_special;
                    if (! $isSpecialSection) {
                        $entry->delete();
                        return response()->json(['success'=>false,'errors'=>['subject_id' => ['Special subjects may only be assigned to designated special sections']]], 422);
                    }
                }
                $dupe = ScheduleEntry::where('scheduling_run_id', $run->id)
                    ->where('section_id', $entry->section_id)
                    ->where('subject_id', $subjectId)
                    ->where('id', '<>', $entry->id)
                    ->exists();
                if ($dupe) { $entry->delete(); return response()->json(['success'=>false,'errors'=>['subject_id' => ['This subject is already assigned to another period for this section']]], 422); }
            }
            if ($teacherId !== null) {
                $teacher = \App\Models\Teacher::with('subjects','gradeLevels')->find($teacherId);
                if (!$teacher) { $entry->delete(); return response()->json(['success'=>false,'errors'=>['teacher_id' => ['Invalid teacher']]], 422); }
                if ($entry->subject_id && !$teacher->subjects->contains('id', $entry->subject_id)) { $entry->delete(); return response()->json(['success'=>false,'errors'=>['teacher_id' => ['Teacher does not teach selected subject']]], 422); }
                $section = $entry->section()->with('gradeLevel')->first();
                $gradeLevelId = $section ? $section->grade_level_id : null;
                // strict: teacher must be explicitly assigned to the grade level
                if ($gradeLevelId) {
                    if (!($teacher->gradeLevels && $teacher->gradeLevels->count() && $teacher->gradeLevels->contains('id', $gradeLevelId))) { $entry->delete(); return response()->json(['success'=>false,'errors'=>['teacher_id' => ['Teacher not assigned to this grade level']]], 422); }
                }
                $conflict = ScheduleEntry::where('scheduling_run_id', $run->id)
                    ->where('period', $entry->period)
                    ->where('teacher_id', $teacherId)
                    ->where('id', '<>', $entry->id)
                    ->exists();
                if ($conflict) { $entry->delete(); return response()->json(['success'=>false,'errors'=>['teacher_id' => ['Teacher is already assigned at this period']]], 422); }
            }
        }

        // re-run validation for the run to refresh conflicts
        $this->validateRun($run);

        return response()->json(['success' => true, 'entry' => $entry->fresh()]);
    }

    /**
     * Suggest substitute teachers for each slot of a given teacher in a run.
     */
    public function suggestSubstitutes($runId, $teacherId)
    {
        $run = SchedulingRun::findOrFail($runId);
        $teacher = \App\Models\Teacher::findOrFail($teacherId);

        $entries = ScheduleEntry::with(['section','subject'])
            ->where('scheduling_run_id', $run->id)
            ->where('teacher_id', $teacher->id)
            ->get();

        $days = range(1,5);
        $maxPeriod = $entries->max('period') ?? 8;

            $substitutesBySlot = [];
            // build assigned map by slot (day:period) so we can avoid teachers already booked at the same slot
            $assignedMap = ScheduleEntry::where('scheduling_run_id', $run->id)
                ->get()
                ->groupBy(fn($e) => "{$e->day}:{$e->period}");

        foreach ($entries as $e) {
            $slotKey = "{$e->day}:{$e->period}";
            $subject = $e->subject;

            // candidates: teachers who teach the subject
            $candidates = $subject ? $subject->teachers()->get() : \App\Models\Teacher::all();

            $candidates = $candidates->filter(function ($t) use ($slotKey, $assignedMap, $e) {
                // availability
                if (is_array($t->availability) && count($t->availability)) {
                    $flat = [];
                    if (isset($t->availability['unavailable']) && is_array($t->availability['unavailable'])) {
                        $flat = $t->availability['unavailable'];
                    } else {
                        $flat = $t->availability;
                    }
                    if (in_array($slotKey, $flat)) return false;
                }

                // already assigned elsewhere at this slot
                if (isset($assignedMap[$slotKey])) {
                    foreach ($assignedMap[$slotKey] as $ass) {
                        if ($ass->teacher_id === $t->id) return false;
                    }
                }

                // strict: only consider teachers explicitly assigned to this grade level
                if ($e->section) {
                    $gid = $e->section->grade_level_id;
                    if (!($t->gradeLevels && $t->gradeLevels->count())) return false;
                    if (!$t->gradeLevels->contains('id', $gid)) return false;
                }

                return true;
            });

            // map to simple data
            $substitutesBySlot[$slotKey] = $candidates->map(fn($c) => ['id'=>$c->id,'name'=>$c->name])->values()->all();
        }

        // also prepare teacher's own timetable entries by slot for display
        $entriesBySlot = [];
        foreach ($entries as $e) {
            $entriesBySlot[$e->day][$e->period] = $e;
        }

        return view('admin.it.scheduler.teacher_substitutes', compact('run','teacher','entriesBySlot','substitutesBySlot','days','maxPeriod'));
    }

    /**
     * Suggest substitutes for a specific schedule entry (AJAX JSON)
     */
    public function suggestSubstitutesForEntry($runId, $entryId)
    {
        $run = SchedulingRun::findOrFail($runId);
        $entry = ScheduleEntry::with(['subject','section'])->findOrFail($entryId);

        $slotKey = "{$entry->day}:{$entry->period}";

        $subject = $entry->subject;

        // candidates: teachers who teach the subject or any teacher if subject missing
        $candidates = $subject ? $subject->teachers()->get() : \App\Models\Teacher::all();

        // build assigned map for this run
        $assignedMap = ScheduleEntry::where('scheduling_run_id', $run->id)
            ->get()
            ->groupBy(fn($e) => "{$e->day}:{$e->period}");

        $candidates = $candidates->filter(function ($t) use ($slotKey, $assignedMap, $entry) {
            // availability
            if (is_array($t->availability) && count($t->availability)) {
                $flat = [];
                if (isset($t->availability['unavailable']) && is_array($t->availability['unavailable'])) {
                    $flat = $t->availability['unavailable'];
                } else {
                    $flat = $t->availability;
                }
                if (in_array($slotKey, $flat)) return false;
            }

            // already assigned elsewhere at this slot
            if (isset($assignedMap[$slotKey])) {
                foreach ($assignedMap[$slotKey] as $ass) {
                    if ($ass->teacher_id === $t->id) return false;
                }
            }

            // strict: only consider teachers explicitly assigned to this grade level
            if ($entry->section) {
                $gid = $entry->section->grade_level_id;
                if (!($t->gradeLevels && $t->gradeLevels->count())) return false;
                if (!$t->gradeLevels->contains('id', $gid)) return false;
            }

            return true;
        });

        $data = $candidates->map(fn($c) => ['id'=>$c->id,'name'=>$c->name])->values()->all();

        return response()->json(['candidates' => $data]);
    }

    /**
     * Return a schedule entry JSON including relations and conflicts.
     */
    public function getEntry($runId, $entryId)
    {
        $run = SchedulingRun::findOrFail($runId);
        $entry = ScheduleEntry::with(['subject','teacher','section'])->findOrFail($entryId);
        return response()->json(['entry' => $entry]);
    }

    /**
     * Apply a substitute teacher to a schedule entry and record a Substitution.
     */
    public function applySubstitute(Request $request, $runId, $entryId)
    {
        $run = SchedulingRun::findOrFail($runId);
        $entry = ScheduleEntry::findOrFail($entryId);

        $request->validate(['substitute_id' => 'required|integer|exists:teachers,id']);

        $subId = $request->input('substitute_id');

        // create substitution record
        $sub = \App\Models\Substitution::create([
            'absence_id' => null,
            'substitute_teacher_id' => $subId,
            'schedule_entry_id' => $entry->id,
            'applied_at' => now(),
            'created_by' => \Illuminate\Support\Facades\Auth::id(),
        ]);

        // update the schedule entry to point to substitute
        $entry->teacher_id = $subId;
        $entry->save();

        $substitute = \App\Models\Teacher::find($subId);

        return response()->json(['success' => true, 'substitution_id' => $sub->id, 'substitute_name' => $substitute ? $substitute->name : null]);
    }

    /**
     * Validate a scheduling run and annotate schedule_entries with conflict info.
     *
     * @param SchedulingRun $run
     * @return void
     */
    private function validateRun(SchedulingRun $run): void
    {
        $entries = ScheduleEntry::with(['section','teacher.subjects','subject'])
            ->where('scheduling_run_id', $run->id)
            ->get();

        $conflictsByEntry = [];

        // maps for detection
        $teacherSlotMap = []; // teacherId:day:period => [entries]
        $sectionSlotMap = []; // sectionId:day:period => [entries]
        $teacherDayCounts = []; // teacherId:day => count

        foreach ($entries as $e) {
            // section slot
            $skey = "{$e->section_id}:{$e->day}:{$e->period}";
            $sectionSlotMap[$skey][] = $e;

            if ($e->teacher_id) {
                $tkey = "{$e->teacher_id}:{$e->day}:{$e->period}";
                $teacherSlotMap[$tkey][] = $e;

                $tdkey = "{$e->teacher_id}:{$e->day}";
                $teacherDayCounts[$tdkey][] = $e;
            }

        }

        // detect double-bookings (teacher in multiple places same slot)
        foreach ($teacherSlotMap as $slot => $list) {
            if (count($list) > 1) {
                $ids = array_map(fn($x) => $x->id, $list);
                foreach ($list as $entry) {
                    $otherSections = array_values(array_filter(array_map(fn($x) => $x->section->name ?? $x->section_id, $list), fn($n) => $n !== ($entry->section->name ?? $entry->section_id)));
                    $conflictsByEntry[$entry->id][] = [
                        'type' => 'double_booking',
                        'message' => 'Teacher double-booked for this slot (' . implode(', ', $otherSections) . ')',
                        'others' => $ids,
                    ];
                }
            }
        }

        // note: section slot overlaps are not treated as a separate conflict type here;
        // we only flag duplicate subjects within a section and teacher double-bookings/overloads.

        // NOTE: duplicate-subject across multiple periods is intentionally not treated as a conflict
        // because the generator intentionally selects a set of distinct subjects per section and
        // cycles them to fill slots. Retain only double-booking and overload checks here.

        // detect teacher overload per-day and per-week
        // per-day
        foreach ($teacherDayCounts as $tdkey => $list) {
            [$teacherId, $day] = explode(':', $tdkey);
            $teacher = \App\Models\Teacher::find($teacherId);
            if ($teacher && $teacher->max_load_per_day && count($list) > $teacher->max_load_per_day) {
                // mark the entries beyond the allowed load
                $limit = (int) $teacher->max_load_per_day;
                // sort list by id (stable)
                usort($list, fn($a,$b) => $a->id <=> $b->id);
                foreach (array_slice($list, $limit) as $extra) {
                    $conflictsByEntry[$extra->id][] = [
                        'type' => 'overload_day',
                        'message' => "Teacher exceeds max load per day ({$limit})",
                    ];
                }
            }
        }

        // per-week overload
        $teacherWeekCounts = [];
        foreach ($entries as $e) {
            if ($e->teacher_id) $teacherWeekCounts[$e->teacher_id][] = $e;
        }
        foreach ($teacherWeekCounts as $tid => $list) {
            $teacher = \App\Models\Teacher::find($tid);
            if ($teacher && $teacher->max_load_per_week && count($list) > $teacher->max_load_per_week) {
                $limit = (int) $teacher->max_load_per_week;
                usort($list, fn($a,$b) => $a->id <=> $b->id);
                foreach (array_slice($list, $limit) as $extra) {
                    $conflictsByEntry[$extra->id][] = [
                        'type' => 'overload_week',
                        'message' => "Teacher exceeds max load per week ({$limit})",
                    ];
                }
            }
        }

        // persist conflicts back to entries
        foreach ($entries as $e) {
            $e->conflict = $conflictsByEntry[$e->id] ?? null;
            $e->save();
        }
    }
}

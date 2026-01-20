<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\Models\Teacher;
use App\Models\Section;
use App\Models\Subject;

class OverviewController extends Controller
{
    /**
     * Show admin overview dashboard with metric cards.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        if (! $user || $user->role !== 'it_coordinator') {
            abort(403);
        }

        $counts = Cache::remember('admin_overview_counts', 60, function () {
            return [
                'teachers' => Teacher::count(),
                'sections' => Section::count(),
                'subjects' => Subject::count(),
            ];
        });

        // Calculate workload summary
        $workloadSummary = $this->calculateWorkloadSummary();

        // Calculate subject load balance
        $subjectLoadBalance = $this->calculateSubjectLoadBalance();

        // Get last scheduling run
        $lastSchedulingRun = \App\Models\SchedulingRun::orderBy('created_at', 'desc')->first();
        $schedulingStatus = $this->calculateSchedulingStatus($lastSchedulingRun);
        $conflictReport = $this->calculateConflictReport($lastSchedulingRun);
        $teacherAvailability = $this->calculateTeacherAvailability();
        $recentActivity = $this->calculateRecentActivity();

        return view('admin.dashboard', [
            'teachersCount' => $counts['teachers'],
            'sectionsCount' => $counts['sections'],
            'subjectsCount' => $counts['subjects'],
            'workloadSummary' => $workloadSummary,
            'subjectLoadBalance' => $subjectLoadBalance,
            'lastSchedulingRun' => $lastSchedulingRun,
            'schedulingStatus' => $schedulingStatus,
            'conflictReport' => $conflictReport,
            'teacherAvailability' => $teacherAvailability,
            'recentActivity' => $recentActivity,
        ]);
    }

    /**
     * Calculate workload summary for each teacher.
     * Returns array of teachers with their teaching hours and overload status.
     */
    private function calculateWorkloadSummary()
    {
        $teachers = Teacher::all();
        $workload = [];

        foreach ($teachers as $teacher) {
            // Count subjects assigned
            $subjectsCount = $teacher->subjects()->count();
            
            // Estimate hours: assume 5 hours per subject (50 min × 6 periods per week)
            // This is a placeholder calculation
            $estimatedHours = $subjectsCount * 5;

            $maxLoad = $teacher->max_load_per_week ?? 40;
            $isOverloaded = $estimatedHours > $maxLoad;

            $workload[] = [
                'id' => $teacher->id,
                'name' => $teacher->name,
                'hours' => $estimatedHours,
                'max_load' => $maxLoad,
                'overloaded' => $isOverloaded,
                'subjects_count' => $subjectsCount,
            ];
        }

        // Sort by overloaded first, then by hours descending
        usort($workload, function ($a, $b) {
            if ($a['overloaded'] !== $b['overloaded']) {
                return $b['overloaded'] <=> $a['overloaded'];
            }
            return $b['hours'] <=> $a['hours'];
        });

        return $workload;
    }

    /**
     * Calculate subject load balance organized by school stage.
     * Junior High: Grade 7-10 in columns
     * Senior High: Grade 11 & 12, each with First Sem & Second Sem
     */
    private function calculateSubjectLoadBalance()
    {
        $gradeLevels = \App\Models\GradeLevel::with('subjects')
            ->orderBy('year', 'asc')
            ->get();

        $juniorHighGrades = [];
        $seniorHighGrades = [];

        // Separate grade levels by school stage
        foreach ($gradeLevels as $gradeLevel) {
            if ($gradeLevel->school_stage === 'junior' || $gradeLevel->year <= 10) {
                $juniorHighGrades[] = $gradeLevel;
            } else {
                $seniorHighGrades[] = $gradeLevel;
            }
        }

        $juniorHighTable = $this->buildSubjectCountTable($juniorHighGrades, 'junior');
        $seniorHighTable = $this->buildSeniorHighTable($seniorHighGrades);

        return [
            'junior_high' => $juniorHighTable,
            'senior_high' => $seniorHighTable,
        ];
    }

    /**
     * Build subject count table for Junior High.
     */
    private function buildSubjectCountTable($gradeLevels, $stage)
    {
        $subjectCounts = [];

        // Get all subjects from these grade levels
        $subjectIds = [];
        foreach ($gradeLevels as $gl) {
            $subjectIds = array_merge($subjectIds, $gl->subjects()->pluck('subjects.id')->toArray());
        }
        $subjectIds = array_unique($subjectIds);

        $subjects = \App\Models\Subject::whereIn('id', $subjectIds)->orderBy('name')->get();

        foreach ($subjects as $subject) {
            $row = ['subject' => $subject->name, 'counts' => []];

            foreach ($gradeLevels as $gradeLevel) {
                $count = $gradeLevel->subjects()
                    ->where('subjects.id', $subject->id)
                    ->first()
                    ?->teachers()
                    ->count() ?? 0;

                $row['counts'][$gradeLevel->name] = $count;
            }

            $subjectCounts[] = $row;
        }

        return [
            'grades' => $gradeLevels,
            'subjects' => $subjectCounts,
        ];
    }

    /**
     * Build subject count table for Senior High (with Grade 11/12 and semesters).
     */
    private function buildSeniorHighTable($gradeLevels)
    {
        $subjectCounts = [];

        // Group by year first
        $groupedByYear = [];
        foreach ($gradeLevels as $gl) {
            if (!isset($groupedByYear[$gl->year])) {
                $groupedByYear[$gl->year] = [];
            }
            $groupedByYear[$gl->year][] = $gl;
        }

        // Get all subjects from these grade levels
        $subjectIds = [];
        foreach ($gradeLevels as $gl) {
            $subjectIds = array_merge($subjectIds, $gl->subjects()->pluck('subjects.id')->toArray());
        }
        $subjectIds = array_unique($subjectIds);

        $subjects = \App\Models\Subject::whereIn('id', $subjectIds)->orderBy('name')->get();

        foreach ($subjects as $subject) {
            $row = ['subject' => $subject->name, 'counts' => []];

            foreach ($gradeLevels as $gradeLevel) {
                $count = $gradeLevel->subjects()
                    ->where('subjects.id', $subject->id)
                    ->first()
                    ?->teachers()
                    ->count() ?? 0;

                $row['counts'][$gradeLevel->name] = $count;
            }

            $subjectCounts[] = $row;
        }

        return [
            'grades_by_year' => $groupedByYear,
            'all_grades' => $gradeLevels,
            'subjects' => $subjectCounts,
        ];
    }

    /**
     * Calculate scheduling status summary.
     */
    private function calculateSchedulingStatus($run)
    {
        if (!$run) {
            return [
                'exists' => false,
                'status' => 'No schedule generated',
                'status_badge' => 'empty',
                'is_published' => false,
                'total_entries' => 0,
                'conflicts' => 0,
            ];
        }

        $totalEntries = \App\Models\ScheduleEntry::where('scheduling_run_id', $run->id)->count();
        $conflicts = \App\Models\ScheduleEntry::where('scheduling_run_id', $run->id)
            ->where('conflict', '!=', null)
            ->count();

        $statusBadge = match($run->status) {
            'draft' => 'warning',
            'locked' => 'info',
            'published' => 'success',
            default => 'secondary',
        };

        return [
            'exists' => true,
            'run_id' => $run->id,
            'run_name' => $run->name ?? 'Schedule Run #' . $run->id,
            'status' => ucfirst($run->status),
            'status_badge' => $statusBadge,
            'is_published' => $run->status === 'published',
            'published_at' => $run->published_at,
            'locked_at' => $run->locked_at,
            'created_at' => $run->created_at,
            'total_entries' => $totalEntries,
            'conflicts' => $conflicts,
            'conflict_free' => $conflicts === 0,
        ];
    }

    /**
     * Calculate conflict report details.
     */
    private function calculateConflictReport($run)
    {
        if (!$run) {
            return [
                'exists' => false,
                'total_conflicts' => 0,
                'conflict_free' => true,
                'conflicts' => [],
            ];
        }

        $conflictEntries = \App\Models\ScheduleEntry::where('scheduling_run_id', $run->id)
            ->where('conflict', '!=', null)
            ->with(['teacher', 'subject', 'section'])
            ->orderBy('day')
            ->orderBy('period')
            ->limit(10)
            ->get();

        $totalConflicts = \App\Models\ScheduleEntry::where('scheduling_run_id', $run->id)
            ->where('conflict', '!=', null)
            ->count();

        $conflictDetails = [];
        foreach ($conflictEntries as $entry) {
            $conflictDetails[] = [
                'teacher' => $entry->teacher?->name ?? 'Unknown',
                'subject' => $entry->subject?->name ?? 'Unknown',
                'section' => $entry->section?->name ?? 'Unknown',
                'day' => $entry->day,
                'period' => $entry->period,
                'conflict_type' => is_array($entry->conflict) ? implode(', ', $entry->conflict) : $entry->conflict,
            ];
        }

        return [
            'exists' => true,
            'run_id' => $run->id,
            'total_conflicts' => $totalConflicts,
            'conflict_free' => $totalConflicts === 0,
            'conflicts' => $conflictDetails,
            'has_more' => $totalConflicts > 10,
        ];
    }

    /**
     * Calculate teacher availability summary.
     */
    private function calculateTeacherAvailability()
    {
        $teachers = Teacher::all();
        $totalTeachers = $teachers->count();

        // Count teachers with availability restrictions
        $availableTeachers = [];
        $restrictedTeachers = [];
        
        foreach ($teachers as $teacher) {
            $availability = $teacher->availability;
            
            // If availability is null or empty, assume fully available
            if (empty($availability) || !is_array($availability)) {
                $availableTeachers[] = $teacher;
            } else {
                // Check if there are any restrictions in the availability array
                $hasRestrictions = false;
                foreach ($availability as $day => $periods) {
                    if (is_array($periods) && count($periods) < 8) { // Assuming 8 periods per day
                        $hasRestrictions = true;
                        break;
                    }
                }
                
                if ($hasRestrictions) {
                    $restrictedTeachers[] = [
                        'name' => $teacher->name,
                        'restrictions' => $this->summarizeAvailability($availability),
                    ];
                } else {
                    $availableTeachers[] = $teacher;
                }
            }
        }

        return [
            'total_teachers' => $totalTeachers,
            'fully_available' => count($availableTeachers),
            'restricted' => count($restrictedTeachers),
            'restricted_teachers' => array_slice($restrictedTeachers, 0, 10), // Show top 10
            'has_more' => count($restrictedTeachers) > 10,
        ];
    }

    /**
     * Summarize availability restrictions.
     */
    private function summarizeAvailability($availability)
    {
        if (!is_array($availability) || empty($availability)) {
            return 'No restrictions';
        }

        $restrictedDays = [];
        foreach ($availability as $day => $periods) {
            if (is_array($periods) && count($periods) < 8) {
                $restrictedDays[] = "Day $day";
            }
        }

        return count($restrictedDays) > 0 
            ? 'Limited on ' . implode(', ', array_slice($restrictedDays, 0, 3))
            : 'No restrictions';
    }

    /**
     * Calculate recent activity across the system.
     */
    private function calculateRecentActivity()
    {
        $activities = [];

        // Recent teachers
        $recentTeachers = Teacher::orderBy('created_at', 'desc')->limit(3)->get();
        foreach ($recentTeachers as $teacher) {
            $activities[] = [
                'type' => 'teacher',
                'icon' => '👤',
                'title' => 'New Teacher Added',
                'description' => $teacher->name,
                'timestamp' => $teacher->created_at,
                'link' => route('admin.teachers.edit', $teacher->id),
            ];
        }

        // Recent subjects
        $recentSubjects = \App\Models\Subject::orderBy('created_at', 'desc')->limit(3)->get();
        foreach ($recentSubjects as $subject) {
            $activities[] = [
                'type' => 'subject',
                'icon' => '📚',
                'title' => 'New Subject Created',
                'description' => $subject->name,
                'timestamp' => $subject->created_at,
                'link' => route('admin.subjects.edit', $subject->id),
            ];
        }

        // Recent sections
        $recentSections = \App\Models\Section::orderBy('created_at', 'desc')->limit(3)->get();
        foreach ($recentSections as $section) {
            $activities[] = [
                'type' => 'section',
                'icon' => '🏫',
                'title' => 'New Section Created',
                'description' => $section->name,
                'timestamp' => $section->created_at,
                'link' => route('admin.grade-levels.index'),
            ];
        }

        // Recent scheduling runs
        $recentRuns = \App\Models\SchedulingRun::orderBy('created_at', 'desc')->limit(3)->get();
        foreach ($recentRuns as $run) {
            $activities[] = [
                'type' => 'schedule',
                'icon' => '📅',
                'title' => 'Schedule Generated',
                'description' => $run->name ?? 'Run #' . $run->id,
                'timestamp' => $run->created_at,
                'link' => route('admin.scheduler.show', $run->id),
            ];
        }

        // Sort all activities by timestamp and limit to 10
        usort($activities, function ($a, $b) {
            return $b['timestamp'] <=> $a['timestamp'];
        });

        return array_slice($activities, 0, 10);
    }

    /**
     * Get final timetable data for dashboard display.
     * Returns formatted schedule entries with section, time, subject, and teacher.
     */
    private function getFinalTimetable($run)
    {
        if (!$run) {
            return [];
        }

        // Get schedule entries with relationships
        $entries = \App\Models\ScheduleEntry::where('scheduling_run_id', $run->id)
            ->with(['section', 'subject', 'teacher'])
            ->orderBy('day')
            ->orderBy('period')
            ->get();

        $timetable = [];
        $dayNames = [1 => 'Monday', 2 => 'Tuesday', 3 => 'Wednesday', 4 => 'Thursday', 5 => 'Friday'];

        foreach ($entries as $entry) {
            // Get period times from scheduling config
            $timeRange = $this->getPeriodTimeRange($entry->period, $run);
            
            $timetable[] = [
                'section' => $entry->section?->name ?? 'Unknown',
                'day_name' => $dayNames[$entry->day] ?? "Day {$entry->day}",
                'day' => $entry->day,
                'period' => $entry->period,
                'time_range' => $timeRange,
                'subject' => $entry->subject?->name ?? 'Unknown',
                'teacher' => $entry->teacher?->name ?? 'Unknown',
            ];
        }

        return $timetable;
    }

    /**
     * Get period time range based on scheduling configuration.
     */
    private function getPeriodTimeRange($period, $run)
    {
        // Try to get period times from scheduling config
        $config = \App\Models\SchedulingConfig::find($run->meta['schedule_level'] ?? 'junior_high');
        
        if ($config && $config->dayConfigs) {
            $dayConfig = $config->dayConfigs->first();
            if ($dayConfig && $dayConfig->period_duration) {
                $duration = $dayConfig->period_duration;
                $startTime = 7 * 60 + 30; // 7:30 AM in minutes
                
                // Calculate start time for this period
                $periodStart = $startTime + ($period - 1) * $duration;
                
                // Add breaks if needed (simplified logic)
                if ($period >= 3) $periodStart += 15; // Morning break after period 2
                if ($period >= 5) $periodStart += 60; // Lunch break after period 4
                
                $periodEnd = $periodStart + $duration;
                
                return $this->minutesToTime($periodStart) . ' - ' . $this->minutesToTime($periodEnd);
            }
        }
        
        // Fallback to default times
        $defaultTimes = [
            1 => '7:30 - 8:30',
            2 => '8:30 - 9:30', 
            3 => '9:45 - 10:45',
            4 => '10:45 - 11:45',
            5 => '1:00 - 2:00',
            6 => '2:00 - 3:00',
            7 => '3:00 - 4:00',
            8 => '4:00 - 5:00',
        ];
        
        return $defaultTimes[$period] ?? "Period {$period}";
    }

    /**
     * Convert minutes since midnight to HH:MM format.
     */
    private function minutesToTime($minutes)
    {
        $hours = intdiv($minutes, 60);
        $mins = $minutes % 60;
        $period = $hours >= 12 ? 'PM' : 'AM';
        $displayHours = $hours % 12;
        if ($displayHours == 0) {
            $displayHours = 12;
        }
        return sprintf('%d:%02d %s', $displayHours, $mins, $period);
    }

    /**
     * Return JSON data for charts / analytics.
     */
    public function data(Request $request)
    {
        $user = Auth::user();
        if (! $user || $user->role !== 'it_coordinator') {
            abort(403);
        }

        $counts = [
            'teachers' => Teacher::count(),
            'sections' => Section::count(),
            'subjects' => Subject::count(),
        ];

        return response()->json($counts);
    }
}

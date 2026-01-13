<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Teacher;
use App\Models\Subject;
use App\Models\GradeLevel;
use App\Models\ScheduleEntry;

class TeacherController extends Controller
{
    /**
     * Calculate current workload hours for a teacher
     */
    private function calculateWorkload(Teacher $teacher)
    {
        $currentLoad = ScheduleEntry::where('teacher_id', $teacher->id)->sum('hours') ?? 0;
        $maxLoad = $teacher->max_load_per_week ?? 24;
        return [
            'current' => $currentLoad,
            'max' => $maxLoad,
            'percentage' => $maxLoad > 0 ? ($currentLoad / $maxLoad) * 100 : 0
        ];
    }

    /**
     * Determine teacher status based on various factors
     */
    private function getTeacherStatus(Teacher $teacher)
    {
        if ($teacher->status_of_appointment === 'On Leave') {
            return ['status' => 'on_leave', 'label' => 'On Leave', 'color' => 'red'];
        }
        if ($teacher->status_of_appointment === 'Part-time') {
            return ['status' => 'part_time', 'label' => 'Part-time', 'color' => 'yellow'];
        }
        if (!$teacher->subjects()->exists() || !$teacher->gradeLevels()->exists()) {
            return ['status' => 'incomplete', 'label' => 'Incomplete', 'color' => 'orange'];
        }
        if ($teacher->created_at->diffInDays(now()) <= 30) {
            return ['status' => 'new', 'label' => 'New', 'color' => 'blue'];
        }
        return ['status' => 'active', 'label' => 'Active', 'color' => 'green'];
    }

    /**
     * Get validation errors for a teacher
     */
    private function getTeacherValidationIssues(Teacher $teacher)
    {
        $issues = [];
        
        if (empty($teacher->email)) {
            $issues[] = 'Email is missing';
        }
        if (empty($teacher->contact)) {
            $issues[] = 'Phone is missing';
        }
        if (!$teacher->subjects()->exists()) {
            $issues[] = 'No subject expertise assigned';
        }
        if (!$teacher->gradeLevels()->exists()) {
            $issues[] = 'No grade level assigned';
        }
        if (empty($teacher->designation)) {
            $issues[] = 'Designation is required';
        }
        
        return $issues;
    }
    
    public function index(Request $request)
    {
        $q = trim((string)$request->input('q', ''));
        $filterDesignation = $request->input('designation', '');
        $filterSubject = $request->input('subject', '');
        $filterGradeLevel = $request->input('grade_level', '');

        $query = Teacher::with(['subjects','gradeLevels']);
        
        // Text search
        if ($q !== '') {
            $query->where(function($wr) use ($q) {
                $wr->where('name', 'like', "%{$q}%")
                   ->orWhere('staff_id', 'like', "%{$q}%");
            });
        }

        // Filter by designation
        if ($filterDesignation !== '') {
            $query->where('designation', $filterDesignation);
        }

        // Filter by subject
        if ($filterSubject !== '') {
            $query->whereHas('subjects', function($sq) use ($filterSubject) {
                $sq->where('subjects.id', $filterSubject);
            });
        }

        // Filter by grade level
        if ($filterGradeLevel !== '') {
            $query->whereHas('gradeLevels', function($gq) use ($filterGradeLevel) {
                $gq->where('grade_levels.id', $filterGradeLevel);
            });
        }

        $teachers = $query->paginate(68)->withQueryString();
        $subjects = Subject::orderBy('name')->get();
        $gradeLevels = GradeLevel::orderBy('year')->get();
        $designations = config('teachers.designations', []);
        
        return view('admin.teachers.index', compact(
            'teachers',
            'subjects',
            'gradeLevels',
            'designations',
            'q',
            'filterDesignation',
            'filterSubject',
            'filterGradeLevel'
        ));
    }

    public function create()
    {
        $subjects = Subject::orderBy('name')->get();
        $gradeLevels = GradeLevel::orderBy('year')->get();
        $sections = \App\Models\Section::orderBy('name')->get();
        return view('admin.teachers.create', compact('subjects','gradeLevels','sections'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'staff_id' => 'required|string|max:64',
            'name' => 'required|string|max:255',
            'sex' => 'required|string|in:male,female,other',
            'designation' => ['required','string','max:255', Rule::in(config('teachers.designations', []))],
            'status_of_appointment' => ['required','string','max:255', Rule::in(config('teachers.statuses', []))],
            'course_degree' => 'nullable|string|max:255',
            'ancillary_assignments' => 'nullable|string',
            'course_major' => 'nullable|string|max:255',
            'course_minor' => 'nullable|string|max:255',
            'number_handled_per_week' => 'nullable|integer|min:0|max:8',
            'advisory' => 'nullable|string|max:255',
            'email' => 'required|email',
            'contact' => ['required', 'string', 'regex:/^(9\d{9}|09\d{9}|63\d{10}|\+63\d{10})$/'],
            'max_load_per_week' => 'nullable|integer|min:0',
            'max_load_per_day' => 'nullable|integer|min:0',
            'subjects' => 'required|array|min:1',
            'grade_levels' => 'required|array|min:1',
            'availability' => 'nullable|array',
        ]);
        
        // Normalize phone number to +639XXXXXXXXX format
        if (isset($data['contact'])) {
            $phone = $data['contact'];
            if (preg_match('/^9\d{9}$/', $phone)) {
                $data['contact'] = '+63' . $phone;
            } elseif (preg_match('/^09\d{9}$/', $phone)) {
                $data['contact'] = '+63' . substr($phone, 1);
            } elseif (preg_match('/^63\d{10}$/', $phone)) {
                $data['contact'] = '+' . $phone;
            }
            // If already +63XXXXXXXXXX, leave as is
        }

        $teacher = Teacher::create($data);

        if (!empty($data['subjects'])) {
            $teacher->subjects()->sync($data['subjects']);
        }
        if (!empty($data['grade_levels'])) {
            $teacher->gradeLevels()->sync($data['grade_levels']);
        }

        // If request is AJAX, return a JSON fragment so the client can keep the form open
        if ($request->ajax() || $request->wantsJson()) {
            $subjects = Subject::orderBy('name')->get();
            $gradeLevels = GradeLevel::orderBy('year')->get();
            $html = view('admin.teachers._form', compact('teacher','subjects','gradeLevels'))->render();
            return response()->json([ 'success' => true, 'teacher' => $teacher, 'html' => $html ]);
        }

        return redirect()->route('admin.teachers.index')->with('success','Teacher created');
    }

    public function edit(Teacher $teacher)
    {
        $subjects = Subject::orderBy('name')->get();
        $gradeLevels = GradeLevel::orderBy('year')->get();
        $sections = \App\Models\Section::orderBy('name')->get();
        return view('admin.teachers.edit', compact('teacher','subjects','gradeLevels','sections'));
    }

    // Return the form partial for create (AJAX master-detail)
    public function fragmentCreate()
    {
        $subjects = Subject::orderBy('name')->get();
        $gradeLevels = GradeLevel::orderBy('year')->get();
        $sections = \App\Models\Section::orderBy('name')->get();
        return view('admin.teachers._form', compact('subjects','gradeLevels','sections'));
    }

    // Return the form partial for edit (AJAX master-detail)
    public function fragmentEdit(Teacher $teacher)
    {
        $subjects = Subject::orderBy('name')->get();
        $gradeLevels = GradeLevel::orderBy('year')->get();
        $sections = \App\Models\Section::orderBy('name')->get();
        return view('admin.teachers._form', compact('teacher','subjects','gradeLevels','sections'));
    }

    /**
     * Return a JSON fragment containing rendered list items for the teachers list.
     * Used by the infinite-scroll loader on the left panel and filtering.
     */
    public function listFragment(Request $request)
    {
        $q = trim((string)$request->input('q', ''));
        $filterDesignation = $request->input('designation', '');
        $filterSubject = $request->input('subject', '');
        $filterGradeLevel = $request->input('grade_level', '');

        $query = Teacher::with(['subjects','gradeLevels']);
        
        // Text search
        if ($q !== '') {
            $query->where(function($wr) use ($q) {
                $wr->where('name', 'like', "%{$q}%")
                   ->orWhere('staff_id', 'like', "%{$q}%");
            });
        }

        // Filter by designation
        if ($filterDesignation !== '') {
            $query->where('designation', $filterDesignation);
        }

        // Filter by subject
        if ($filterSubject !== '') {
            $query->whereHas('subjects', function($sq) use ($filterSubject) {
                $sq->where('subjects.id', $filterSubject);
            });
        }

        // Filter by grade level
        if ($filterGradeLevel !== '') {
            $query->whereHas('gradeLevels', function($gq) use ($filterGradeLevel) {
                $gq->where('grade_levels.id', $filterGradeLevel);
            });
        }

        $teachers = $query->paginate(68)->withQueryString();
        
        // Enrich teachers with workload and status data
        $teachersData = $teachers->map(function($teacher) {
            return [
                'teacher' => $teacher,
                'workload' => $this->calculateWorkload($teacher),
                'status' => $this->getTeacherStatus($teacher),
                'validationIssues' => $this->getTeacherValidationIssues($teacher)
            ];
        });
        
        $html = view('admin.teachers._list', ['teachers' => $teachersData])->render();
        
        return response()->json([
            'html' => $html,
            'next' => $teachers->nextPageUrl()
        ]);
    }

    // Return subjects as JSON (used by client-side fallback when fragment is injected)
    public function subjectsJson()
    {
        $subjects = Subject::orderBy('name')->get(['id','name','short_code']);
        return response()->json($subjects);
    }

    public function update(Request $request, Teacher $teacher)
    {
        $data = $request->validate([
            'staff_id' => 'required|string|max:64',
            'name' => 'required|string|max:255',
            'sex' => 'required|string|in:male,female,other',
            'designation' => ['required','string','max:255', Rule::in(config('teachers.designations', []))],
            'status_of_appointment' => ['required','string','max:255', Rule::in(config('teachers.statuses', []))],
            'course_degree' => 'nullable|string|max:255',
            'ancillary_assignments' => 'nullable|string',
            'course_major' => 'nullable|string|max:255',
            'course_minor' => 'nullable|string|max:255',
            'number_handled_per_week' => 'nullable|integer|min:0|max:8',
            'advisory' => 'nullable|string|max:255',
            'email' => 'required|email',
            'contact' => ['required', 'string', 'regex:/^(9\d{9}|09\d{9}|63\d{10}|\+63\d{10})$/'],
            'max_load_per_week' => 'nullable|integer|min:0',
            'max_load_per_day' => 'nullable|integer|min:0',
            'subjects' => 'required|array|min:1',
            'grade_levels' => 'required|array|min:1',
            'availability' => 'nullable|array',
        ]);
        
        // Normalize phone number to +639XXXXXXXXX format
        if (isset($data['contact'])) {
            $phone = $data['contact'];
            if (preg_match('/^9\d{9}$/', $phone)) {
                $data['contact'] = '+63' . $phone;
            } elseif (preg_match('/^09\d{9}$/', $phone)) {
                $data['contact'] = '+63' . substr($phone, 1);
            } elseif (preg_match('/^63\d{10}$/', $phone)) {
                $data['contact'] = '+' . $phone;
            }
            // If already +63XXXXXXXXXX, leave as is
        }

        $teacher->update($data);
        $teacher->subjects()->sync($data['subjects'] ?? []);
        $teacher->gradeLevels()->sync($data['grade_levels'] ?? []);

        if ($request->ajax() || $request->wantsJson()) {
            $subjects = Subject::orderBy('name')->get();
            $gradeLevels = GradeLevel::orderBy('year')->get();
            $html = view('admin.teachers._form', compact('teacher','subjects','gradeLevels'))->render();
            return response()->json([ 'success' => true, 'teacher' => $teacher, 'html' => $html ]);
        }

        return redirect()->route('admin.teachers.index')->with('success','Teacher updated');
    }

    public function destroy(Teacher $teacher)
    {
        $teacher->delete();
        if (request()->wantsJson() || request()->ajax()) {
            return response()->json(['success' => true]);
        }
        return redirect()->route('admin.teachers.index')->with('success','Teacher deleted');
    }

    public function export(Request $request)
    {
        $filterDesignation = $request->input('designation', '');
        $filterSubject = $request->input('subject', '');
        $filterGradeLevel = $request->input('grade_level', '');

        $query = Teacher::with(['subjects','gradeLevels']);

        // Apply same filters as index
        if ($filterDesignation !== '') {
            $query->where('designation', $filterDesignation);
        }
        if ($filterSubject !== '') {
            $query->whereHas('subjects', function($sq) use ($filterSubject) {
                $sq->where('subjects.id', $filterSubject);
            });
        }
        if ($filterGradeLevel !== '') {
            $query->whereHas('gradeLevels', function($gq) use ($filterGradeLevel) {
                $gq->where('grade_levels.id', $filterGradeLevel);
            });
        }

        $teachers = $query->get();

        $filename = 'teachers_export_' . date('Y-m-d_His') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($teachers) {
            $file = fopen('php://output', 'w');
            
            // CSV headers - matching all form fields
            fputcsv($file, [
                'Staff ID',
                'Name',
                'Sex',
                'Email',
                'Phone',
                'Designation',
                'Status of Appointment',
                'Course Degree',
                'Course Major',
                'Course Minor',
                'Ancillary Assignments',
                'Unavailable Periods',
                'Subjects',
                'Grade Levels',
                'Max Load Per Week',
                'Max Load Per Day',
                'Notes'
            ]);
            
            foreach ($teachers as $teacher) {
                $subjects = $teacher->subjects->pluck('name')->implode(';');
                $gradeLevels = $teacher->gradeLevels->pluck('name')->implode(';');
                $unavailablePeriods = is_array($teacher->availability) 
                    ? implode(';', $teacher->availability) 
                    : $teacher->availability;
                
                fputcsv($file, [
                    $teacher->staff_id,
                    $teacher->name,
                    $teacher->sex ?? '',
                    $teacher->email ?? '',
                    $teacher->contact ?? '',
                    $teacher->designation,
                    $teacher->status_of_appointment ?? '',
                    $teacher->course_degree ?? '',
                    $teacher->course_major ?? '',
                    $teacher->course_minor ?? '',
                    $teacher->ancillary_assignments ?? '',
                    $unavailablePeriods ?? '',
                    $subjects,
                    $gradeLevels,
                    $teacher->max_load_per_week ?? '',
                    $teacher->max_load_per_day ?? '',
                    $teacher->notes ?? ''
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function downloadTemplate()
    {
        $filename = 'teachers_import_template.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() {
            $file = fopen('php://output', 'w');
            
            // CSV headers
            fputcsv($file, [
                'Staff ID',
                'Name',
                'Sex',
                'Email',
                'Phone',
                'Designation',
                'Status of Appointment',
                'Course Degree',
                'Course Major',
                'Course Minor',
                'Ancillary Assignments',
                'Unavailable Periods',
                'Subjects',
                'Grade Levels',
                'Max Load Per Week',
                'Max Load Per Day',
                'Notes'
            ]);
            
            // Example row
            fputcsv($file, [
                'T12345',
                'John Doe',
                'male',
                'john@example.com',
                '09123456789',
                'Teacher I',
                'Permanent',
                'Bachelor of Science',
                'Mathematics',
                'Statistics',
                'Class Sponsor',
                '1;9',
                'English;Mathematics',
                'Grade 7;Grade 8',
                '24',
                '6',
                'No restrictions'
            ]);
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function import(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:2048',
        ]);

        $file = $request->file('csv_file');
        $path = $file->getRealPath();
        
        $handle = fopen($path, 'r');
        
        // Skip header row
        $header = fgetcsv($handle);
        
        $imported = 0;
        $errors = [];
        $row = 1;

        while (($data = fgetcsv($handle)) !== false) {
            $row++;
            
            if (count($data) < 6) {
                $errors[] = "Row {$row}: Insufficient columns";
                continue;
            }

            $staffId = trim($data[0] ?? '');
            $name = trim($data[1] ?? '');
            $sex = trim($data[2] ?? null) ?: null;
            $email = trim($data[3] ?? null) ?: null;
            $phone = trim($data[4] ?? null) ?: null;
            $designation = trim($data[5] ?? '');
            $statusOfAppointment = trim($data[6] ?? null) ?: null;
            $courseDegree = trim($data[7] ?? null) ?: null;
            $courseMajor = trim($data[8] ?? null) ?: null;
            $courseMinor = trim($data[9] ?? null) ?: null;
            $ancillaryAssignments = trim($data[10] ?? null) ?: null;
            $unavailablePeriods = trim($data[11] ?? null) ?: null;
            $subjectsStr = trim($data[12] ?? '');
            $gradeLevelsStr = trim($data[13] ?? '');
            $maxLoadPerWeek = trim($data[14] ?? null) ?: null;
            $maxLoadPerDay = trim($data[15] ?? null) ?: null;
            $notes = trim($data[16] ?? null) ?: null;

            if (empty($staffId) || empty($name) || empty($designation)) {
                $errors[] = "Row {$row}: Missing required fields (Staff ID, Name, or Designation)";
                continue;
            }

            try {
                // Parse availability periods
                $availability = [];
                if (!empty($unavailablePeriods)) {
                    $availability = array_map('trim', explode(';', $unavailablePeriods));
                }

                // Create or update teacher
                $teacher = Teacher::updateOrCreate(
                    ['staff_id' => $staffId],
                    [
                        'name' => $name,
                        'sex' => $sex,
                        'email' => $email,
                        'contact' => $phone,
                        'designation' => $designation,
                        'status_of_appointment' => $statusOfAppointment,
                        'course_degree' => $courseDegree,
                        'course_major' => $courseMajor,
                        'course_minor' => $courseMinor,
                        'ancillary_assignments' => $ancillaryAssignments,
                        'availability' => !empty($availability) ? $availability : null,
                        'max_load_per_week' => $maxLoadPerWeek,
                        'max_load_per_day' => $maxLoadPerDay,
                        'notes' => $notes
                    ]
                );

                // Sync subjects
                if (!empty($subjectsStr)) {
                    $subjectNames = array_map('trim', explode(';', $subjectsStr));
                    $subjectIds = Subject::whereIn('name', $subjectNames)->pluck('id')->toArray();
                    $teacher->subjects()->sync($subjectIds);
                }

                // Sync grade levels
                if (!empty($gradeLevelsStr)) {
                    $gradeLevelNames = array_map('trim', explode(';', $gradeLevelsStr));
                    $gradeLevelIds = GradeLevel::whereIn('name', $gradeLevelNames)->pluck('id')->toArray();
                    $teacher->gradeLevels()->sync($gradeLevelIds);
                }

                $imported++;
            } catch (\Exception $e) {
                $errors[] = "Row {$row}: " . $e->getMessage();
            }
        }

        fclose($handle);

        $message = "Imported {$imported} teachers.";
        if (!empty($errors)) {
            $message .= " Errors: " . implode('; ', array_slice($errors, 0, 5));
            if (count($errors) > 5) {
                $message .= " (and " . (count($errors) - 5) . " more...)";
            }
        }

        return redirect()->route('admin.teachers.index')->with('success', $message);
    }
}

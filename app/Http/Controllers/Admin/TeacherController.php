<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Teacher;
use App\Models\Subject;
use App\Models\GradeLevel;

class TeacherController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string)$request->input('q', ''));

        $query = Teacher::with(['subjects','gradeLevels']);
        if ($q !== '') {
            $query->where(function($wr) use ($q) {
                $wr->where('name', 'like', "%{$q}%")
                   ->orWhere('staff_id', 'like', "%{$q}%");
            });
        }

        $teachers = $query->paginate(10)->withQueryString();
        $subjects = Subject::orderBy('name')->get();
        $gradeLevels = GradeLevel::orderBy('name')->get();
        return view('admin.it.teachers.index', compact('teachers','subjects','gradeLevels','q'));
    }

    public function create()
    {
        $subjects = Subject::orderBy('name')->get();
        $gradeLevels = GradeLevel::orderBy('name')->get();
        return view('admin.it.teachers.create', compact('subjects','gradeLevels'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'staff_id' => 'nullable|string|max:64',
            'name' => 'required|string|max:255',
            'sex' => 'nullable|string|in:male,female,other',
            'designation' => 'nullable|string|max:255',
            'status_of_appointment' => 'nullable|string|max:255',
            'course_degree' => 'nullable|string|max:255',
            'ancillary_assignments' => 'nullable|string',
            'course_major' => 'nullable|string|max:255',
            'course_minor' => 'nullable|string|max:255',
            'number_handled_per_week' => 'nullable|integer|min:0|max:8',
            'advisory' => 'nullable|string|max:255',
            'email' => 'nullable|email',
            'contact' => 'nullable|string|max:64',
            'max_load_per_week' => 'nullable|integer|min:0',
            'max_load_per_day' => 'nullable|integer|min:0',
            'subjects' => 'nullable|array',
            'grade_levels' => 'nullable|array',
            'availability' => 'nullable|array',
        ]);

        $teacher = Teacher::create($data);

        if (!empty($data['subjects'])) {
            $teacher->subjects()->sync($data['subjects']);
        }
        if (!empty($data['grade_levels'])) {
            $teacher->gradeLevels()->sync($data['grade_levels']);
        }

        return redirect()->route('admin.it.teachers.index')->with('success','Teacher created');
    }

    public function edit(Teacher $teacher)
    {
        $subjects = Subject::orderBy('name')->get();
        $gradeLevels = GradeLevel::orderBy('name')->get();
        return view('admin.it.teachers.edit', compact('teacher','subjects','gradeLevels'));
    }

    // Return the form partial for create (AJAX master-detail)
    public function fragmentCreate()
    {
        $subjects = Subject::orderBy('name')->get();
        $gradeLevels = GradeLevel::orderBy('name')->get();
        return view('admin.it.teachers._form', compact('subjects','gradeLevels'));
    }

    // Return the form partial for edit (AJAX master-detail)
    public function fragmentEdit(Teacher $teacher)
    {
        $subjects = Subject::orderBy('name')->get();
        $gradeLevels = GradeLevel::orderBy('name')->get();
        return view('admin.it.teachers._form', compact('teacher','subjects','gradeLevels'));
    }

    /**
     * Return a JSON fragment containing rendered list items for the teachers list.
     * Used by the infinite-scroll loader on the left panel.
     */
    public function listFragment(Request $request)
    {
        $q = trim((string)$request->input('q', ''));
        $query = Teacher::with(['subjects','gradeLevels']);
        if ($q !== '') {
            $query->where(function($wr) use ($q) {
                $wr->where('name', 'like', "%{$q}%")
                   ->orWhere('staff_id', 'like', "%{$q}%");
            });
        }

        $teachers = $query->paginate(10)->withQueryString();
        $html = view('admin.it.teachers._list', compact('teachers'))->render();
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
            'staff_id' => 'nullable|string|max:64',
            'name' => 'required|string|max:255',
            'sex' => 'nullable|string|in:male,female,other',
            'designation' => 'nullable|string|max:255',
            'status_of_appointment' => 'nullable|string|max:255',
            'course_degree' => 'nullable|string|max:255',
            'ancillary_assignments' => 'nullable|string',
            'course_major' => 'nullable|string|max:255',
            'course_minor' => 'nullable|string|max:255',
            'number_handled_per_week' => 'nullable|integer|min:0|max:8',
            'advisory' => 'nullable|string|max:255',
            'email' => 'nullable|email',
            'contact' => 'nullable|string|max:64',
            'max_load_per_week' => 'nullable|integer|min:0',
            'max_load_per_day' => 'nullable|integer|min:0',
            'subjects' => 'nullable|array',
            'grade_levels' => 'nullable|array',
            'availability' => 'nullable|array',
        ]);

        $teacher->update($data);
        $teacher->subjects()->sync($data['subjects'] ?? []);
        $teacher->gradeLevels()->sync($data['grade_levels'] ?? []);

        return redirect()->route('admin.it.teachers.index')->with('success','Teacher updated');
    }

    public function destroy(Teacher $teacher)
    {
        $teacher->delete();
        return redirect()->route('admin.it.teachers.index')->with('success','Teacher deleted');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Teacher;
use App\Models\Subject;
use App\Models\GradeLevel;

class TeacherController extends Controller
{
    public function index()
    {
        $teachers = Teacher::with(['subjects','gradeLevels'])->paginate(10);
        return view('admin.it.teachers.index', compact('teachers'));
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

    public function update(Request $request, Teacher $teacher)
    {
        $data = $request->validate([
            'staff_id' => 'nullable|string|max:64',
            'name' => 'required|string|max:255',
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

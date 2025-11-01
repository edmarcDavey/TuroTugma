<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Subject;
use App\Models\GradeLevel;

use Illuminate\Http\JsonResponse;

class SubjectController extends Controller
{
    public function index()
    {
        $subjects = Subject::with('gradeLevels')->orderBy('name')->paginate(20);
        return view('admin.it.subjects.index', compact('subjects'));
    }

    public function create()
    {
        $gradeLevels = GradeLevel::orderBy('name')->get();
        return view('admin.it.subjects.create', compact('gradeLevels'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'code' => 'nullable|string|max:32',
            'name' => 'required|string|max:255',
            'type' => 'nullable|string|max:64',
            'grade_levels' => 'nullable|array',
            'description' => 'nullable|string',
        ]);

        $subject = Subject::create($data);
        if (!empty($data['grade_levels'])) {
            $subject->gradeLevels()->sync($data['grade_levels']);
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'subject' => $subject], 201);
        }

        return redirect()->route('admin.it.subjects.index')->with('success','Subject created');
    }

    public function edit(Subject $subject)
    {
        $gradeLevels = GradeLevel::orderBy('name')->get();
        return view('admin.it.subjects.edit', compact('subject','gradeLevels'));
    }

    public function update(Request $request, Subject $subject)
    {
        $data = $request->validate([
            'code' => 'nullable|string|max:32',
            'name' => 'required|string|max:255',
            'type' => 'nullable|string|max:64',
            'grade_levels' => 'nullable|array',
            'description' => 'nullable|string',
        ]);

        $subject->update($data);
        $subject->gradeLevels()->sync($data['grade_levels'] ?? []);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'subject' => $subject], 200);
        }

        return redirect()->route('admin.it.subjects.index')->with('success','Subject updated');
    }

    public function destroy(Subject $subject)
    {
        $subject->delete();
        return redirect()->route('admin.it.subjects.index')->with('success','Subject deleted');
    }

    // fragment endpoints for master-detail
    public function fragmentCreate()
    {
        $gradeLevels = GradeLevel::orderBy('name')->get();
        return view('admin.it.subjects._form', compact('gradeLevels'));
    }

    public function fragmentEdit(Subject $subject)
    {
        $gradeLevels = GradeLevel::orderBy('name')->get();
        return view('admin.it.subjects._form', compact('subject','gradeLevels'));
    }

    /**
     * Toggle assignment of this subject to a grade level (AJAX).
     * If assigned -> detach; otherwise attach.
     */
    public function toggleGrade(Request $request, $subjectId, $gradeLevelId)
    {
        // simple role guard
        if (!\Illuminate\Support\Facades\Auth::check() || \Illuminate\Support\Facades\Auth::user()->role !== 'it_coordinator') {
            abort(403);
        }

        $subject = Subject::findOrFail($subjectId);
        $grade = GradeLevel::findOrFail($gradeLevelId);

        $attached = $subject->gradeLevels()->where('grade_level_id', $grade->id)->exists();
        if ($attached) {
            $subject->gradeLevels()->detach($grade->id);
            $attached = false;
        } else {
            $subject->gradeLevels()->attach($grade->id);
            $attached = true;
        }

        $current = $subject->gradeLevels()->pluck('id')->toArray();

        return response()->json(['success' => true, 'attached' => $attached, 'subject_id' => $subject->id, 'grade_level_id' => $grade->id, 'grade_levels' => $current]);
    }
}

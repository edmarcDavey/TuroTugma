<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Subject;
use App\Models\GradeLevel;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\JsonResponse;

class SubjectController extends Controller
{
    public function index()
    {
        $subjects = Subject::with('gradeLevels')->orderBy('name')->paginate(20);
        return view('admin.subjects.index', compact('subjects'));
    }

    public function create()
    {
        $gradeLevels = GradeLevel::orderBy('name')->get();
        return view('admin.subjects.create', compact('gradeLevels'));
    }

    public function show(Subject $subject)
    {
        $subject->load('gradeLevels', 'strand');
        return response()->json([
            'subject' => [
                'id' => $subject->id,
                'name' => $subject->name,
                'code' => $subject->code,
                'type' => $subject->type,
                'strand_id' => $subject->strand_id,
                'hours_per_week' => $subject->hours_per_week,
                'grade_levels' => $subject->gradeLevels->pluck('id')->toArray(),
            ]
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'code' => 'nullable|string|max:32',
            'name' => 'required|string|max:255',
            'type' => 'nullable|string|max:64',
            'strand_id' => 'nullable|exists:strands,id',
            'hours_per_week' => 'nullable|integer|min:1|max:20',
            'grade_levels' => 'nullable|array',
            'description' => 'nullable|string',
        ]);

        $subject = Subject::create([
            'code' => $data['code'] ?? null,
            'name' => $data['name'],
            'type' => $data['type'] ?? null,
            'strand_id' => $data['strand_id'] ?? null,
            'hours_per_week' => $data['hours_per_week'] ?? null,
        ]);

        if (!empty($data['grade_levels'])) {
            $subject->gradeLevels()->sync($data['grade_levels']);
        }

        return response()->json(['success' => true, 'subject' => $subject->load('gradeLevels', 'strand')]);
    }

    public function edit(Subject $subject)
    {
        $gradeLevels = GradeLevel::orderBy('name')->get();
        return view('admin.subjects.edit', compact('subject','gradeLevels'));
    }

    public function update(Request $request, Subject $subject)
    {
        $data = $request->validate([
            'code' => 'nullable|string|max:32',
            'name' => 'required|string|max:255',
            'type' => 'nullable|string|max:64',
            'strand_id' => 'nullable|exists:strands,id',
            'hours_per_week' => 'nullable|integer|min:1|max:20',
            'grade_levels' => 'nullable|array',
            'description' => 'nullable|string',
        ]);

        $subject->update([
            'code' => $data['code'] ?? null,
            'name' => $data['name'],
            'type' => $data['type'] ?? null,
            'strand_id' => $data['strand_id'] ?? null,
            'hours_per_week' => $data['hours_per_week'] ?? null,
        ]);

        if (isset($data['grade_levels'])) {
            $subject->gradeLevels()->sync($data['grade_levels']);
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'subject' => $subject->load('gradeLevels', 'strand')]);
        }

        return redirect()->route('admin.subjects.index')->with('success','Subject updated');
    }

    public function destroy(Request $request, Subject $subject)
    {
        $subject->delete();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Subject deleted']);
        }

        return redirect()->route('admin.subjects.index')->with('success','Subject deleted');
    }

    // fragment endpoints for master-detail
    public function fragmentCreate()
    {
        $gradeLevels = GradeLevel::orderBy('name')->get();
        return view('admin.subjects._form', compact('gradeLevels'));
    }

    public function fragmentEdit(Subject $subject)
    {
        $gradeLevels = GradeLevel::orderBy('name')->get();
        return view('admin.subjects._form', compact('subject','gradeLevels'));
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

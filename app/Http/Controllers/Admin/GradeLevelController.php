<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\GradeLevel;
use App\Models\Section;


class GradeLevelController extends Controller
{
    /**
     * Return JSON preview of generated section names for a grade level.
     */
    public function previewSections(Request $request, GradeLevel $gradeLevel)
    {
        $this->authorizeAdmin();

        $data = $request->validate([
            'count' => 'required|integer|min:0|max:200',
            'theme' => 'nullable|string',
        ]);

        $count = intval($data['count']);
        // if no theme provided, pass empty string so naming service can treat it as 'auto' and pick random
        $result = $gradeLevel->generateSectionNames($count, ['theme' => $data['theme'] ?? '', 'return_meta' => true]);

        if (is_array($result) && isset($result['names']) && isset($result['theme_key'])) {
            return response()->json(['preview' => $result['names'], 'theme' => $result['theme_key'], 'theme_label' => ($result['theme_label'] ?? null)]);
        }

        return response()->json(['preview' => $result]);
    }

    public function index()
    {
        $this->authorizeAdmin();
        $gradeLevels = GradeLevel::with('sections')->withCount('sections')->orderBy('seq')->orderBy('name')->get();
        // prepare a lightweight payload for client-side JS
        $gradePayload = $gradeLevels->map(function($g){
            return [
                'id' => $g->id,
                'name' => $g->name,
                'section_naming' => $g->section_naming,
                'sections' => $g->sections->map(function($s){ return ['id'=>$s->id,'name'=>$s->name]; })->toArray(),
            ];
        })->toArray();

        return view('admin.it.grade-levels.index', compact('gradeLevels','gradePayload'));
    }

    public function store(Request $request)
    {
        $this->authorizeAdmin();
        $data = $request->validate([
            'name' => 'required|string|max:191',
            //'year' => 'nullable|string|max:20',
            'section_naming' => 'nullable|string',
            'section_naming_options' => 'nullable|array',
        ]);

        $grade = GradeLevel::create([
            'name' => $data['name'],
            'section_naming' => $data['section_naming'] ?? null,
            'section_naming_options' => $data['section_naming_options'] ?? null,
        ]);

        // If AJAX/JSON requested, return JSON with created grade
        if ($request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            return response()->json(['grade' => $grade]);
        }

        return redirect()->route('admin.it.grade-levels.index')->with('success', 'Grade level created');
    }

    public function fragmentCreate()
    {
        $this->authorizeAdmin();
        $gradeLevel = null;
        $isEdit = false;
        $themes = array_keys(config('section_themes.themes', []));
        return view('admin.it.grade-levels._form', compact('gradeLevel','isEdit','themes'));
    }

    public function fragmentEdit(GradeLevel $gradeLevel)
    {
        $this->authorizeAdmin();
        $isEdit = true;
        $themes = array_keys(config('section_themes.themes', []));
        return view('admin.it.grade-levels._form', compact('gradeLevel','isEdit','themes'));
    }

    /**
     * Update grade level fields from combined UI (AJAX or form).
     */
    public function update(Request $request, GradeLevel $gradeLevel)
    {
        $this->authorizeAdmin();

        $data = $request->validate([
            'name' => 'nullable|string|max:191',
            'section_naming' => 'nullable|string',
            'section_naming_options' => 'nullable|array',
            'school_stage' => 'nullable|string|max:32',
        ]);

        $gradeLevel->update([
            'name' => $data['name'] ?? $gradeLevel->name,
            'section_naming' => $data['section_naming'] ?? $gradeLevel->section_naming,
            'section_naming_options' => $data['section_naming_options'] ?? $gradeLevel->section_naming_options,
            'school_stage' => $data['school_stage'] ?? $gradeLevel->school_stage,
        ]);

        if ($request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            return response()->json(['success' => true, 'grade' => $gradeLevel]);
        }

        return redirect()->route('admin.it.grade-levels.index')->with('success','Grade level updated');
    }

    


    /**
     * Preview without an existing grade level: accepts theme and count and returns generated names.
     */
    public function previewAnonymous(Request $request)
    {
        $this->authorizeAdmin();

        $data = $request->validate([
            'theme' => 'nullable|string',
            'count' => 'required|integer|min:0|max:200',
        ]);

        // create a temporary GradeLevel-like object with just a name (used for label formatting)
        $gl = new GradeLevel();
        $gl->name = $request->input('name', '');
        // set to empty string when theme not provided so the naming service may auto-pick a random theme
        $gl->section_naming = $data['theme'] ?? '';

        $result = app(\App\Services\SectionNamingService::class)->generate($gl, intval($data['count']), ['return_meta' => true]);

        if (is_array($result) && isset($result['names']) && isset($result['theme_key'])) {
            return response()->json(['preview' => $result['names'], 'theme' => $result['theme_key'], 'theme_label' => ($result['theme_label'] ?? null)]);
        }

        return response()->json(['preview' => $result]);
    }

    protected function authorizeAdmin()
    {
        // simple role guard consistent with existing admin routes
        if (!Auth::check() || Auth::user()->role !== 'it_coordinator') {
            abort(403);
        }
    }
}

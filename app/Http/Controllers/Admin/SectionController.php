<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\GradeLevel;
use App\Models\Section;
use Illuminate\Http\Response;

class SectionController extends Controller
{
    protected function authorizeAdmin()
    {
        if (!\Illuminate\Support\Facades\Auth::check() || \Illuminate\Support\Facades\Auth::user()->role !== 'it_coordinator') abort(403);
    }

    /**
     * Bulk create sections for a grade level using the naming service via GradeLevel::generateSectionNames
     */
    public function bulkStore(Request $request, GradeLevel $gradeLevel)
    {
        $this->authorizeAdmin();

        $data = $request->validate([
            'count' => 'nullable|integer|min:0|max:200',
            'mode' => 'nullable|in:append,replace',
            'names' => 'nullable|array',
            'theme' => 'nullable|string|max:191',
            'section_naming_options' => 'nullable|array',
            'items' => 'nullable|array',
            'items.*.name' => 'required_with:items|string|max:191',
            'items.*.is_special' => 'nullable|boolean',
            'items.*.track' => 'nullable|string|max:50',
        ]);

        $mode = $data['mode'] ?? 'append';

        // If names array provided, use it as authoritative list. Otherwise generate by count.
        if (!empty($data['items'])) {
            // items contain objects with name and optional is_special and optional track
            $names = array_map(function($it, $i){
                return [
                    'ordinal' => $i+1,
                    'name' => trim($it['name']),
                    'is_special' => !empty($it['is_special']) ? 1 : 0,
                    'track' => isset($it['track']) ? trim($it['track']) : null,
                ];
            }, array_values($data['items']), array_keys($data['items']));
        } elseif (!empty($data['names'])) {
            $names = array_map(function($n, $i){ return ['ordinal' => $i+1, 'name' => trim($n), 'is_special' => 0]; }, array_values($data['names']), array_keys($data['names']));
        } else {
            $count = intval($data['count'] ?? 0);
            // Pass theme/options through to generation so inline theme works without persisting
            $options = [];
            if (!empty($data['theme'])) $options['theme'] = $data['theme'];
            if (!empty($data['section_naming_options'])) $options['section_naming_options'] = $data['section_naming_options'];
            $names = $gradeLevel->generateSectionNames($count, $options);
        }

        DB::transaction(function() use ($gradeLevel, $names, $mode) {
            if ($mode === 'replace') {
                Section::where('grade_level_id', $gradeLevel->id)->delete();
            }

            foreach ($names as $n) {
                $name = $n['name'];
                $ordinal = $n['ordinal'] ?? null;
                $track = $n['track'] ?? null;

                // skip if exists
                $exists = Section::where('grade_level_id', $gradeLevel->id)->where('name', $name)->exists();
                if ($exists) continue;

                Section::create([
                    'grade_level_id' => $gradeLevel->id,
                    'name' => $name,
                    'ordinal' => $ordinal,
                    'code' => null,
                    'is_special' => isset($n['is_special']) ? (bool)$n['is_special'] : false,
                    'track' => $track,
                ]);
            }
        });

        $sections = Section::where('grade_level_id', $gradeLevel->id)->orderBy('ordinal')->get();
        return response()->json(['sections' => $sections]);
    }

    /**
     * Return sections for a grade as JSON (for the editor UI)
     */
    public function listForGrade(Request $request, GradeLevel $gradeLevel)
    {
        $this->authorizeAdmin();
        // For Junior High we prefer to surface special sections first, then by ordinal.
        $sections = Section::where('grade_level_id', $gradeLevel->id)
            ->orderByDesc('is_special')
            ->orderBy('ordinal')
            ->get(['id','name','ordinal','is_special','track']);
        return response()->json(['sections' => $sections]);
    }

    /**
     * Create a single section for a grade (AJAX)
     */
    public function store(Request $request, GradeLevel $gradeLevel)
    {
        $this->authorizeAdmin();

        $data = $request->validate([
            'name' => 'required|string|max:191',
            'is_special' => 'nullable|boolean',
            'track' => 'nullable|string|max:50',
            'ordinal' => 'nullable|integer',
        ]);

        $section = Section::create([
            'grade_level_id' => $gradeLevel->id,
            'name' => $data['name'],
            'ordinal' => $data['ordinal'] ?? null,
            'is_special' => !empty($data['is_special']) ? 1 : 0,
            'track' => $data['track'] ?? null,
        ]);

        return response()->json(['section' => $section], Response::HTTP_CREATED);
    }

    /**
     * Update a section
     */
    public function update(Request $request, Section $section)
    {
        $this->authorizeAdmin();

        $data = $request->validate([
            'name' => 'required|string|max:191',
            'is_special' => 'nullable|boolean',
            'track' => 'nullable|string|max:50',
            'ordinal' => 'nullable|integer',
        ]);

        $section->update([
            'name' => $data['name'],
            'is_special' => !empty($data['is_special']) ? 1 : 0,
            'track' => $data['track'] ?? null,
            'ordinal' => $data['ordinal'] ?? $section->ordinal,
        ]);

        return response()->json(['section' => $section]);
    }

    /**
     * Delete a section
     */
    public function destroy(Section $section)
    {
        $this->authorizeAdmin();
        $section->delete();
        return response()->json(['success' => true]);
    }
}

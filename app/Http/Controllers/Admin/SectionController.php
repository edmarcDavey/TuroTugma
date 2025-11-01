<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\GradeLevel;
use App\Models\Section;

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
            'names.*' => 'required|string|max:191',
            'theme' => 'nullable|string|max:191',
            'section_naming_options' => 'nullable|array',
        ]);

        $mode = $data['mode'] ?? 'append';

        // If names array provided, use it as authoritative list. Otherwise generate by count.
        if (!empty($data['names'])) {
            $names = array_map(function($n, $i){ return ['ordinal' => $i+1, 'name' => trim($n)]; }, array_values($data['names']), array_keys($data['names']));
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

                // skip if exists
                $exists = Section::where('grade_level_id', $gradeLevel->id)->where('name', $name)->exists();
                if ($exists) continue;

                Section::create([
                    'grade_level_id' => $gradeLevel->id,
                    'name' => $name,
                    'ordinal' => $ordinal,
                    'code' => null,
                ]);
            }
        });

        $sections = Section::where('grade_level_id', $gradeLevel->id)->orderBy('ordinal')->get();
        return response()->json(['sections' => $sections]);
    }
}

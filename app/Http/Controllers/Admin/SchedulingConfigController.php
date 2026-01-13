<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SchedulingConfig;
use App\Models\DayConfig;
use App\Models\PeriodRestriction;
use App\Models\Subject;
use App\Models\User;
use App\Models\ScheduleSection;

class SchedulingConfigController extends Controller
{
    public function index()
    {
        $jh = SchedulingConfig::with('dayConfigs')->where('level','junior_high')->first();
        $sh = SchedulingConfig::with('dayConfigs')->where('level','senior_high')->first();
        $general = SchedulingConfig::with('periodRestrictions.subject')->where('level','general')->first();
        $subjects = Subject::orderBy('name')->get();
        $teachers = User::where('role', 'teacher')->orderBy('name')->get();
        $sections_jhs = ScheduleSection::where('level', 'junior_high')->orderBy('name')->get();
        $sections_shs = ScheduleSection::where('level', 'senior_high')->orderBy('name')->get();
        return view('admin.schedule-maker.settings', compact('jh','sh','general','subjects','teachers','sections_jhs','sections_shs'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'level' => 'required|in:junior_high,senior_high,general',
            'max_subjects_per_day' => 'nullable|integer|min:1|max:12',
            'max_periods_per_week' => 'nullable|integer|min:1|max:60',
            'weekly_load_limit' => 'nullable|integer|min:1|max:60',
            'is_locked' => 'boolean',
            'unit_distribution_rules' => 'nullable|array',
            'days' => 'nullable|array',
            'restrictions' => 'nullable|array',
            'restrictions.*.role' => 'required|string',
            'restrictions.*.periods' => 'nullable|array',
            'constraint_type' => 'nullable|string|in:subject,faculty',
            'subject_constraints' => 'nullable|array',
            'subject_constraints.*.subject_id' => 'required|integer|exists:subjects,id',
            'subject_constraints.*.periods' => 'nullable|array',
            'load_config' => 'nullable|array',
            'load_config.min_subjects' => 'nullable|integer|min:1|max:8',
            'load_config.max_subjects' => 'nullable|integer|min:1|max:8',
            'load_config.min_sections' => 'nullable|integer|min:1|max:15',
            'load_config.max_sections' => 'nullable|integer|min:1|max:15',
            'load_config.min_hours' => 'nullable|integer|min:1|max:60',
            'load_config.max_hours' => 'nullable|integer|min:1|max:60',
            'load_config.allow_same_subject_gap' => 'nullable|boolean',
            'load_config.prefer_consecutive_periods' => 'nullable|boolean',
                'optimization_config' => 'nullable|array',
                'optimization_config.max_consecutive_periods' => 'nullable|integer|min:1|max:12',
                'optimization_config.max_teaching_days_per_week' => 'nullable|integer|min:1|max:7',
                'optimization_config.load_distribution_threshold' => 'nullable|numeric|min:0.5|max:10',
                'optimization_config.senior_junior_ratio' => 'nullable|in:equal,favor_senior,favor_junior',
                'optimization_config.minimize_gaps' => 'nullable|boolean',
                'optimization_config.enable_load_balancing' => 'nullable|boolean',
                'optimization_config.respect_subject_affinity' => 'nullable|boolean',
        ]);

        // Handle general level for faculty restrictions and subject constraints
        if ($data['level'] === 'general') {
            $config = SchedulingConfig::firstOrCreate(['level' => 'general']);
            
            // Faculty restrictions
            if (isset($data['restrictions']) && !isset($data['constraint_type'])) {
                $config->update([
                    'faculty_restrictions' => $data['restrictions']
                ]);
                return redirect()->route('admin.schedule-maker.settings')->with('status','Faculty restrictions saved.');
            }
            
            // Subject constraints - save to PeriodRestriction table
            if (isset($data['subject_constraints']) && isset($data['constraint_type']) && $data['constraint_type'] === 'subject') {
                // Clear existing subject restrictions for this config
                PeriodRestriction::where('scheduling_config_id', $config->id)
                    ->where('restriction_type', 'subject')
                    ->delete();
                
                // Add new subject constraints
                foreach ($data['subject_constraints'] as $constraint) {
                    if (!empty($constraint['subject_id']) && !empty($constraint['periods'])) {
                        foreach ($constraint['periods'] as $period) {
                            PeriodRestriction::create([
                                'scheduling_config_id' => $config->id,
                                'restriction_type' => 'subject',
                                'subject_id' => $constraint['subject_id'],
                                'period_number' => $period,
                                'day_of_week' => null,
                                'teacher_ancillary' => null,
                            ]);
                        }
                    }
                }
                
                return redirect()->route('admin.schedule-maker.settings')->with('status','Subject constraints saved.');
            }
            
                // Optimization configuration - save to general config
                if (isset($data['optimization_config'])) {
                    $optimizationSettings = [
                        'minimize_gaps' => $data['optimization_config']['minimize_gaps'] ?? true,
                        'enable_load_balancing' => $data['optimization_config']['enable_load_balancing'] ?? true,
                        'respect_subject_affinity' => $data['optimization_config']['respect_subject_affinity'] ?? true,
                    ];
                
                    $config->update([
                        'max_consecutive_periods' => $data['optimization_config']['max_consecutive_periods'] ?? 3,
                        'max_teaching_days_per_week' => $data['optimization_config']['max_teaching_days_per_week'] ?? 5,
                        'load_distribution_threshold' => $data['optimization_config']['load_distribution_threshold'] ?? 2.0,
                        'senior_junior_ratio' => $data['optimization_config']['senior_junior_ratio'] ?? 'equal',
                        'optimization_settings' => $optimizationSettings,
                    ]);
                
                    return redirect()->route('admin.schedule-maker.settings')->with('status','Optimization rules saved.');
                }
            
            return redirect()->route('admin.schedule-maker.settings')->with('status','Settings saved.');
        }

        // Handle JHS/SHS load configuration
        if (isset($data['load_config']) && in_array($data['level'], ['junior_high', 'senior_high'])) {
            $config = SchedulingConfig::firstOrCreate(['level' => $data['level']]);
            
            $constraintsField = $data['level'] === 'junior_high' ? 'jhs_constraints' : 'shs_constraints';
            
            $constraints = [
                'min_subjects' => $data['load_config']['min_subjects'] ?? 1,
                'max_subjects' => $data['load_config']['max_subjects'] ?? 3,
                'min_sections' => $data['load_config']['min_sections'] ?? 1,
                'max_sections' => $data['load_config']['max_sections'] ?? 4,
                'min_hours' => $data['load_config']['min_hours'] ?? 12,
                'max_hours' => $data['load_config']['max_hours'] ?? 24,
            ];
            
            // Add level-specific preferences
            if ($data['level'] === 'junior_high') {
                $constraints['allow_same_subject_gap'] = $data['load_config']['allow_same_subject_gap'] ?? false;
            } else {
                $constraints['prefer_consecutive_periods'] = $data['load_config']['prefer_consecutive_periods'] ?? false;
            }
            
            $config->update([
                $constraintsField => $constraints
            ]);
            
            return redirect()->route('admin.schedule-maker.settings')->with('status','Load configuration saved.');
        }

        $config = SchedulingConfig::updateOrCreate(
            ['level' => $data['level']],
            [
                'max_subjects_per_day' => $data['max_subjects_per_day'] ?? 8,
                'max_periods_per_week' => $data['max_periods_per_week'] ?? 40,
                'weekly_load_limit' => $data['weekly_load_limit'] ?? null,
                'is_locked' => $data['is_locked'] ?? false,
                'unit_distribution_rules' => $data['unit_distribution_rules'] ?? null,
            ]
        );

        if (!empty($data['days'])) {
            foreach ($data['days'] as $day) {
                DayConfig::updateOrCreate(
                    ['scheduling_config_id' => $config->id, 'day_of_week' => $day['day_of_week']],
                    [
                        'is_active' => (bool)($day['is_active'] ?? true),
                        'session_type' => $day['session_type'] ?? 'regular',
                        'period_count' => (int)($day['period_count'] ?? 8),
                        'start_time' => $day['start_time'] ?? null,
                        'end_time' => $day['end_time'] ?? null,
                        'period_duration' => (int)($day['period_duration'] ?? 50),
                        'breaks' => $day['breaks'] ?? null,
                        'manual_period_times' => $day['manual_period_times'] ?? null,
                    ]
                );
            }
        }

        return redirect()->route('admin.schedule-maker.settings')->with('status','Settings saved.');
    }

    public function restrictions(Request $request)
    {
        $data = $request->validate([
            'level' => 'required|in:junior_high,senior_high',
            'restrictions' => 'required|array',
        ]);

        $config = SchedulingConfig::where('level', $data['level'])->firstOrFail();

        foreach ($data['restrictions'] as $r) {
            PeriodRestriction::updateOrCreate(
                [
                    'scheduling_config_id' => $config->id,
                    'day_of_week' => $r['day_of_week'],
                    'period_number' => $r['period_number'],
                    'restriction_type' => $r['restriction_type'],
                    'subject_id' => $r['subject_id'] ?? null,
                    'teacher_ancillary' => $r['teacher_ancillary'] ?? null,
                ],
                []
            );
        }

        return redirect()->route('admin.schedule-maker.settings')->with('status','Restrictions updated.');
    }

    public function saveQualifications(Request $request)
    {
        $data = $request->validate([
            'qualifications' => 'nullable|array',
            'qualifications.*.teacher_id' => 'required|integer|exists:users,id',
            'qualifications.*.subject_id' => 'required|integer|exists:subjects,id',
            'qualifications.*.level' => 'required|in:junior_high,senior_high,both',
            'qualifications.*.proficiency' => 'required|in:primary_expert,capable,can_assist',
            'qualifications.*.seniority' => 'required|in:senior,mid_level,junior',
        ]);

        // First, get the appropriate model if it exists, or use the first general config
        $config = SchedulingConfig::first();
        if (!$config) {
            $config = SchedulingConfig::create(['level' => 'general']);
        }

        // Clear existing qualifications and create new ones
        if (!empty($data['qualifications'])) {
            foreach ($data['qualifications'] as $qual) {
                \App\Models\TeacherQualification::updateOrCreate(
                    [
                        'teacher_id' => $qual['teacher_id'],
                        'subject_id' => $qual['subject_id'],
                        'level' => $qual['level'],
                    ],
                    [
                        'proficiency' => $qual['proficiency'],
                        'seniority' => $qual['seniority'],
                    ]
                );
            }
        }

        return redirect()->route('admin.schedule-maker.settings')->with('status','Teacher qualifications saved successfully.');
    }

    public function saveSections(Request $request)
    {
        $data = $request->validate([
            'sections' => 'nullable|array',
            'sections.*.level' => 'required|in:junior_high,senior_high',
            'sections.*.name' => 'required|string|max:50',
            'sections.*.grade_level' => 'required|string|max:2',
            'sections.*.student_count' => 'required|integer|min:1|max:60',
            'sections.*.track' => 'nullable|string|max:50',
            'sections.*.required_subjects' => 'nullable|array',
        ]);

        if (!empty($data['sections'])) {
            foreach ($data['sections'] as $section) {
                ScheduleSection::create([
                    'level' => $section['level'],
                    'name' => $section['name'],
                    'grade_level' => $section['grade_level'],
                    'student_count' => $section['student_count'],
                    'track' => $section['track'] ?? null,
                    'required_subjects' => $section['required_subjects'] ?? [],
                ]);
            }
        }

        return redirect()->route('admin.schedule-maker.settings')->with('status','Sections added successfully.');
    }

    public function saveConstraints(Request $request)
    {
        $data = $request->validate([
            'max_consecutive_periods' => 'nullable|integer|min:1|max:12',
            'max_teaching_days_per_week' => 'nullable|integer|min:1|max:7',
            'load_distribution_threshold' => 'nullable|numeric|min:0.5|max:10',
            'senior_junior_ratio' => 'nullable|in:equal,favor_senior,favor_junior',
            'jhs_min_subjects' => 'nullable|integer|min:1|max:8',
            'jhs_max_subjects' => 'nullable|integer|min:1|max:8',
            'jhs_max_sections' => 'nullable|integer|min:1|max:10',
            'jhs_allow_same_subject_gap' => 'boolean',
            'shs_min_subjects' => 'nullable|integer|min:1|max:8',
            'shs_max_subjects' => 'nullable|integer|min:1|max:8',
            'shs_max_sections' => 'nullable|integer|min:1|max:10',
            'shs_prefer_consecutive_periods' => 'boolean',
            'primary_expert_ratio' => 'nullable|integer|min:20|max:100',
            'min_capable_per_subject' => 'nullable|integer|min:1|max:5',
            'enforce_subject_expertise' => 'boolean',
        ]);

        // Get or create a general config if none exists
        $config = SchedulingConfig::first();
        if (!$config) {
            $config = SchedulingConfig::create(['level' => 'general']);
        }

        // Prepare JHS constraints
        $jhsConstraints = [
            'min_subjects' => $data['jhs_min_subjects'] ?? 2,
            'max_subjects' => $data['jhs_max_subjects'] ?? 5,
            'max_sections' => $data['jhs_max_sections'] ?? 4,
            'allow_same_subject_gap' => $data['jhs_allow_same_subject_gap'] ?? false,
        ];

        // Prepare SHS constraints
        $shsConstraints = [
            'min_subjects' => $data['shs_min_subjects'] ?? 1,
            'max_subjects' => $data['shs_max_subjects'] ?? 3,
            'max_sections' => $data['shs_max_sections'] ?? 3,
            'prefer_consecutive_periods' => $data['shs_prefer_consecutive_periods'] ?? false,
        ];

        // Prepare expertise distribution
        $expertiseDistribution = [
            'primary_expert_ratio' => $data['primary_expert_ratio'] ?? 60,
            'min_capable_per_subject' => $data['min_capable_per_subject'] ?? 2,
            'enforce_subject_expertise' => $data['enforce_subject_expertise'] ?? true,
        ];

        // Update config
        $config->update([
            'max_consecutive_periods' => $data['max_consecutive_periods'] ?? 3,
            'max_teaching_days_per_week' => $data['max_teaching_days_per_week'] ?? 5,
            'load_distribution_threshold' => $data['load_distribution_threshold'] ?? 2,
            'senior_junior_ratio' => $data['senior_junior_ratio'] ?? 'equal',
            'jhs_constraints' => $jhsConstraints,
            'shs_constraints' => $shsConstraints,
        ]);

        return redirect()->route('admin.schedule-maker.settings')->with('status','Advanced constraints saved successfully.');
    }

    /**
     * Get teachers by type (for faculty restrictions)
     */
    public function getTeachersByType($type)
    {
        $teachers = [];
        
        switch ($type) {
            case 'ancillary':
                // Teachers with ancillary assignments (not null)
                $teachers = \App\Models\Teacher::whereNotNull('ancillary_assignments')
                    ->where('ancillary_assignments', '!=', '')
                    ->orderBy('name')
                    ->get(['id', 'name', 'ancillary_assignments']);
                break;
            case 'department_head':
                // For now, no specific field - return empty
                // Could be extended to use a designation field
                $teachers = collect();
                break;
            case 'part_time':
                // For now, no specific field - return empty
                $teachers = collect();
                break;
            case 'full_time':
                // All teachers (or filter by status)
                $teachers = \App\Models\Teacher::orderBy('name')
                    ->get(['id', 'name', 'ancillary_assignments']);
                break;
        }
        
        return response()->json($teachers);
    }
    //
}

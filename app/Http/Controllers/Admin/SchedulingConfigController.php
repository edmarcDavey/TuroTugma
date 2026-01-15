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
use App\Models\Teacher;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SchedulingConfigController extends Controller
{
    public function index()
    {
        $jh = SchedulingConfig::with('dayConfigs')->where('level','junior_high')->first();
        $sh = SchedulingConfig::with('dayConfigs')->where('level','senior_high')->first();
        $general = SchedulingConfig::with('periodRestrictions.subject')->where('level','general')->first();
        $subjects = Subject::orderBy('name')->get();
        $teachers = Teacher::orderBy('name')->get();
        $sections_jhs = ScheduleSection::where('level', 'junior_high')->orderBy('name')->get();
        $sections_shs = ScheduleSection::where('level', 'senior_high')->orderBy('name')->get();
        
        // Get distinct ancillary roles from teachers table
        // ancillary_assignments is stored as JSON/text, so we need to extract unique values
        $ancillaryRoles = Teacher::whereNotNull('ancillary_assignments')
            ->get()
            ->pluck('ancillary_assignments')
            ->filter()
            ->map(function($item) {
                // If it's JSON encoded, decode it; otherwise use as-is
                $decoded = json_decode($item, true);
                return is_array($decoded) ? $decoded : [$item];
            })
            ->flatten()
            ->unique()
            ->sort()
            ->values();
        
        return view('admin.schedule-maker.settings', compact('jh','sh','general','subjects','teachers','sections_jhs','sections_shs','ancillaryRoles'));
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

    /**
     * Save JH configuration (period duration, breaks, etc.)
     */
    public function storeJHConfig(Request $request)
    {
        try {
            \Log::info('storeJHConfig called with data:', $request->all());
            
            $validated = $request->validate([
                'period_duration' => 'required|integer|min:30|max:90',
                'total_periods' => 'required|integer|min:4|max:12',
                'active_days' => 'array',
                'active_days.*' => 'integer|min:0|max:6',
                'morning_break_enabled' => 'boolean',
                'morning_break_after_period' => 'nullable|integer|min:1|max:12',
                'morning_break_duration' => 'nullable|integer|min:5|max:60',
                'lunch_break_enabled' => 'boolean',
                'lunch_break_after_period' => 'nullable|integer|min:1|max:12',
                'lunch_break_duration' => 'nullable|integer|min:5|max:90',
                'afternoon_break_enabled' => 'boolean',
                'afternoon_break_after_period' => 'nullable|integer|min:1|max:12',
                'afternoon_break_duration' => 'nullable|integer|min:5|max:60',
                'session_type' => 'required|in:regular,shortened',
            ]);
            
            \Log::info('Validated data:', $validated);
        } catch (\Exception $e) {
            \Log::error('Validation failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Validation error: ' . $e->getMessage()
            ], 422);
        }

        // Get or create JH config
        $config = SchedulingConfig::firstOrCreate(
            ['level' => 'junior_high'],
            ['level' => 'junior_high']
        );

        // Build config array
        $jhConfig = $config->jh_config ? json_decode($config->jh_config, true) : [];
        
        // Store by session type
        if (!isset($jhConfig['sessions'])) {
            $jhConfig['sessions'] = [];
        }

        $jhConfig['sessions'][$validated['session_type']] = [
            'period_duration' => $validated['period_duration'],
            'total_periods' => $validated['total_periods'],
            'active_days' => $validated['active_days'],
            'breaks' => [
                'morning_break_enabled' => $validated['morning_break_enabled'],
                'morning_break_after_period' => $validated['morning_break_after_period'],
                'morning_break_duration' => $validated['morning_break_duration'],
                'lunch_break_enabled' => $validated['lunch_break_enabled'],
                'lunch_break_after_period' => $validated['lunch_break_after_period'],
                'lunch_break_duration' => $validated['lunch_break_duration'],
                'afternoon_break_enabled' => $validated['afternoon_break_enabled'],
                'afternoon_break_after_period' => $validated['afternoon_break_after_period'],
                'afternoon_break_duration' => $validated['afternoon_break_duration'],
            ],
            'start_time' => '07:30', // Can be made configurable later
            'created_at' => now()->toDateTimeString()
        ];

        // Save config
        $config->jh_config = json_encode($jhConfig);
        $config->save();

        \Log::info('Saved JH config:', ['jh_config' => $jhConfig]);

        return response()->json([
            'success' => true,
            'message' => 'Junior High configuration saved successfully',
            'config' => $jhConfig
        ]);
    }

    /**
     * Get the current JH configuration
     */
    public function getJHConfig()
    {
        $config = SchedulingConfig::where('level', 'junior_high')->first();
        
        if (!$config) {
            return response()->json([
                'success' => false,
                'config' => null
            ]);
        }

        $jhConfig = json_decode($config->jh_config ?? '{}', true) ?? [];
        
        return response()->json([
            'success' => true,
            'config' => $jhConfig['sessions'] ?? []
        ]);
    }

    /**
     * Calculate period times based on configuration
     * Returns array of periods with start/end times for both regular and shortened sessions
     */
    public static function calculatePeriodTimes($sessionType = 'regular', $level = 'junior_high')
    {
        \Log::info('calculatePeriodTimes called', ['sessionType' => $sessionType, 'level' => $level]);
        
        $config = SchedulingConfig::where('level', $level)->first();
        
        if (!$config) {
            // Return default hardcoded periods if no config found
            \Log::warning('No config found, returning defaults');
            return self::getDefaultPeriods();
        }

        // Get the appropriate config JSON
        $configData = null;
        if ($level === 'junior_high') {
            $allConfigs = json_decode($config->jh_config ?? '{}', true) ?? [];
            \Log::info('All configs from DB:', $allConfigs);
            $configData = $allConfigs['sessions'][$sessionType] ?? null;
            \Log::info('Config data for session type:', ['configData' => $configData]);
        }

        if (!$configData) {
            \Log::warning('No config data for session type, returning defaults');
            return self::getDefaultPeriods();
        }

        $periods = [];
        $startMinutes = 450; // 7:30 AM in minutes from midnight
        $periodDuration = (int)($configData['period_duration'] ?? 50);
        $totalPeriods = (int)($configData['total_periods'] ?? 9);

        \Log::info('Period calculation params', [
            'periodDuration' => $periodDuration,
            'totalPeriods' => $totalPeriods
        ]);

        // Normalize breaks to flat structure (support both legacy nested and current flat formats)
        $rawBreaks = $configData['breaks'] ?? [];
        \Log::info('Raw breaks from config:', $rawBreaks);
        
        // Check if it's the old nested format
        if (isset($rawBreaks['morning']) || isset($rawBreaks['lunch']) || isset($rawBreaks['afternoon'])) {
            // Legacy nested format - convert to flat
            \Log::info('Converting legacy nested format to flat');
            $breaks = [
                'morning_break_enabled' => (bool)($rawBreaks['morning']['enabled'] ?? false),
                'morning_break_after_period' => (int)($rawBreaks['morning']['after_period'] ?? 2),
                'morning_break_duration' => (int)($rawBreaks['morning']['duration'] ?? 15),
                'lunch_break_enabled' => (bool)($rawBreaks['lunch']['enabled'] ?? false),
                'lunch_break_after_period' => (int)($rawBreaks['lunch']['after_period'] ?? 4),
                'lunch_break_duration' => (int)($rawBreaks['lunch']['duration'] ?? 60),
                'afternoon_break_enabled' => (bool)($rawBreaks['afternoon']['enabled'] ?? false),
                'afternoon_break_after_period' => (int)($rawBreaks['afternoon']['after_period'] ?? 5),
                'afternoon_break_duration' => (int)($rawBreaks['afternoon']['duration'] ?? 10),
            ];
        } else {
            // Already flat format or empty
            \Log::info('Using flat format (already normalized or new data)');
            $breaks = $rawBreaks;
        }
        
        \Log::info('Final breaks array:', $breaks);

        for ($i = 1; $i <= $totalPeriods; $i++) {
            $periodStart = $startMinutes;
            $periodEnd = $startMinutes + $periodDuration;

            $periods[] = [
                'number' => $i,
                'start' => self::minutesToTime($periodStart),
                'end' => self::minutesToTime($periodEnd),
            ];

            // Add break duration if this period has a break after it
            $breakAfterThisPeriod = null;
            if (($breaks['morning_break_enabled'] ?? false) && ($breaks['morning_break_after_period'] ?? null) == $i) {
                $breakAfterThisPeriod = (int)($breaks['morning_break_duration'] ?? 15);
            } elseif (($breaks['lunch_break_enabled'] ?? false) && ($breaks['lunch_break_after_period'] ?? null) == $i) {
                $breakAfterThisPeriod = (int)($breaks['lunch_break_duration'] ?? 60);
            } elseif (($breaks['afternoon_break_enabled'] ?? false) && ($breaks['afternoon_break_after_period'] ?? null) == $i) {
                $breakAfterThisPeriod = (int)($breaks['afternoon_break_duration'] ?? 10);
            }

            if ($breakAfterThisPeriod) {
                $startMinutes = $periodEnd + $breakAfterThisPeriod;
            } else {
                $startMinutes = $periodEnd;
            }
        }

        return $periods;
    }

    /**
     * Convert minutes from midnight to HH:MM format
     */
    private static function minutesToTime($minutes)
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
     * Get default hardcoded periods if no config exists
     */
    private static function getDefaultPeriods()
    {
        return [
            // Include generic start/end so the scheduler header never shows undefined-undefined
            ['number' => 1, 'start_regular' => '7:30', 'end_regular' => '8:30', 'start_shortened' => '7:30', 'end_shortened' => '8:20', 'start' => '7:30', 'end' => '8:30'],
            ['number' => 2, 'start_regular' => '8:30', 'end_regular' => '9:30', 'start_shortened' => '8:20', 'end_shortened' => '9:10', 'start' => '8:30', 'end' => '9:30'],
            ['number' => 3, 'start_regular' => '9:30', 'end_regular' => '10:30', 'start_shortened' => '9:10', 'end_shortened' => '10:00', 'start' => '9:30', 'end' => '10:30'],
            ['number' => 4, 'start_regular' => '10:30', 'end_regular' => '11:30', 'start_shortened' => '10:00', 'end_shortened' => '10:50', 'start' => '10:30', 'end' => '11:30'],
            ['number' => 5, 'start_regular' => '11:30', 'end_regular' => '12:30', 'start_shortened' => '10:50', 'end_shortened' => '11:40', 'start' => '11:30', 'end' => '12:30'],
            ['number' => 6, 'start_regular' => '1:00', 'end_regular' => '2:00', 'start_shortened' => '12:50', 'end_shortened' => '1:40', 'start' => '1:00', 'end' => '2:00'],
            ['number' => 7, 'start_regular' => '2:00', 'end_regular' => '3:00', 'start_shortened' => '1:40', 'end_shortened' => '2:30', 'start' => '2:00', 'end' => '3:00'],
            ['number' => 8, 'start_regular' => '3:00', 'end_regular' => '4:00', 'start_shortened' => '2:30', 'end_shortened' => '3:20', 'start' => '3:00', 'end' => '4:00'],
            ['number' => 9, 'start_regular' => '4:00', 'end_regular' => '5:00', 'start_shortened' => '3:20', 'end_shortened' => '4:10', 'start' => '4:00', 'end' => '5:00'],
        ];
    }
    
    /**
     * Save faculty restrictions to database
     */
    public function saveFacultyRestrictions(Request $request)
    {
        try {
            $restrictions = $request->validate([
                'restrictions' => 'required|array'
            ]);
            
            // Get or create general config
            $config = SchedulingConfig::firstOrCreate(['level' => 'general']);
            
            // Save restrictions as JSON
            $config->faculty_restrictions = json_encode($restrictions['restrictions']);
            $config->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Faculty restrictions saved successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error saving faculty restrictions: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error saving restrictions: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get faculty restrictions from database
     */
    public function getFacultyRestrictions()
    {
        try {
            $config = SchedulingConfig::where('level', 'general')->first();
            
            $restrictions = [];
            if ($config && $config->faculty_restrictions) {
                $restrictions = json_decode($config->faculty_restrictions, true) ?? [];
            }
            
            return response()->json([
                'success' => true,
                'restrictions' => $restrictions
            ]);
        } catch (\Exception $e) {
            Log::error('Error loading faculty restrictions: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error loading restrictions: ' . $e->getMessage(),
                'restrictions' => []
            ], 500);
        }
    }

    /**
     * Save subject constraints to database
     */
    public function saveSubjectConstraints(Request $request)
    {
        try {
            $constraints = $request->validate([
                'constraints' => 'required|array'
            ]);
            
            // Get or create general config
            $config = SchedulingConfig::firstOrCreate(['level' => 'general']);
            
            // Save constraints as JSON
            $config->subject_constraints = json_encode($constraints['constraints']);
            $config->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Subject constraints saved successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error saving subject constraints: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error saving constraints: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get subject constraints from database
     */
    public function getSubjectConstraints()
    {
        try {
            $config = SchedulingConfig::where('level', 'general')->first();
            
            $constraints = [];
            if ($config && $config->subject_constraints) {
                $constraints = json_decode($config->subject_constraints, true) ?? [];
            }
            
            return response()->json([
                'success' => true,
                'constraints' => $constraints
            ]);
        } catch (\Exception $e) {
            Log::error('Error loading subject constraints: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error loading constraints: ' . $e->getMessage(),
                'constraints' => []
            ], 500);
        }
    }
    //
}

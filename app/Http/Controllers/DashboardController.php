<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Show the main dashboard with final timetable.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Get last scheduling run
        $lastSchedulingRun = \App\Models\SchedulingRun::orderBy('created_at', 'desc')->first();
        $finalTimetable = $this->getFinalTimetable($lastSchedulingRun);

        return view('dashboard', [
            'finalTimetable' => $finalTimetable,
        ]);
    }

    /**
     * Get final timetable data for dashboard display.
     */
    private function getFinalTimetable($run)
    {
        if (!$run) {
            return [];
        }

        // Get schedule entries with relationships
        $entries = \App\Models\ScheduleEntry::where('scheduling_run_id', $run->id)
            ->with(['section', 'subject', 'teacher', 'section.gradeLevel'])
            ->orderBy('day')
            ->orderBy('period')
            ->get();

        $timetable = [];
        $dayNames = [1 => 'Monday', 2 => 'Tuesday', 3 => 'Wednesday', 4 => 'Thursday', 5 => 'Friday'];

        foreach ($entries as $entry) {
            // Get period times from scheduling config
            $timeRange = $this->getPeriodTimeRange($entry->period, $run);
            
            // Determine level based on section's grade level
            $level = 'junior'; // default
            $gradeLevel = 'unknown';
            if ($entry->section && $entry->section->gradeLevel) {
                $gradeLevel = $entry->section->gradeLevel->year ?? 'unknown';
                $level = $entry->section->gradeLevel->school_stage ?? 'junior';
            }
            
            $timetable[] = [
                'section' => $entry->section?->name ?? 'Unknown',
                'day_name' => $dayNames[$entry->day] ?? "Day {$entry->day}",
                'day' => $entry->day,
                'period' => $entry->period,
                'time_range' => $timeRange,
                'subject' => $entry->subject?->name ?? 'Unknown',
                'teacher' => $entry->teacher?->name ?? 'Unknown',
                'level' => $level,
                'grade_level' => $gradeLevel, // Add grade level info
            ];
        }

        return $timetable;
    }

    /**
     * Get period time range based on scheduling configuration.
     */
    private function getPeriodTimeRange($period, $run)
    {
        // Fallback to default times
        $defaultTimes = [
            1 => '7:30 - 8:30',
            2 => '8:30 - 9:30', 
            3 => '9:45 - 10:45',
            4 => '10:45 - 11:45',
            5 => '1:00 - 2:00',
            6 => '2:00 - 3:00',
            7 => '3:00 - 4:00',
            8 => '4:00 - 5:00',
        ];
        
        return $defaultTimes[$period] ?? "Period {$period}";
    }
}

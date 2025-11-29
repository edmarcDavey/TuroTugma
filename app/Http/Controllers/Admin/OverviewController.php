<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\Models\Teacher;
use App\Models\Section;
use App\Models\Subject;

class OverviewController extends Controller
{
    /**
     * Show admin overview dashboard with metric cards.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        if (! $user || $user->role !== 'it_coordinator') {
            abort(403);
        }

        $counts = Cache::remember('admin_overview_counts', 60, function () {
            return [
                'teachers' => Teacher::count(),
                'sections' => Section::count(),
                'subjects' => Subject::count(),
            ];
        });

        return view('admin.it.dashboard', [
            'teachersCount' => $counts['teachers'],
            'sectionsCount' => $counts['sections'],
            'subjectsCount' => $counts['subjects'],
        ]);
    }

    /**
     * Return JSON data for charts / analytics.
     */
    public function data(Request $request)
    {
        $user = Auth::user();
        if (! $user || $user->role !== 'it_coordinator') {
            abort(403);
        }

        $counts = [
            'teachers' => Teacher::count(),
            'sections' => Section::count(),
            'subjects' => Subject::count(),
        ];

        return response()->json($counts);
    }
}

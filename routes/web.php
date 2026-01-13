<?php

use Illuminate\Support\Facades\Route;

// Public landing and simple pages
Route::get('/', fn() => view('landing'));
Route::get('/dashboard', fn() => view('dashboard'));
Route::get('/features', fn() => view('features'));
Route::get('/about', fn() => view('about'));

// Include authentication routes (login, register, password, etc.)
require __DIR__ . '/auth.php';

// Role-specific dashboards (require auth)
Route::middleware('auth')->group(function () {
    Route::get('/dashboard/it', fn() => redirect()->route('admin.dashboard'))->name('dashboard.it');
});

// Admin routes for IT coordinator
Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [\App\Http\Controllers\Admin\OverviewController::class, 'index'])->name('dashboard');
    Route::get('/overview/data', [\App\Http\Controllers\Admin\OverviewController::class, 'data'])->name('overview.data');

    // Teachers
    Route::get('/teachers', [\App\Http\Controllers\Admin\TeacherController::class, 'index'])->name('teachers.index');
    Route::get('/teachers/create', [\App\Http\Controllers\Admin\TeacherController::class, 'create'])->name('teachers.create');
    Route::post('/teachers', [\App\Http\Controllers\Admin\TeacherController::class, 'store'])->name('teachers.store');
    Route::get('/teachers/{teacher}/edit', [\App\Http\Controllers\Admin\TeacherController::class, 'edit'])->name('teachers.edit');
    Route::put('/teachers/{teacher}', [\App\Http\Controllers\Admin\TeacherController::class, 'update'])->name('teachers.update');
    Route::delete('/teachers/{teacher}', [\App\Http\Controllers\Admin\TeacherController::class, 'destroy'])->name('teachers.destroy');

    // Teachers bulk actions
    Route::get('/teachers/export', [\App\Http\Controllers\Admin\TeacherController::class, 'export'])->name('teachers.export');
    Route::post('/teachers/import', [\App\Http\Controllers\Admin\TeacherController::class, 'import'])->name('teachers.import');
    Route::get('/teachers/download-template', [\App\Http\Controllers\Admin\TeacherController::class, 'downloadTemplate'])->name('teachers.download-template');

    // Teachers fragments
    Route::get('/teachers/fragment', [\App\Http\Controllers\Admin\TeacherController::class, 'fragmentCreate'])->name('teachers.fragment.create');
    Route::get('/teachers/list', [\App\Http\Controllers\Admin\TeacherController::class, 'listFragment'])->name('teachers.list');
    Route::get('/teachers/subjects.json', [\App\Http\Controllers\Admin\TeacherController::class, 'subjectsJson'])->name('teachers.subjects.json');
    Route::get('/teachers/{teacher}/fragment', [\App\Http\Controllers\Admin\TeacherController::class, 'fragmentEdit'])->name('teachers.fragment.edit');

    // Subjects
    Route::get('/subjects', [\App\Http\Controllers\Admin\SubjectController::class, 'index'])->name('subjects.index');
    Route::get('/subjects/create', [\App\Http\Controllers\Admin\SubjectController::class, 'create'])->name('subjects.create');
    Route::get('/subjects/{subject}', [\App\Http\Controllers\Admin\SubjectController::class, 'show'])->whereNumber('subject')->name('subjects.show');
    Route::post('/subjects', [\App\Http\Controllers\Admin\SubjectController::class, 'store'])->name('subjects.store');
    Route::get('/subjects/{subject}/edit', [\App\Http\Controllers\Admin\SubjectController::class, 'edit'])->name('subjects.edit');
    Route::put('/subjects/{subject}', [\App\Http\Controllers\Admin\SubjectController::class, 'update'])->name('subjects.update');
    Route::delete('/subjects/{subject}', [\App\Http\Controllers\Admin\SubjectController::class, 'destroy'])->name('subjects.destroy');
    Route::get('/subjects/fragment', [\App\Http\Controllers\Admin\SubjectController::class, 'fragmentCreate'])->name('subjects.fragment.create');
    Route::get('/subjects/{subject}/fragment', [\App\Http\Controllers\Admin\SubjectController::class, 'fragmentEdit'])->name('subjects.fragment.edit');

    // Grade levels & sections (essential endpoints)
    Route::get('/grade-levels', [\App\Http\Controllers\Admin\GradeLevelController::class, 'index'])->name('grade-levels.index');
    Route::post('/grade-levels', [\App\Http\Controllers\Admin\GradeLevelController::class, 'store'])->name('grade-levels.store');
    Route::patch('/grade-levels/{gradeLevel}', [\App\Http\Controllers\Admin\GradeLevelController::class, 'update'])->name('grade-levels.update');
    Route::get('/grade-levels/{gradeLevel}/sections', [\App\Http\Controllers\Admin\SectionController::class, 'listForGrade'])->name('grade-levels.sections.index');
    Route::post('/grade-levels/{gradeLevel}/sections', [\App\Http\Controllers\Admin\SectionController::class, 'store'])->name('grade-levels.sections.store');
    Route::put('/sections/{section}', [\App\Http\Controllers\Admin\SectionController::class, 'update'])->name('sections.update');
    Route::delete('/sections/{section}', [\App\Http\Controllers\Admin\SectionController::class, 'destroy'])->name('sections.destroy');
    Route::post('/grade-levels/preview', [\App\Http\Controllers\Admin\GradeLevelController::class, 'previewAnonymous'])->name('grade-levels.preview');
    Route::get('/grade-levels/{gradeLevel}/preview-sections', [\App\Http\Controllers\Admin\GradeLevelController::class, 'previewSections'])->name('grade-levels.preview-sections');

    // Consolidated subjects & sections page
    Route::get('/subjects-sections', function () {
        $gradeLevels = \App\Models\GradeLevel::with('sections')->orderBy('name')->get();
        $subjects = \App\Models\Subject::with('gradeLevels', 'strand')->orderBy('name')->get();
        $strands = \App\Models\Strand::orderBy('name')->get();
        $hasSections = \App\Models\Section::exists();
        return view('admin.sections.subjects-sections', compact('gradeLevels','subjects','strands','hasSections'));
    })->name('subjects-sections');

    // Exports & logs
    Route::get('/exports', fn() => view('admin.exports'))->name('exports');
    Route::get('/logs', fn() => view('admin.logs'))->name('logs');

    // Scheduler (namespaced under admin.scheduler)
    Route::prefix('scheduler')->name('scheduler.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\SchedulingRunController::class, 'index'])->name('index');
        Route::post('/', [\App\Http\Controllers\Admin\SchedulingRunController::class, 'store'])->name('store');
        Route::get('/{run}', [\App\Http\Controllers\Admin\SchedulingRunController::class, 'show'])->whereNumber('run')->name('show');
        Route::get('/{run}/matrix', [\App\Http\Controllers\Admin\SchedulingRunController::class, 'matrix'])->whereNumber('run')->name('matrix');
        Route::post('/{run}/generate', [\App\Http\Controllers\Admin\SchedulingRunController::class, 'generate'])->whereNumber('run')->name('generate');
        Route::get('/{run}/generate/status', [\App\Http\Controllers\Admin\SchedulingRunController::class, 'generateStatus'])->whereNumber('run')->name('generate.status');
        // Additional scheduler endpoints can be added here as needed
    });

    // Schedule Maker placeholders (nav group: Schedule Maker -> Scheduler, Settings)
    Route::prefix('schedule-maker')->name('schedule-maker.')->group(function () {
        Route::get('/scheduler', function() {
            $subjects = \App\Models\Subject::with('teachers')->orderBy('name')->get();
            $teachers = \App\Models\Teacher::with('subjects')->orderBy('name')->get();
            // Get Junior High sections only (grade level IDs 1-4 which are Grade 7-10)
            $sections = \App\Models\Section::with('gradeLevel')
                ->whereIn('grade_level_id', [1, 2, 3, 4])
                ->orderBy('grade_level_id')
                ->orderBy('name')
                ->get();
            return view('admin.schedule-maker.scheduler', compact('subjects', 'teachers', 'sections'));
        })->name('scheduler');
        Route::get('/settings', [\App\Http\Controllers\Admin\SchedulingConfigController::class, 'index'])->name('settings');
        Route::post('/settings', [\App\Http\Controllers\Admin\SchedulingConfigController::class, 'store'])->name('settings.save');
        Route::post('/settings/qualifications', [\App\Http\Controllers\Admin\SchedulingConfigController::class, 'saveQualifications'])->name('settings.save-qualifications');
        Route::post('/settings/sections', [\App\Http\Controllers\Admin\SchedulingConfigController::class, 'saveSections'])->name('settings.save-sections');
        Route::post('/settings/constraints', [\App\Http\Controllers\Admin\SchedulingConfigController::class, 'saveConstraints'])->name('settings.save-constraints');
        Route::get('/settings/teachers-by-type/{type}', [\App\Http\Controllers\Admin\SchedulingConfigController::class, 'getTeachersByType'])->name('settings.teachers-by-type');
    });
});

<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Public landing page (no auth required)
Route::get('/', function () {
    return view('landing');
});

// Dashboard placeholder for 'Get Started' (no auth for scaffold)
Route::get('/dashboard', function () {
    return view('dashboard');
});

// Role-specific dashboards (require auth)
Route::middleware('auth')->group(function () {
    // redirect legacy route to the new admin it dashboard (admin layout uses left-side panel)
    Route::get('/dashboard/it', function () {
        return redirect()->route('admin.it.dashboard');
    })->name('dashboard.it');

    Route::get('/dashboard/scheduler', function () {
        return view('dashboard_scheduler');
    })->name('dashboard.scheduler');
});

// IT Coordinator admin placeholders (protected by auth + simple role check)
Route::middleware('auth')->prefix('admin/it')->name('admin.it.')->group(function () {
    Route::get('/', function () {
        if (Auth::user()->role !== 'it_coordinator') {
            abort(403);
        }
        return view('admin.it.dashboard');
    })->name('dashboard');

    // Teachers resource (basic CRUD)
    Route::get('/teachers', function () {
        if (Auth::user()->role !== 'it_coordinator') abort(403);
        return app(\App\Http\Controllers\Admin\TeacherController::class)->index(request());
    })->name('teachers.index');

    Route::get('/teachers/create', function () {
        if (Auth::user()->role !== 'it_coordinator') abort(403);
        return app(\App\Http\Controllers\Admin\TeacherController::class)->create();
    })->name('teachers.create');

    Route::post('/teachers', function () {
        if (Auth::user()->role !== 'it_coordinator') abort(403);
        return app(\App\Http\Controllers\Admin\TeacherController::class)->store(request());
    })->name('teachers.store');

    Route::get('/teachers/{teacher}/edit', function ($teacher) {
        if (Auth::user()->role !== 'it_coordinator') abort(403);
        $t = \App\Models\Teacher::findOrFail($teacher);
        return app(\App\Http\Controllers\Admin\TeacherController::class)->edit($t);
    })->name('teachers.edit');

    Route::put('/teachers/{teacher}', function ($teacher) {
        if (Auth::user()->role !== 'it_coordinator') abort(403);
        $t = \App\Models\Teacher::findOrFail($teacher);
        return app(\App\Http\Controllers\Admin\TeacherController::class)->update(request(), $t);
    })->name('teachers.update');

    Route::delete('/teachers/{teacher}', function ($teacher) {
        if (Auth::user()->role !== 'it_coordinator') abort(403);
        $t = \App\Models\Teacher::findOrFail($teacher);
        return app(\App\Http\Controllers\Admin\TeacherController::class)->destroy($t);
    })->name('teachers.destroy');

    // Fragments for master-detail AJAX loading
    Route::get('/teachers/fragment', function () {
        if (Auth::user()->role !== 'it_coordinator') abort(403);
        return app(\App\Http\Controllers\Admin\TeacherController::class)->fragmentCreate();
    })->name('teachers.fragment.create');

    // Fragment that returns list items for infinite scroll (JSON)
    Route::get('/teachers/list', function () {
        if (Auth::user()->role !== 'it_coordinator') abort(403);
        return app(\App\Http\Controllers\Admin\TeacherController::class)->listFragment(request());
    })->name('teachers.list');

    // Subjects JSON for teacher form fallback
    Route::get('/teachers/subjects.json', function () {
        if (Auth::user()->role !== 'it_coordinator') abort(403);
        return app(\App\Http\Controllers\Admin\TeacherController::class)->subjectsJson();
    })->name('teachers.subjects.json');

    Route::get('/teachers/{teacher}/fragment', function ($teacher) {
        if (Auth::user()->role !== 'it_coordinator') abort(403);
        $t = \App\Models\Teacher::findOrFail($teacher);
        return app(\App\Http\Controllers\Admin\TeacherController::class)->fragmentEdit($t);
    })->name('teachers.fragment.edit');

    Route::get('/subjects', function () {
        if (Auth::user()->role !== 'it_coordinator') abort(403);
        return app(\App\Http\Controllers\Admin\SubjectController::class)->index();
    })->name('subjects.index');

    Route::get('/subjects/create', function () {
        if (Auth::user()->role !== 'it_coordinator') abort(403);
        return app(\App\Http\Controllers\Admin\SubjectController::class)->create();
    })->name('subjects.create');

    Route::post('/subjects', function () {
        if (Auth::user()->role !== 'it_coordinator') abort(403);
        return app(\App\Http\Controllers\Admin\SubjectController::class)->store(request());
    })->name('subjects.store');

    Route::get('/subjects/{subject}/edit', function ($subject) {
        if (Auth::user()->role !== 'it_coordinator') abort(403);
        return app(\App\Http\Controllers\Admin\SubjectController::class)->edit($subject);
    })->name('subjects.edit');

    Route::put('/subjects/{subject}', function ($subject) {
        if (Auth::user()->role !== 'it_coordinator') abort(403);
        return app(\App\Http\Controllers\Admin\SubjectController::class)->update(request(), $subject);
    })->name('subjects.update');

    Route::delete('/subjects/{subject}', function ($subject) {
        if (Auth::user()->role !== 'it_coordinator') abort(403);
        return app(\App\Http\Controllers\Admin\SubjectController::class)->destroy($subject);
    })->name('subjects.destroy');

    // fragment routes for subjects
    Route::get('/subjects/fragment', function () {
        if (Auth::user()->role !== 'it_coordinator') abort(403);
        return app(\App\Http\Controllers\Admin\SubjectController::class)->fragmentCreate();
    })->name('subjects.fragment.create');

    Route::get('/subjects/{subject}/fragment', function ($subject) {
        if (Auth::user()->role !== 'it_coordinator') abort(403);
        return app(\App\Http\Controllers\Admin\SubjectController::class)->fragmentEdit($subject);
    })->name('subjects.fragment.edit');

    // Grade Levels & Sections routes (master/detail + AJAX preview & bulk-create)
    Route::get('/grade-levels', function () {
        if (Auth::user()->role !== 'it_coordinator') abort(403);
        return app(\App\Http\Controllers\Admin\GradeLevelController::class)->index();
    })->name('grade-levels.index');

    Route::get('/grade-levels/create', function () {
        if (Auth::user()->role !== 'it_coordinator') abort(403);
        return app(\App\Http\Controllers\Admin\GradeLevelController::class)->fragmentCreate();
    })->name('grade-levels.create');

    // fragment routes for grade-levels (master-detail)
    Route::get('/grade-levels/fragment', function () {
        if (Auth::user()->role !== 'it_coordinator') abort(403);
        return app(\App\Http\Controllers\Admin\GradeLevelController::class)->fragmentCreate();
    })->name('grade-levels.fragment.create');

    // AJAX toggle for subject <-> grade assignment (used by combined UI)
    Route::post('/subjects/{subject}/toggle-grade/{gradeLevel}', function ($subject, $gradeLevel) {
        if (Auth::user()->role !== 'it_coordinator') abort(403);
        return app(\App\Http\Controllers\Admin\SubjectController::class)->toggleGrade(request(), $subject, $gradeLevel);
    })->name('subjects.toggle-grade');

    Route::get('/grade-levels/{gradeLevel}/fragment', function ($gradeLevel) {
        if (Auth::user()->role !== 'it_coordinator') abort(403);
        return app(\App\Http\Controllers\Admin\GradeLevelController::class)->fragmentEdit($gradeLevel);
    })->name('grade-levels.fragment.edit');

    Route::post('/grade-levels', function () {
        if (Auth::user()->role !== 'it_coordinator') abort(403);
        return app(\App\Http\Controllers\Admin\GradeLevelController::class)->store(request());
    })->name('grade-levels.store');

    

    // Update grade level (persist section naming, options, and school stage)
    Route::patch('/grade-levels/{gradeLevel}', function ($gradeLevel) {
        if (Auth::user()->role !== 'it_coordinator') abort(403);
        return app(\App\Http\Controllers\Admin\GradeLevelController::class)->update(request(), $gradeLevel);
    })->name('grade-levels.update');

    // Bulk create sections for a grade level
    Route::post('/grade-levels/{gradeLevel}/sections/bulk-create', function ($gradeLevel) {
        if (Auth::user()->role !== 'it_coordinator') abort(403);
        $gl = \App\Models\GradeLevel::findOrFail($gradeLevel);
        return app(\App\Http\Controllers\Admin\SectionController::class)->bulkStore(request(), $gl);
    })->name('grade-levels.sections.bulk-create');

    // Preview generated sections for a grade level (AJAX)
    Route::get('/grade-levels/{gradeLevel}/preview-sections', function ($gradeLevel) {
        if (Auth::user()->role !== 'it_coordinator') abort(403);
        $gl = \App\Models\GradeLevel::findOrFail($gradeLevel);
        return app(\App\Http\Controllers\Admin\GradeLevelController::class)->previewSections(request(), $gl);
    })->name('grade-levels.preview-sections');

    // Preview generated sections from inline inputs (no existing grade required)
    Route::post('/grade-levels/preview', function () {
        if (Auth::user()->role !== 'it_coordinator') abort(403);
        return app(\App\Http\Controllers\Admin\GradeLevelController::class)->previewAnonymous(request());
    })->name('grade-levels.preview');

    // Grade levels management removed per admin configuration (forms/views deleted)

    Route::get('/rooms', function () {
        if (Auth::user()->role !== 'it_coordinator') abort(403);
        return view('admin.it.rooms.index');
    })->name('rooms.index');

    Route::get('/scheduling', function () {
        if (Auth::user()->role !== 'it_coordinator') abort(403);
        return view('admin.it.scheduling.index');
    })->name('scheduling.index');

        // Consolidated Subjects & Sections page (navigation target)
        Route::get('/subjects-sections', function () {
            if (Auth::user()->role !== 'it_coordinator') abort(403);
        $gradeLevels = \App\Models\GradeLevel::with('sections')->orderBy('name')->get();
        $subjects = \App\Models\Subject::with('gradeLevels')->orderBy('name')->get();
        // compute once in the controller/route and pass to the view so Blade does not query the model
        $hasSections = \App\Models\Section::exists();
            return view('admin.it.subjects-sections', compact('gradeLevels','subjects','hasSections'));
    })->name('subjects-sections');

    Route::get('/scheduler/run', function () {
        if (Auth::user()->role !== 'it_coordinator') abort(403);
        return view('admin.it.scheduler.run');
    })->name('scheduler.run');

    Route::get('/exports', function () {
        if (Auth::user()->role !== 'it_coordinator') abort(403);
        return view('admin.it.exports');
    })->name('exports');

    Route::get('/logs', function () {
        if (Auth::user()->role !== 'it_coordinator') abort(403);
        return view('admin.it.logs');
    })->name('logs');
});

// Simple pages for Features and About
Route::get('/features', function () {
    return view('features');
});

Route::get('/about', function () {
    return view('about');
});

// Include authentication routes (login, register, password, etc.)
require __DIR__.'/auth.php';

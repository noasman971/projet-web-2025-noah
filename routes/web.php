<?php

use App\Http\Controllers\AdminBilansController;
use App\Http\Controllers\CohortController;
use App\Http\Controllers\CommonLifeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RetroController;
use App\Http\Controllers\StudentBilansController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\KnowledgeController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\TeacherController;
use Illuminate\Support\Facades\Route;

// Redirect the root path to /dashboard
Route::redirect('/', 'dashboard');

Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::middleware('verified')->group(function () {
        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Cohorts
        Route::get('/cohorts', [CohortController::class, 'index'])->name('cohort.index');
        Route::get('/cohort/{cohort}', [CohortController::class, 'show'])->name('cohort.show');

        // Teachers
        Route::get('/teachers', [TeacherController::class, 'index'])->name('teacher.index');

        // Students
        Route::get('students', [StudentController::class, 'index'])->name('student.index');

        // Knowledge
        Route::get('knowledge', [KnowledgeController::class, 'index'])->name('knowledge.index');
        Route::post('knowledge', [KnowledgeController::class, 'createQcm'])->name('knowledge.qcm');
        Route::put('knowledge/{id}', [KnowledgeController::class, 'updateQcmCohort'])->name('knowledge.update');


        // Groups
        Route::get('groups', [GroupController::class, 'index'])->name('group.index');

        // Retro
        route::get('retros', [RetroController::class, 'index'])->name('retro.index');

        // Common life
        Route::get('common-life', [CommonLifeController::class, 'index'])->name('common-life.index');
        Route::post('common-life', [CommonLifeController::class, 'create'])->name('common-life.create');
        Route::put('common-life/{id}/pointer', [CommonLifeController::class, 'pointer'])->name('common-life.pointer');
        Route::put('common-life/{id}', [CommonLifeController::class, 'update'])->name('common-life.update');
        Route::delete('common-life/{id}', [CommonLifeController::class, 'destroy'])->name('common-life.destroy');

        // History
        Route::get('history', [HistoryController::class, 'index'])->name('history.index');

        // Admin Bilans
        Route::get('adminKnowledge/{id}', [AdminBilansController::class, 'index'])->name('adminKnowledge.index');

        // Student Bilans
        Route::get('studentKnowledge/{id}', [StudentBilansController::class, 'index'])->name('studentKnowledge.index');
    });

});

require __DIR__.'/auth.php';

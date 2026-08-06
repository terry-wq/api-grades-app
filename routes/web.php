<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\IncidenceController;
use App\Http\Controllers\PredefinedIncidenceController;
use App\Http\Controllers\PracticeController;
use App\Http\Controllers\ExcelController;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;

/*
|--------------------------------------------------------------------------
| REST API Routes for React App (Rate limited to 60 req/min)
|--------------------------------------------------------------------------
*/
Route::prefix('api')->middleware('throttle:60,1')->group(function () {
    Route::get('/public-data', [ApiController::class, 'getPublicData'])->name('api.public-data');
});

// Breeze Auth Routes (Guest Only - Rate limited to prevent DDoS/Bruteforce)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store'])->middleware('throttle:5,1');
    //Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::get('/register', function() {
        return 'Not available';
    })->name('register');
    //Route::post('/register', [RegisteredUserController::class, 'store'])->middleware('throttle:5,1');
});

/*
|--------------------------------------------------------------------------
| Authenticated Teacher / Admin Panel Routes (Protected for Professor)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    // Admin Panel Dashboard & Teacher Profile Settings
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
    Route::put('/admin/profile', [AdminController::class, 'updateProfile'])->name('admin.profile.update');

    // Group Management (Create, Edit, Delete, Settings)
    Route::post('/groups', [AdminController::class, 'storeGroup'])->name('groups.store');
    Route::put('/groups/{id}', [DashboardController::class, 'updateGroupSettings'])->name('groups.update');
    Route::delete('/groups/{id}', [AdminController::class, 'destroyGroup'])->name('groups.destroy');

    // Student Management (Create, Edit General Info, Edit Avatar, Edit Grade, Delete, Update Evaluations)
    Route::post('/students', [StudentController::class, 'store'])->name('students.store');
    Route::put('/students/{id}', [StudentController::class, 'update'])->name('students.update');
    Route::put('/students/{id}/avatar', [StudentController::class, 'updateAvatar'])->name('students.update-avatar');
    Route::put('/students/{id}/grade', [StudentController::class, 'updateGrade'])->name('students.update-grade');
    Route::put('/students/{id}/evaluations', [StudentController::class, 'updateEvaluationGrades'])->name('students.update-evaluations');
    Route::delete('/students/{id}', [StudentController::class, 'destroy'])->name('students.destroy');

    // Incidences Management (Create, Delete, Predefined)
    Route::post('/incidences', [IncidenceController::class, 'store'])->name('incidences.store');
    Route::delete('/incidences/{id}', [IncidenceController::class, 'destroy'])->name('incidences.destroy');
    Route::post('/predefined-incidences', [PredefinedIncidenceController::class, 'store'])->name('predefined-incidences.store');
    Route::delete('/predefined-incidences/{id}', [PredefinedIncidenceController::class, 'destroy'])->name('predefined-incidences.destroy');

    // Practices & Evaluations
    Route::post('/practices', [PracticeController::class, 'store'])->name('practices.store');

    // Excel & CSV Export / Import Backup
    Route::get('/excel/export/{groupId}', [ExcelController::class, 'exportCsv'])->name('excel.export');
});



require __DIR__.'/auth.php';

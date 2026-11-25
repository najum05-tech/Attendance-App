<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\DashboardController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Auth\PasswordController;

// ADMIN CONTROLLERS
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\AdminAttendanceController;
use App\Http\Controllers\Admin\LeaveApprovalController;
use App\Http\Controllers\Admin\TaskController;
use App\Http\Controllers\Admin\AttendanceReportController;
use App\Http\Controllers\Admin\GradingController;

// USER CONTROLLERS
use App\Http\Controllers\User\AttendanceController;
use App\Http\Controllers\User\LeaveController;
use App\Http\Controllers\User\TaskSubmissionController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

// Home page
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Registration
Route::get('/register', [RegisteredUserController::class, 'create'])
    ->middleware('guest')
    ->name('register');
Route::post('/register', [RegisteredUserController::class, 'store'])
    ->middleware('guest')
    ->name('register.store');

// Login
Route::get('/login', [AuthenticatedSessionController::class, 'create'])
    ->middleware('guest')
    ->name('login');
Route::post('/login', [AuthenticatedSessionController::class, 'store'])
    ->middleware('guest')
    ->name('login.store');

// Logout
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

/*
|--------------------------------------------------------------------------
| Dashboard Redirect Based on Role
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', [DashboardController::class, 'redirectBasedOnRole'])
    ->middleware('auth')
    ->name('dashboard');

/*
|--------------------------------------------------------------------------
| Profile Routes (all authenticated users)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

Route::middleware('auth')->group(function () {
    // ... other routes ...
    Route::put('password', [PasswordController::class, 'update'])->name('password.update');
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'adminDashboard'])->name('dashboard');

    // Users - only index, show, edit, update, destroy (no create)
    Route::get('/users', [UserManagementController::class, 'index'])->name('users.index');
    Route::get('/users/{user}', [UserManagementController::class, 'show'])->name('users.show');
    Route::get('/users/{user}/edit', [UserManagementController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserManagementController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserManagementController::class, 'destroy'])->name('users.destroy');
    
    // Roles
    Route::resource('roles', RoleController::class);
    
    // Attendance
    Route::resource('attendance', AdminAttendanceController::class);
    
    // Leaves
    Route::resource('leaves', LeaveApprovalController::class);
    Route::post('/leaves/{leave}/approve', [LeaveApprovalController::class, 'approve'])->name('leaves.approve');
    Route::post('/leaves/{leave}/reject', [LeaveApprovalController::class, 'reject'])->name('leaves.reject');
    
    // Tasks
    Route::resource('tasks', TaskController::class);
    Route::post('/tasks/{task}/approve', [TaskController::class, 'approve'])->name('tasks.approve');
    Route::post('/tasks/{task}/reject', [TaskController::class, 'reject'])->name('tasks.reject');
    
    // Grades - only index (no create/edit)
    Route::get('/grades', [GradingController::class, 'index'])->name('grades.index');
    Route::get('/grades/config', [\App\Http\Controllers\Admin\GradeConfigController::class, 'index'])->name('grades.config');
    Route::post('/grades/config', [\App\Http\Controllers\Admin\GradeConfigController::class, 'update'])->name('grades.config.update');

    // Reports
    Route::get('/reports', [AttendanceReportController::class, 'index'])->name('reports.index');
    Route::post('/reports/generate', [AttendanceReportController::class, 'generateReport'])->name('reports.generate');
});

/*
|--------------------------------------------------------------------------
| User Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:student'])->prefix('user')->name('user.')->group(function () {

    Route::get('/dashboard', function() {
        $tasks = \App\Models\Task::where('user_id', auth()->id())->get();
        return view('user.user', compact('tasks'));
    })->name('dashboard');

    Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::get('/attendance/mark', [AttendanceController::class, 'create'])->name('attendance.mark');
    Route::post('/attendance/mark', [AttendanceController::class, 'store'])->name('attendance.store');

    Route::resource('leaves', LeaveController::class);

    Route::get('/tasks', [TaskSubmissionController::class, 'index'])->name('tasks.index');
    Route::get('/tasks/{task}/submit', [TaskSubmissionController::class, 'edit'])->name('tasks.submit');
    Route::post('/tasks/{task}/submit', [TaskSubmissionController::class, 'update'])->name('tasks.submit.store');
});

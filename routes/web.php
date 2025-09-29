<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\TaskController;

// Redirección inicial
Route::redirect('/', '/login');
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store']);
    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store']);
});
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/stats', [DashboardController::class, 'stats'])->name('dashboard.stats');
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');
    Route::get('/myaccount', [AccountController::class, 'index'])->name('account');
    // Gestión de usuarios (futuro - admin)
    Route::resource('users', UserController::class);
});


Route::middleware(['auth'])->group(function () {
    
    // Rutas de tareas
    Route::prefix('tasks')->name('tasks.')->group(function () {
        Route::get('/', [TaskController::class, 'index'])->name('index');
        
        // Tareas específicas de un grupo
        Route::get('/group/{groupId}', [TaskController::class, 'showGroupTasks'])->name('group');
        Route::post('/{taskId}/upload-evidence', [TaskController::class, 'uploadEvidence'])->name('upload-evidence');
        Route::delete('/{taskId}/evidence/{evidenciaId}', [TaskController::class, 'deleteEvidence'])->name('delete-evidence');
        Route::put('/{taskId}/evidence/{evidenciaId}/rename', [TaskController::class, 'renameEvidence'])->name('rename-evidence');
        Route::post('/{taskId}/request-pause', [TaskController::class, 'requestPause'])->name('request-pause');
        Route::get('/{taskId}/details', [TaskController::class, 'getTaskDetails'])->name('details');
        
    });
    
});
// Rutas protegidas
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // My Groups
    Route::get('/groups', [GroupController::class, 'index'])->name('groups.index');
    
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');
});
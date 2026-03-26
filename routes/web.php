<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\SecretaryDashboardController;
use App\Http\Controllers\FormsController;
use Illuminate\Support\Facades\Route;

// Redirect root to login if not authenticated, otherwise to appropriate dashboard
Route::get('/', function () {
    if (auth()->check()) {
        $user = auth()->user();
        return redirect($user->role === 'admin' ? route('admin.dashboard') : route('secretary.dashboard'));
    }
    return redirect(route('login'));
});

// Admin Dashboard Routes
Route::middleware(['auth', 'admin.role'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.m-e-dashboard');
    })->name('dashboard');
    Route::get('/extension-programs', function () {
        return view('admin.extension-programs');
    })->name('extension-programs');
    Route::get('/reports', function () {
        return view('admin.reports');
    })->name('reports');
});

// Secretary Dashboard Routes
Route::middleware(['auth', 'secretary.role'])->prefix('secretary')->name('secretary.')->group(function () {
    Route::get('/dashboard', function () {
        return view('secretary.m-e-dashboard');
    })->name('dashboard');
    Route::get('/communities', function () {
        return view('secretary.communities');
    })->name('communities');
    Route::get('/extension-programs', function () {
        return view('secretary.extension-programs');
    })->name('extension-programs');
    Route::get('/beneficiaries', function () {
        return view('secretary.beneficiaries');
    })->name('beneficiaries');
});

// Program Planning & Baseline Routes (Phase 1-3)
Route::middleware(['auth', 'secretary.role'])->prefix('programs')->name('programs.')->group(function () {
    Route::get('/{id}/manage', function ($id) {
        return view('programs.manage', ['program_id' => $id]);
    })->name('manage');
    Route::get('/{id}/logic-model', function ($id) {
        return view('programs.logic-model', ['program_id' => $id]);
    })->name('logic-model');
    Route::get('/{id}/baseline', function ($id) {
        return view('programs.baseline', ['program_id' => $id]);
    })->name('baseline');
    Route::get('/{id}/activities', function ($id) {
        return view('programs.activities', ['program_id' => $id]);
    })->name('activities');
    Route::get('/{id}/outputs', function ($id) {
        return view('programs.outputs', ['program_id' => $id]);
    })->name('outputs');
});

// Forms Routes (Secretary)
Route::middleware(['auth', 'secretary.role'])->prefix('forms')->name('forms.')->group(function () {
    Route::get('/', [FormsController::class, 'index'])->name('index');
    Route::get('/{id}', [FormsController::class, 'show'])->name('show');
});;

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

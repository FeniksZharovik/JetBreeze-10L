<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Halaman pertama langsung ke login
Route::get('/', function () {
    return redirect()->route('login');
});

// Middleware untuk tamu (belum login)
Route::middleware('guest')->group(function () {
    Route::get('/login', function () {
        return Inertia::render('Auth/Login');
    })->name('login');

    Route::get('/register', function () {
        return Inertia::render('Auth/Register');
    })->name('register');
});

// Redirect setelah login berdasarkan role
Route::get('/redirect', function () {
    $user = auth()->user();
    if (!$user) {
        return redirect()->route('login');
    }

    return match ($user->role) {
        'admin' => redirect()->route('admin.dashboard'),
        'user' => redirect()->route('user.dashboard'),
        default => redirect()->route('dashboard'),
    };
})->middleware('auth');

// Dashboard berdasarkan role
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::middleware('role:admin')->group(function () {
        Route::get('/admin', [DashboardController::class, 'admin'])->name('admin.dashboard');
    });

    Route::middleware('role:user')->group(function () {
        Route::get('/user', [DashboardController::class, 'user'])->name('user.dashboard');
    });

    // Profile Management
    Route::prefix('/profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('destroy');
    });
});

require __DIR__.'/auth.php';

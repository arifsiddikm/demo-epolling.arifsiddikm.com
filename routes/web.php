<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\PublicPollController;
use App\Http\Controllers\User\DashboardController;
use App\Http\Controllers\User\PollController;
use App\Http\Controllers\User\ExportController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\UserController as AdminUser;
use App\Http\Controllers\Admin\PollController as AdminPoll;
use App\Http\Controllers\Admin\AccountController as AdminAccount;
use App\Http\Controllers\Admin\ExportController as AdminExport;

// Landing Page
Route::get('/', fn() => view('landing'))->name('home');

// Auth Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Public Poll Routes
Route::get('/p/{slug}', [PublicPollController::class, 'show'])->name('poll.show');
Route::post('/p/{slug}/check', [PublicPollController::class, 'checkKey'])->name('poll.check');
Route::post('/p/{slug}/vote', [PublicPollController::class, 'vote'])->name('poll.vote');
Route::get('/p/{slug}/result', [PublicPollController::class, 'result'])->name('poll.result');

// User Routes
Route::middleware(['auth'])->prefix('dashboard')->name('user.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [DashboardController::class, 'profile'])->name('profile');
    Route::put('/profile', [DashboardController::class, 'updateProfile'])->name('profile.update');
    Route::put('/profile/password', [DashboardController::class, 'updatePassword'])->name('profile.password');

    // Polls CRUD
    Route::resource('polls', PollController::class);
    Route::patch('/polls/{poll}/toggle', [PollController::class, 'toggleStatus'])->name('polls.toggle');
    Route::get('/polls/{poll}/recap', [PollController::class, 'recap'])->name('polls.recap');

    // Exports
    Route::get('/polls/{poll}/export-pdf', [ExportController::class, 'exportPdf'])->name('polls.export.pdf');
    Route::get('/polls/{poll}/export-excel', [ExportController::class, 'exportExcel'])->name('polls.export.excel');
    Route::get('/export/summary-pdf', [ExportController::class, 'exportSummaryPdf'])->name('export.summary.pdf');
    Route::get('/export/summary-excel', [ExportController::class, 'exportSummaryExcel'])->name('export.summary.excel');
});

// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('webmin')->name('admin.')->group(function () {
    Route::get('/', [AdminDashboard::class, 'index'])->name('dashboard');

    // Users
    Route::get('/users', [AdminUser::class, 'index'])->name('users.index');
    Route::get('/users/{user}', [AdminUser::class, 'show'])->name('users.show');
    Route::patch('/users/{user}/toggle', [AdminUser::class, 'toggleStatus'])->name('users.toggle');
    Route::delete('/users/{user}', [AdminUser::class, 'destroy'])->name('users.destroy');

    // Polls
    Route::get('/polls', [AdminPoll::class, 'index'])->name('polls.index');
    Route::get('/polls/{poll}', [AdminPoll::class, 'show'])->name('polls.show');
    Route::patch('/polls/{poll}/toggle', [AdminPoll::class, 'toggleStatus'])->name('polls.toggle');
    Route::delete('/polls/{poll}', [AdminPoll::class, 'destroy'])->name('polls.destroy');

    // Account
    Route::get('/account', [AdminAccount::class, 'index'])->name('account');
    Route::put('/account', [AdminAccount::class, 'update'])->name('account.update');
    Route::put('/account/password', [AdminAccount::class, 'updatePassword'])->name('account.password');

    // Exports
    Route::get('/export/polls-pdf', [AdminExport::class, 'exportPollsPdf'])->name('export.polls.pdf');
    Route::get('/export/polls-excel', [AdminExport::class, 'exportPollsExcel'])->name('export.polls.excel');
    Route::get('/export/users-pdf', [AdminExport::class, 'exportUsersPdf'])->name('export.users.pdf');
    Route::get('/export/users-excel', [AdminExport::class, 'exportUsersExcel'])->name('export.users.excel');
});

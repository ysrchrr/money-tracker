<?php

use App\Http\Controllers\Admin\AuditLogController as AdminAuditLogController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ImpersonationController as AdminImpersonationController;
use App\Http\Controllers\Admin\MemberController as AdminMemberController;
use App\Http\Controllers\Admin\ReportController as AdminReportController;
use App\Http\Controllers\Admin\TransactionController as AdminTransactionController;
use App\Http\Controllers\CashFlowController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReminderController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'welcome'])->name('home');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'member.context'])
    ->name('dashboard');

Route::middleware(['auth', 'superadmin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/members', [AdminMemberController::class, 'index'])->name('members.index');
    Route::patch('/members/{user}', [AdminMemberController::class, 'update'])->name('members.update');
    Route::get('/members/{user}', [AdminMemberController::class, 'show'])->name('members.show');
    Route::post('/members/{user}/impersonate', [AdminImpersonationController::class, 'store'])->name('members.impersonate');
    Route::delete('/impersonation', [AdminImpersonationController::class, 'destroy'])->name('impersonation.destroy');
    Route::get('/transactions', [AdminTransactionController::class, 'index'])->name('transactions.index');
    Route::get('/reports', [AdminReportController::class, 'index'])->name('reports.index');
    Route::get('/audit-logs', [AdminAuditLogController::class, 'index'])->name('audit-logs.index');
});

Route::middleware(['auth', 'member.context', 'readonly.impersonation'])->group(function () {
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::patch('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

    Route::get('/cash-flows', [CashFlowController::class, 'index'])->name('cash-flows.index');
    Route::post('/cash-flows', [CashFlowController::class, 'store'])->name('cash-flows.store');
    Route::patch('/cash-flows/{cashFlow}', [CashFlowController::class, 'update'])->name('cash-flows.update');
    Route::delete('/cash-flows/{cashFlow}', [CashFlowController::class, 'destroy'])->name('cash-flows.destroy');

    Route::get('/reminder', [ReminderController::class, 'index'])->name('reminder.index');
    Route::put('/reminder', [ReminderController::class, 'update'])->name('reminder.update');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

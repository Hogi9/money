<?php

use App\Http\Controllers\ApiTokenController;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\TransactionNameController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WalletController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect('/dashboard');
    }
    return view('landing');
});

Route::get('/login',[AuthenticationController::class,'index'])->name('login');
Route::post('/login',[AuthenticationController::class,'login'])->name('login.post');
Route::get('/logout',[AuthenticationController::class,'logout'])->name('logout');

Route::get('/register',[AuthenticationController::class,'register'])->name('register');
Route::post('/register',[AuthenticationController::class,'registerProcess'])->name('register.post');

Route::get('/forget-password',[AuthenticationController::class,'resetPassword'])->name('password.request');
Route::post('/forget-password',[AuthenticationController::class,'sendResetLink'])->name('password.email');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/dashboard/wallets', [DashboardController::class, 'walletsPage'])->name('dashboard.wallets');
    Route::post('/dashboard/transactions', [DashboardController::class, 'transactionsPage'])->name('dashboard.transactions');

    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::post('/profile/api-token/generate', [ApiTokenController::class, 'generate'])->name('api-token.generate');
    Route::post('/profile/api-token/reset', [ApiTokenController::class, 'reset'])->name('api-token.reset');
    Route::delete('/profile/api-token', [ApiTokenController::class, 'revoke'])->name('api-token.revoke');

    Route::resource('users', UserController::class)->except(['show']);
    Route::resource('roles', RoleController::class)->except(['show']);
    Route::resource('permissions', PermissionController::class)->except(['show']);
    Route::resource('teams', TeamController::class);
    Route::resource('menus', MenuController::class)->except(['show']);
    Route::post('teams/{team}/members', [TeamController::class, 'addMember'])->name('teams.members.add');
    Route::delete('teams/{team}/members/{user}', [TeamController::class, 'removeMember'])->name('teams.members.remove');

    // Finance
    Route::resource('categories', CategoryController::class)->except(['show']);
    Route::resource('transaction-names', TransactionNameController::class)->except(['show']);
    Route::resource('wallets', WalletController::class)->except(['show']);
    Route::resource('transactions', TransactionController::class)->except(['show']);

    // Feedback
    Route::resource('feedbacks', FeedbackController::class)->except(['edit']);
});
<?php

use App\Http\Controllers\admin\AddUsersController;
use App\Http\Controllers\admin\AdminController;
use App\Http\Controllers\Admin\CashsController;
use App\Http\Controllers\admin\CashsOutController;
use App\Http\Controllers\Admin\CategoriesController;
use App\Http\Controllers\admin\ProfileUpdateController;
use App\Http\Controllers\user\ProfileOwnerUpdateController;
use App\Http\Controllers\user\UserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    if (Auth::check()) {
        $user = Auth::user();
        if ($user->role->name === 'admin') {
            return redirect()->route('admin.dashboard');
        } else {
            return redirect()->route('home');
        }
    }
    return redirect()->route('login');
})->name('home');

Auth::routes(['middleware' => ['redirectIfAuthenticated']]);


Route::middleware(['auth', 'role.admin'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashboard');

    Route::resource('categories', CategoriesController::class);
    Route::resource('cashs', CashsController::class)->except(['show'])->names([
        'index' => 'cashs.index',
        'create' => 'cashs.create',
        'store' => 'cashs.store',
        'edit' => 'cashs.edit',
        'update' => 'cashs.update',
        'destroy' => 'cashs.destroy',
    ]);
    Route::resource('cashs-out', CashsOutController::class)->except(['show'])->names([
        'index' => 'cashs-out.index',
        'create' => 'cashs-out.create',
        'store' => 'cashs-out.store',
        'edit' => 'cashs-out.edit',
        'update' => 'cashs-out.update',
        'destroy' => 'cashs-out.destroy',
    ]);
    Route::get('cashs/export', [CashsController::class, 'export'])->name('cashs.export');
    Route::get('cashsout/export', [CashsOutController::class, 'export'])->name('cashsout.export');

    Route::get('/profile', [ProfileUpdateController::class, 'index'])->name('profile.index');
    Route::put('/profile/update', [ProfileUpdateController::class, 'update'])->name('profile.update');
    Route::resource('add-users', AddUsersController::class);
    Route::get('/cashs/monthly-report', [CashsController::class, 'monthlyReport'])->name('cashs.monthlyReport');
    Route::get('/cash-out/monthly-report', [CashsOutController::class, 'monthlyReport'])->name('cashOut.monthlyReport');
});


Route::middleware(['auth', 'role.user'])->group(function () {
    Route::get('/home', [UserController::class, 'index'])->name('home');

    Route::resource('categories-owner', CategoriesController::class);
    Route::resource('cashs-owner', CashsController::class)->except(['show'])->names([
        'index' => 'cashs.index.owner',
        'create' => 'cashs.create.owner',
        'store' => 'cashs.store.owner',
        'edit' => 'cashs.edit.owner',
        'update' => 'cashs.update.owner',
        'destroy' => 'cashs.destroy.owner',
    ]);
    Route::resource('cashs-out-owner', CashsOutController::class)->except(['show'])->names([
        'index' => 'cashs-out.index.owner',
        'create' => 'cashs-out.create.owner',
        'store' => 'cashs-out.store.owner',
        'edit' => 'cashs-out.edit.owner',
        'update' => 'cashs-out.update.owner',
        'destroy' => 'cashs-out.destroy.owner',
    ]);

    Route::get('/profile-owner', [ProfileOwnerUpdateController::class, 'index'])->name('profile.index.owner');
    Route::put('/profile-owner/update', [ProfileOwnerUpdateController::class, 'update'])->name('profile.update.owner');
    // Route::resource('add-users', AddUsersController::class);
});

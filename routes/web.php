<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Contracts\Role;

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');



    Route::get('/users/{role?}', [RegisteredUserController::class, 'index'])->name('users');
    Route::get('/users/{id}/edit', [RegisteredUserController::class, 'edit'])->name('users.edit');
    Route::post('/users/{id}', [RegisteredUserController::class, 'update'])->name('users.update');
    Route::delete('/users/{id}/delete', [RegisteredUserController::class, 'destory'])->name('users.destory');
    Route::post('/users', [RegisteredUserController::class, 'store'])->name('users.store');
    Route::get('/users/search', [RegisteredUserController::class, 'search'])->name('users.search');

    Route::get('/permissions',[PermissionController::class, 'index'])->name('permissions');
    Route::post('/permissions/store', [PermissionController::class, 'store'])->name('permissions.store');
    Route::delete('/permissions/{id}', [PermissionController::class, 'destroy'])->name('permissions.destroy');
    Route::get('/permissions/{id}/edit', [PermissionController::class, 'edit'])->name('permissions.edit');
    Route::post('/permissions/{id}/update', [PermissionController::class, 'update'])->name('permissions.update');
    Route::get('/rolePermissions', [PermissionController::class, 'rolePermission'])->name('rolePermissions');
    Route::post('/updatePermission', [PermissionController::class, 'updatePermission'])->name('updatePermission');
    Route::get('/permissions/search', [PermissionController::class, 'search'])->name('permissions.search');

    Route::get('/roles', [RoleController::class, 'index'])->name('roles');
    Route::post('/roles/store', [RoleController::class, 'store'])->name('roles.store');
    Route::delete('/roles/{id}', [RoleController::class, 'destroy'])->name('roles.destroy');
    Route::get('/roles/{id}/edit', [RoleController::class, 'edit'])->name('roles.edit');
    Route::post('/roles/{id}/update',[RoleController::class, 'update'])->name('roles.update');
    Route::get('/roles/search', [RoleController::class, 'search'])->name('roles.search');


    Route::get('/accounts', [AccountController::class, 'index'])->name('accounts');
    Route::post('/accounts/store',[AccountController::class,'store'])->name('accounts.store');
    Route::get('/accounts/{id}/edit',[AccountController::class,'edit'])->name('accounts.edit');
    Route::post('/accounts/update',[AccountController::class,'update'])->name('account.update');
    Route::delete('account/{id}/delete', [AccountController::class, 'destory'])->name('accounts.destory');
    Route::get('/accounts/search',[AccountController::class,'search'])->name('accounts.search');




});

require __DIR__.'/auth.php';

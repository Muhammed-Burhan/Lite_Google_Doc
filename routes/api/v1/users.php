<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


Route::middleware([
    //'auth'
])->name('users.')->group(function () {

    Route::get('/users', [UserController::class, 'index'])->name('index')->withoutMiddleware('aut');

    Route::get('/users/{id}', [UserController::class, 'show'])->name('show')->whereNumber('id');

    Route::post('/users', [UserController::class, 'store'])->name('store');

    Route::put('/users/{user}', [UserController::class, 'update'])->name('update');

    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('destroy');
});
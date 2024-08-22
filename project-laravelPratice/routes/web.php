<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\loginController;
use App\Http\Controllers\userController;
use App\Http\Controllers\StudentrecordController;
// Get Route
Route::get('/index', function () {
    return view('index');
})->name('index');

Route::get('/register', function () {return view('signUp');})->name('register');
Route::get('/user/{id}', [userController::class, 'singleUser'])->name('view.user');
Route::get('/show', [userController::class, 'showUser'])->name('show');
Route::get('/showUser', [userController::class, 'user'])->name('showUser');


// Post Routes
Route::post('/login', [LoginController::class, 'loginUser'])->name('login');
Route::post('/add', [userController::class, 'addNewUser'])->name('addNewUser');

// 
Route::resource('studentrecord', StudentrecordController::class);

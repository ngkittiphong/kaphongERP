<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
Route::get('/', function () {
    return view('index');
});

Route::get('/user/login', function () {
    return view('user.login');
});

Route::get('/user/signin', [UserController::class, 'LogIn']);
Route::post('/user/signin_process', [UserController::class, 'signInProcess']);
Route::get('/user/register', [UserController::class, 'Register']);
Route::post('/user/user_create', [UserController::class, 'createUserProcess']);



//Route::get('/user/signOut', [UserController::class, 'signOut'])->middleware(EnsureTokenIsValid::class);
//Route::get('/user/info', [UserController::class, 'info'])->middleware(EnsureTokenIsValid::class);

//Route::get('/user/list', [UserController::class, 'list']);
//Route::get('/user/form', [UserController::class, 'form']);
//Route::get('/user/edit/{id}', [UserController::class, 'edit']);
//Route::post('/user/update/{id}', [UserController::class, 'update']);
//Route::get('/user/delete/{id}', [UserController::class, 'delete']);

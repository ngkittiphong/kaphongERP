<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return view('index');
});

Route::get('/user/login', function () {return view('user.login');});
//Route::post('/user/user_create', [UserController::class, 'store'])->name('users.store');
//Route::post('/users', [UserController::class, 'store'])->name('users.store');
Route::post('/user', [UserController::class, 'store'])->name('users.store');

Route::get('/user/signin', [UserController::class, 'LogIn']);
Route::post('/user/signin_process', [UserController::class, 'signinProcess']);
Route::get('/user/signOut', [UserController::class, 'signOut'])->middleware(EnsureTokenIsValid::class);


//Route::get('/user/register', [UserController::class, 'Register']);
//Route::post('/user/user_create', [UserController::class, 'createUserProcess']);

Route::get('/menu/menu_user', function () {
    return view('menu_user');
});

Route::match(['get','post'],'/test', function() {
    dd("Got request!");
});

Route::post('/post-message', [MessageController::class, 'store'])->name('post.message');
//Route::get('/user/info', [UserController::class, 'info'])->middleware(EnsureTokenIsValid::class);

//Route::get('/user/list', [UserController::class, 'list']);
//Route::get('/user/form', [UserController::class, 'form']);
//Route::get('/user/edit/{id}', [UserController::class, 'edit']);
//Route::post('/user/update/{id}', [UserController::class, 'update']);
//Route::get('/user/delete/{id}', [UserController::class, 'delete']);

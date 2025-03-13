<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;


//------------------Root Route--------------------------------
Route::get('/', function () {
    return view('index');
});

//------------------User Route--------------------------------
Route::get('/user/login', function () {return view('user.login');});
//Route::post('/user/user_create', [UserController::class, 'store'])->name('users.store');
//Route::post('/users', [UserController::class, 'store'])->name('users.store');
Route::post('/user', [UserController::class, 'store'])->name('users.store');

Route::get('/user/signin', [UserController::class, 'LogIn']);
Route::post('/user/signin_process', [UserController::class, 'signinProcess']);
Route::get('/user/signOut', [UserController::class, 'signOut'])->middleware(EnsureTokenIsValid::class);


//Route::get('/user/register', [UserController::class, 'Register']);
//Route::post('/user/user_create', [UserController::class, 'createUserProcess']);

//------------------Menu Route--------------------------------
Route::get('/menu/menu_user', function () {
    return view('menu.menu_user');
});

Route::get('/menu/menu_product', function () {
    return view('menu.menu_product');
});

Route::get('/menu/menu_category', function () {
    return view('menu.menu_category');
});

Route::get('/menu/menu_transfer', function () {
    return view('menu.menu_transfer');
});

Route::get('/menu/menu_warehouse', function () {
    return view('menu.menu_warehouse');
});

// Route::get('/menu/menu_report', function () {
//     return view('menu.menu_report');
// });

// Route::get('/menu/menu_setting', function () {
//     return view('menu.menu_setting');
// }); 




//------------------Test Route--------------------------------

Route::match(['get','post'],'/test', function() {
    dd("Got request!");
});

//------------------Message Route--------------------------------
Route::post('/post-message', [MessageController::class, 'store'])->name('post.message');
//Route::get('/user/info', [UserController::class, 'info'])->middleware(EnsureTokenIsValid::class);

//Route::get('/user/list', [UserController::class, 'list']);
//Route::get('/user/form', [UserController::class, 'form']);
//Route::get('/user/edit/{id}', [UserController::class, 'edit']);
//Route::post('/user/update/{id}', [UserController::class, 'update']);
//Route::get('/user/delete/{id}', [UserController::class, 'delete']);

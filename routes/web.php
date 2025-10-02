<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Middleware\EnsureTokenIsValid;
use App\Http\Middleware\AutoLogin;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\LanguageController;

//------------------Root Route--------------------------------
Route::get('/', function () {
    return view('index');
})->middleware(['auth', \App\Http\Middleware\ForcePasswordChange::class]);

//------------------User Route--------------------------------
Route::get('/user/login', function () {
    return view('user.login');
})->name('login');
Route::post('/user/signin_process', [UserController::class, 'signinProcess']);
Route::get('/user/signOut', [UserController::class, 'signOut'])->name('user.signOut')->middleware('auth');
//api
Route::post('/users/{id}/change-password', [UserController::class, 'changePassword'])->middleware('auth');

// Force password change routes
Route::get('/user/change-password', function () {
    return view('user.force_change_pwd');
})->name('user.change-password')->middleware('auth');
Route::post('/user/change-password', [UserController::class, 'forceChangePassword'])
    ->name('user.change-password.update')
    ->middleware('auth');

//unuse
//Route::post('/user/user_create', [UserController::class, 'store'])->name('users.store');
//Route::post('/users', [UserController::class, 'store'])->name('users.store');
//Route::post('/user', [UserController::class, 'store'])->name('users.store');
//Route::get('/user/signin', [UserController::class, 'LogIn']);

//Route::get('/user/register', [UserController::class, 'Register']);
//Route::post('/user/user_create', [UserController::class, 'createUserProcess']);

//------------------Menu Route--------------------------------
Route::get('/menu/menu_user', function () {
    return view('menu.menu_user');
})->middleware(['auth', \App\Http\Middleware\ForcePasswordChange::class]);

Route::get('/menu/menu_product', function () {
    return view('menu.menu_product');
})->name('menu.menu_product')->middleware(['auth', \App\Http\Middleware\ForcePasswordChange::class]);

Route::get('/menu/menu_category', function () {
    return view('menu.menu_category');
})->middleware(['auth', \App\Http\Middleware\ForcePasswordChange::class]);

Route::get('/menu/menu_transfer', function () {
    return view('menu.menu_transfer');
})->middleware(['auth', \App\Http\Middleware\ForcePasswordChange::class]);

Route::get('/menu/menu_branch', function () {
    return view('menu.menu_branch');
})->middleware(['auth', \App\Http\Middleware\ForcePasswordChange::class]);

Route::get('/menu/menu_warehouse', function () {
    return view('menu.menu_warehouse');
})->name('menu.menu_warehouse')->middleware(['auth', \App\Http\Middleware\ForcePasswordChange::class]);

Route::get('/menu/menu_warehouse_checkstock', function () {
    return view('menu.menu_warehouse_checkstock');
})->middleware(['auth', \App\Http\Middleware\ForcePasswordChange::class]);

Route::get('/menu/menu_warehouse_transfer', function () {
    return view('menu.menu_warehouse_transfer');
})->name('menu.menu_warehouse_transfer')->middleware(['auth', \App\Http\Middleware\ForcePasswordChange::class]);

Route::get('/menu/menu_warehouse_stock', function () {
    return view('menu.menu_warehouse_stock');
})->middleware(['auth', \App\Http\Middleware\ForcePasswordChange::class]);

//------------------Setting Route--------------------------------
Route::get('/setting/company_profile', [BranchController::class, 'getHeadOffice'])->name('setting.company_profile');

Route::get('/setting/delivery_note', function () {
    return view('setting.setting_delivery_note');
})->middleware(['auth', \App\Http\Middleware\ForcePasswordChange::class]);


// Route::get('/menu/menu_report', function () {
//     return view('menu.menu_report');
// });

// Route::get('/menu/menu_setting', function () {
//     return view('menu.menu_setting');
// }); 




//------------------Test Route--------------------------------
// Removed test route - no longer needed

Route::get('/test-transfer', function() {
    return view('menu.menu_warehouse_transfer');
})->middleware(['auth', \App\Http\Middleware\ForcePasswordChange::class]);

//------------------Message Route--------------------------------
// Route::post('/post-message', [MessageController::class, 'store'])->name('post.message')->middleware('auth');
//Route::get('/user/info', [UserController::class, 'info'])->middleware(EnsureTokenIsValid::class);

//Route::get('/user/list', [UserController::class, 'list']);
//Route::get('/user/form', [UserController::class, 'form']);
//Route::get('/user/edit/{id}', [UserController::class, 'edit']);
//Route::post('/user/update/{id}', [UserController::class, 'update']);
//Route::get('/user/delete/{id}', [UserController::class, 'delete']);

// Password change route (as a web route)

// Add this with your other routes
Route::post('/upload/avatar', [UserController::class, 'uploadAvatar'])->name('upload.avatar')->middleware(['auth', \App\Http\Middleware\ForcePasswordChange::class]);

Route::post('/users/update-nickname', [UserController::class, 'updateNickname'])->middleware(['auth', \App\Http\Middleware\ForcePasswordChange::class]);

Route::post('/users/change-password', [UserController::class, 'changePasswordProfile'])->middleware(['auth', \App\Http\Middleware\ForcePasswordChange::class]);

//------------------Branch Route--------------------------------
Route::resource('branches', BranchController::class);

//------------------Inventory Route--------------------------------
Route::prefix('inventory')->middleware(['auth', \App\Http\Middleware\ForcePasswordChange::class])->group(function () {
    Route::post('/stock-in', [InventoryController::class, 'stockIn'])->name('inventory.stock-in');
    Route::post('/stock-out', [InventoryController::class, 'stockOut'])->name('inventory.stock-out');
    Route::post('/stock-adjustment', [InventoryController::class, 'stockAdjustment'])->name('inventory.stock-adjustment');
    Route::post('/transfer-stock', [InventoryController::class, 'transferStock'])->name('inventory.transfer-stock');
    Route::get('/stock-balance', [InventoryController::class, 'getStockBalance'])->name('inventory.stock-balance');
    Route::get('/stock-history', [InventoryController::class, 'getStockHistory'])->name('inventory.stock-history');
    Route::get('/validate-integrity', [InventoryController::class, 'validateStockIntegrity'])->name('inventory.validate-integrity');
    Route::post('/reconcile-stock', [InventoryController::class, 'reconcileStock'])->name('inventory.reconcile-stock');
});

//------------------Language Route--------------------------------
Route::post('/language/switch', [LanguageController::class, 'switch'])->name('language.switch');
Route::get('/language/current', [LanguageController::class, 'getCurrent'])->name('language.current');

// Debug route for testing language switching
Route::get('/debug/language', function() {
    // Force set locale from session
    $sessionLocale = session('locale', 'en');
    if (in_array($sessionLocale, ['en', 'th'])) {
        app()->setLocale($sessionLocale);
    }
    
    return response()->json([
        'current_locale' => app()->getLocale(),
        'session_locale' => session('locale'),
        'test_translation' => __t('menu.main', 'Main'),
        'products_translation' => __t('menu.products', 'Products'),
        'session_id' => session()->getId(),
        'timestamp' => now()
    ]);
});

// Test route to manually set session locale
Route::get('/debug/set-locale/{locale}', function($locale) {
    if (in_array($locale, ['en', 'th'])) {
        session(['locale' => $locale]);
        app()->setLocale($locale);
    }
    
    return response()->json([
        'message' => 'Locale set to ' . $locale,
        'current_locale' => app()->getLocale(),
        'session_locale' => session('locale'),
        'test_translation' => __t('menu.main', 'Main')
    ]);
});


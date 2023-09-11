<?php
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProductController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers;
use App\Http\Controllers\MainController;
use App\Http\Controllers\BasketController;
use App\Http\Controllers\Admin\CategoryController;

Auth::routes([
    'reset' => false,
    'confirm' => false,
    'verify' => false,
]);

Route::get('/logout', function () {
    Auth::logout();
    return redirect('/');
})->name('logout');

Route::group(['middleware' => 'auth',
    'namespace' => 'Admin',

], function () {
    Route::get('/orders', [OrderController::class, 'index'])->name('home');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');

});
Route::get('/login', function () {
    if (auth()->check()) {
        return redirect()->route('home');
    } else {
        return view('auth.login');
    }
})->name('login');

Route::group([
    'middleware' => 'auth',
    'prefix' => 'admin',
], function () {
    Route::group(['middleware' => 'is_admin'], function () {
        Route::get('/orders', [OrderController::class, 'index'])->name('home');
        Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    });

    Route::resource('categories', CategoryController::class);
    Route::resource('products', ProductController::class);
});
Route::get('/', [MainController::class, 'index'])->name('index');
Route::get('/categories', [MainController::class, 'categories'])->name('categories');

Route::group(['prefix' => 'basket'], function () {
    Route::post('/add/{id}', [BasketController::class, 'basketAdd'])->name('basket-add');

    Route::group(['middleware' => 'basket_not_empty'
    ], function () {
        Route::get('/', [BasketController::class, 'basket'])->name('basket');
        Route::get('/place', [BasketController::class, 'basketPlace'])->name('basket-place');
        Route::post('/remove/{id}', [BasketController::class, 'basketRemove'])->name('basket-remove');
        Route::post('/place', [BasketController::class, 'basketConfirm'])->name('basket-confirm');
    });
});

Route::get('/{category}', [MainController::class, 'category'])->name('category');
Route::get('/{category}/{product?}', [MainController::class, 'product'])->name('product');

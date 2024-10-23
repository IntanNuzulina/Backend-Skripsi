<?php

use App\Http\Controllers\PaymentController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Route;

Route::get('/user', function () {
    return Auth::user();
})->middleware('auth:sanctum');


Route::post('/register', \App\Http\Controllers\Api\User\Create::class);
Route::post('/login', \App\Http\Controllers\Api\User\Login::class);
Route::get('/logout', \App\Http\Controllers\Api\User\Logout::class)->middleware('auth:sanctum');
Route::get('/users', \App\Http\Controllers\Api\User\GetUser::class);
Route::delete('/users/{id}', function ($id) {
    User::find($id)->delete();
    return response()->json([
        'message' => 'Berhasil menghapus user'
    ]);
})->middleware('auth:sanctum');

Route::put('/users/{id}', function (Request $request, $id) {
    $request->validate([
        'name' => 'string',
        'email' => 'email'
    ]);

    $user = User::find($id);

    $user->name = $request->name;
    $user->email = $request->email;
    $user->save();

    return response()->json([
        'message' => 'update success',
        'data' => User::find($id)
    ]);
})->middleware('auth:sanctum');

Route::group(['auth:sanctum'], function () {
    Route::post('/kategori/create', \App\Http\Controllers\Api\Kategori\Create::class);
    Route::get('/kategori/view', \App\Http\Controllers\Api\Kategori\View::class);
    Route::get('/kategori/view/{id}', [\App\Http\Controllers\Api\Kategori\View::class, 'show'])->name('category.show');
    Route::put('/kategori/update/{id}', \App\Http\Controllers\Api\Kategori\Update::class);
    Route::delete('/kategori/delete/{id}', \App\Http\Controllers\Api\Kategori\Delete::class);
});

Route::get('/buku/view', \App\Http\Controllers\Api\Buku\View::class);
Route::get('/buku/view/{id}', [\App\Http\Controllers\Api\Buku\View::class, 'show'])->name('book.show');
Route::get('/buku/search', [\App\Http\Controllers\Api\Buku\View::class, 'search'])->name('book.search');
Route::post('/buku/create', \App\Http\Controllers\Api\Buku\Create::class);
Route::put('/buku/update/{id}', \App\Http\Controllers\Api\Buku\Update::class);
Route::delete('/buku/delete/{id}', \App\Http\Controllers\Api\Buku\Delete::class);
Route::middleware(['auth:sanctum'])->group(function () {
});

Route::post('/flash-sale/create', \App\Http\Controllers\Api\Flashsale\Create::class)->middleware('auth:sanctum');
Route::get('/flash-sale/view', \App\Http\Controllers\Api\Flashsale\View::class);
Route::get('/flash-sale', [\App\Http\Controllers\Api\Flashsale\View::class, 'flashsale']);
Route::get('/flash-sale/view/{id}', [\App\Http\Controllers\Api\Flashsale\View::class, 'show'])->name('flashSale.show');
Route::put('/flash-sale/update', \App\Http\Controllers\Api\Flashsale\Update::class);
Route::delete('/flash-sale/delete/{id}', \App\Http\Controllers\Api\Flashsale\Delete::class)->middleware('auth:sanctum');

Route::group(['auth:sanctum'], function () {
    Route::get('/keranjang/view', \App\Http\Controllers\Api\Keranjang\View::class)->middleware('auth:sanctum');
    Route::post('/keranjang/create', \App\Http\Controllers\Api\Keranjang\Create::class)->middleware('auth:sanctum');
    Route::post('/keranjang/tambah', \App\Http\Controllers\Api\Keranjang\Tambah::class)->middleware('auth:sanctum');
    Route::post('/keranjang/kurang', \App\Http\Controllers\Api\Keranjang\Kurang::class)->middleware('auth:sanctum');
    Route::delete('/keranjang/delete/{id}', \App\Http\Controllers\Api\Keranjang\Delete::class)->middleware('auth:sanctum');
});

Route::group(['auth:sanctum'], function () {
    Route::post('/order/create', \App\Http\Controllers\Api\Order\Create::class)->middleware('auth:sanctum');
});

Route::get('/order/view', \App\Http\Controllers\Api\Order\View::class);
Route::get('/order/user', [App\Http\Controllers\Api\Order\View::class, 'orderUser'])->middleware('auth:sanctum');

// midtrans
Route::post('/payment/charge', [PaymentController::class, 'createCharge']);
Route::post('/payment/callback', [PaymentController::class, 'callback']);

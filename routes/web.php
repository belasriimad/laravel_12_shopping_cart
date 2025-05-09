<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;

Route::get('/', [HomeController::class,'index'])->name('home');
Route::get('cart', [CartController::class,'index'])->name('cart.index');
Route::post('add/cart', [CartController::class,'addToCart'])->name('cart.add');
Route::put('update/cart', [CartController::class,'updateCartItem'])->name('cart.update');
Route::delete('remove/cart', [CartController::class,'removeCartItem'])->name('cart.remove');
Route::delete('clear/cart', [CartController::class,'clearCart'])->name('cart.clear');
Route::get('order/pay', [OrderController::class,'payOrderByStripe'])->name('order.pay');
Route::get('success/pay', [OrderController::class,'successPaid'])->name('order.success');
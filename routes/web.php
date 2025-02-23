<?php

use App\Livewire\Admin\Products\ProductCategory;
use App\Livewire\Admin\Products\ProductList;
use App\Livewire\Admin\Products\ProductVariant;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('admin')->group(function () {
    Route::get('/dashboard', \App\Livewire\Admin\Dashboard::class)->name('admin.dashboard');
    
    // Product Management Routes
    Route::prefix('products')->group(function () {
        Route::get('/', ProductList::class)->name('admin.products.list');
        Route::get('/categories', ProductCategory::class)->name('admin.products.categories');
        Route::get('/variants', ProductVariant::class)->name('admin.products.variants');
    });

    Route::get('/articles', \App\Livewire\Admin\ArticleManagement::class)->name('admin.articles');
    Route::get('/orders', \App\Livewire\Admin\OrderManagement::class)->name('admin.orders');
});

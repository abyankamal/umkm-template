<?php

use App\Livewire\Admin\Articles\ArticleCategory;
use App\Livewire\Admin\Articles\ArticleList;
use App\Livewire\Admin\Articles\ArticleRelateProduct;
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

    Route::prefix('articles')->group(function () {
        Route::get('/', ArticleList::class)->name('admin.articles.list');
        Route::get('/categories', ArticleCategory::class)->name('admin.articles.categories');
        Route::get('/related-products', ArticleRelateProduct::class)->name('admin.articles.related-products');
    });

    Route::get('/orders', \App\Livewire\Admin\OrderManagement::class)->name('admin.orders');
});

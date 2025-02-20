<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Route::prefix('admin')->group(function () {
//     Route::get('/dashboard', \App\Livewire\Admin\Dashboard::class)->name('admin.dashboard');
    
//     // Product Management Routes
//     Route::prefix('products')->group(function () {
//         Route::get('/', \App\Http\Livewire\Admin\Products\ProductIndex::class)->name('admin.products.list');
//         Route::get('/categories', \App\Http\Livewire\Admin\Products\CategoryIndex::class)->name('admin.products.categories');
//         Route::get('/variants', \App\Http\Livewire\Admin\Products\VariantIndex::class)->name('admin.products.variants');
//     });

//     Route::get('/articles', \App\Livewire\Admin\ArticleManagement::class)->name('admin.articles');
//     Route::get('/orders', \App\Livewire\Admin\OrderManagement::class)->name('admin.orders');
// });

Route::get('/dashboard', \App\Livewire\Admin\Dashboard::class)->name('admin.dashboard');

Route::get('admin/articles', \App\Livewire\Admin\ArticleManagement::class)->name('admin.articles');
Route::get('admin/orders', \App\Livewire\Admin\OrderManagement::class)->name('admin.orders');

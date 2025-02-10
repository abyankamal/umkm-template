<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/admin/dashboard', \App\Livewire\Admin\Dashboard::class)->name('admin.dashboard');
Route::get('/admin/categories', \App\Livewire\Admin\CategoryManagement::class)->name('admin.categories');
Route::get('/admin/products', \App\Livewire\Admin\ProductManagement::class)->name('admin.products');

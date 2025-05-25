<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AddminMiddleware;
use App\Http\Middleware\LocalizationMiddleware;

// Admin routes
Route::post('login', [AdminController::class, 'login']);

// Products routes
Route::post('/products/insert', [ProductController::class, 'InsertNewProduct']);
Route::post('/products/update', [ProductController::class, 'UpdateProduct']);
Route::delete('/products/delete', [ProductController::class, 'destroy']);
Route::get('/mainpage/products', [ProductController::class, 'get_mainPage_products']);
Route::get('/category/products/{id}', [CategoryController::class, 'get_products_by_categroy']);
Route::post('/product/like/add',[ProductController::class,'Add_like']);
Route::post('/product/like/remove',[ProductController::class,'Remove_like']);

// Category routes
Route::post('/categories/insert', [CategoryController::class, 'InsertNewCategory']);
Route::post('/categories/update', [CategoryController::class, 'UpdateCategory']);
Route::delete('/categories/delete', [CategoryController::class, 'destroy']);

// Localized routes

    Route::get('/categories/ViewAllCategory', [CategoryController::class, 'ViewAllCategory']);
    Route::get('/products/ViewAllProducts', [ProductController::class, 'ViewAllProducts']);
    Route::get('/categories/show', [CategoryController::class, 'show']);
    Route::get('/products/show', [ProductController::class, 'show']);


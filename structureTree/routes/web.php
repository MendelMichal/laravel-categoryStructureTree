<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [App\Http\Controllers\MainPageController::class, 'index'])->name('mainPage');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

/* Categories Tree Actions */
Route::post('/add-category', [App\Http\Controllers\CategoryController::class, 'addCategory'])->name('addCategories');
Route::get('/add-category', [App\Http\Controllers\CategoryController::class, 'listCategories']);

Route::post('/edit-category', [App\Http\Controllers\CategoryController::class, 'editCategory'])->name('editCategories');
Route::get('/edit-category', [App\Http\Controllers\CategoryController::class, 'listCategories']);

Route::post('/delete-category', [App\Http\Controllers\CategoryController::class, 'deleteCategory'])->name('deleteCategories');
Route::get('/delete-category', [App\Http\Controllers\CategoryController::class, 'listCategories']);

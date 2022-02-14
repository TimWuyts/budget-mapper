<?php

use App\Http\Controllers\AppController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\FileController;
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

Route::controller(AppController::class)->group(function() {
    Route::get('/', 'app');
});

Route::controller(CategoryController::class)->prefix('category')->name('category.')->group(function() {
    Route::post('/add', 'create')->name('add');
    Route::put('/edit/{id}', 'update')->name('edit');
    Route::delete('/delete/{id}', 'delete')->name('delete');
});

Route::controller(FileController::class)->prefix('file')->name('file.')->group(function() {
    Route::post('/import', 'import')->name('import');
    Route::get('/export', 'export')->name('export');
});

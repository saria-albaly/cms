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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::name("admin.")->prefix("admin")->middleware(['auth', 'verified'])->group(function () {
    Route::resource('articles', App\Http\Controllers\admin\articleController::class)->except([
        'index', 'show'
    ]);
});

Route::name("admin.")->prefix("admin")->group(function () {
    Route::resource('articles', App\Http\Controllers\admin\articleController::class)->only([
        'index', 'show'
    ]);
});
require __DIR__.'/auth.php';

<?php

use App\Http\Controllers\HomeController;
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

Route::get('/', [HomeController::class, 'home'])->name("home");
Route::get('/test', [HomeController::class, 'test']);

Route::get('login', [App\Http\Controllers\DiscordController::class, 'login'])
  ->name('login')->withoutMiddleware('VerifyCsrfToken');
Route::post('logout', [App\Http\Controllers\DiscordController::class, 'logout'])
  ->name('logout');

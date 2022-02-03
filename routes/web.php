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

// auth bs
Route::get('tologin', [App\Http\Controllers\DiscordController::class, 'tologin'])
  ->name('login');
Route::get('login', [App\Http\Controllers\DiscordController::class, 'loginCallback'])
  ->withoutMiddleware('VerifyCsrfToken');
Route::get('logout', [App\Http\Controllers\DiscordController::class, 'logout'])
  ->name('logout');

Route::get("/activities", [App\Http\Controllers\ActivityController::class, "index"])
  ->name("activities.index");
Route::get("/activities/{activity}", [App\Http\Controllers\ActivityController::class, "show"])
  ->name("activities.show");

<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\DiscordController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TeamController;
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
Route::get('tologin', [DiscordController::class, 'tologin'])
  ->name('login');
Route::get('login', [DiscordController::class, 'loginCallback'])
  ->withoutMiddleware('VerifyCsrfToken');
Route::get('logout', [DiscordController::class, 'logout'])
  ->name('logout');

// Activities
Route::get("/activities", [ActivityController::class, "index"])
  ->name("activities.index");
Route::get("/activities/{activity}", [ActivityController::class, "show"])
  ->name("activities.show");
Route::post("/activities/{activity}/complete", [ActivityController::class, "complete"])
  ->name("activities.complete");

// Teams
Route::get("/teams", [TeamController::class, "index"])
  ->name("teams.index");

// About
Route::get("/about", [HomeController::class, "about"])
  ->name("about");

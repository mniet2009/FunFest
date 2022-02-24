<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\DiscordController;
use App\Http\Controllers\EntryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\UserController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
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
Route::post('logout', [DiscordController::class, 'logout'])
  ->name('logout');

// User
Route::get('/signup', [UserController::class, 'signupForm'])->name("signUpForm");
Route::post('/signup', [UserController::class, 'signup'])->name("signUp");
Route::get('/users/{user}', [UserController::class, 'show'])->name("users.show");

// Activities
Route::get("/activities", [ActivityController::class, "index"])
  ->name("activities.index");
Route::get("/activities/{activity}", [ActivityController::class, "show"])
  ->name("activities.show");
Route::post("/activities/{activity}/complete", [ActivityController::class, "complete"])
  ->name("activities.complete");

Route::get("/schedule", [ActivityController::class, "schedule"])
  ->name("activities.schedule");

Route::post("/activities/{activity}/entry", [EntryController::class, "store"])
  ->name("entries.store");

// Teams
Route::get("/teams", [TeamController::class, "index"])
  ->name("teams.index");

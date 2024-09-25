<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Web\WebController;
use App\Http\Controllers\OpenTelemetryController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Auth::routes();

Route::get('/', [WebController::class, 'index']);
Route::get('/web/user-list', [WebController::class, 'webUserList']);
Route::get('/zipkin', [OpenTelemetryController::class, 'zipkinIndex']);
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KeycloakController;
use App\Http\Controllers\Api\KeywordController;

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
Route::get('auth/redirect', [KeycloakController::class, 'redirect'])->name('login');
Route::get('auth/callback', [KeycloakController::class, 'callback']);

Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        return view('welcome');
    });
    Route::get('/random', function(Request $request) {
        dump($request->user());
        return 'Hello';
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/keywords', [KeywordController::class, 'index']);
});

Route::get('/not', function (Request $request) {
    dump($request->user());
    return;
});

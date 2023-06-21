<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KeycloakController;
use App\Http\Controllers\Api\KeywordController;
use App\Http\Controllers\Api\QuizController;
use App\Http\Controllers\Api\ActivityController;
use App\Http\Controllers\Api\RosterController;
use App\Models\Activity;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::get('auth/redirect', [KeycloakController::class, 'redirect'])->name('login');
Route::get('auth/callback', [KeycloakController::class, 'callback']);
Route::get('login', [KeycloakController::class, 'login']);
Route::get('after', [KeycloakController::class, 'afterLogout']);

Route::middleware('auth')->group(function () {
    Route::get('/keywords', [KeywordController::class, 'index']);
    Route::get('/user', function (Request $request) {
        $name = $request->user()->getFullName();
        return response()->json([
            'name' => $name,
        ]);
    });
    Route::get('/quizzes', [QuizController::class, 'index']);

    Route::get('/activities', [ActivityController::class, 'index']);

    Route::get('/rosters', [RosterController::class, 'index']);

    Route::get('logout',[KeycloakController::class, 'logout']); 
});

Route::get('/', function () {
    return response()->json([
        'message' => 'Welcome to our API',
    ]);
});

Route::get('/keys', [KeywordController::class, 'index']);
// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


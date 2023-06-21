<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KeycloakController;
use App\Http\Controllers\Api\KeywordController;
use App\Http\Controllers\Api\QuizController;
use App\Http\Controllers\Api\ActivityController;
use App\Http\Controllers\Api\RosterController;
use App\Http\Controllers\Api\UserController;
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

// Routes concernant le login sans être authentifié
Route::get('auth/redirect', [KeycloakController::class, 'redirect'])->name('login');
Route::get('auth/callback', [KeycloakController::class, 'callback']);
Route::get('login', [KeycloakController::class, 'login']);
Route::get('after', [KeycloakController::class, 'afterLogout']);

// Authentification
Route::middleware('auth')->group(function () {
    Route::get('/keywords', [KeywordController::class, 'index']);

    // User
    Route::get('/user', function (Request $request) {
        $name = $request->user()->getFullName();
        return response()->json([
            'name' => $name,
            'role' => $request->user()->affiliation,
        ]);
    });
    Route::get('/users', [UserController::class, 'index']);

    Route::get('/quizzes', [QuizController::class, 'index']);

    Route::get('/activities', [ActivityController::class, 'index']);

    // Roster
    Route::get('/rosters', [RosterController::class, 'index']);
    Route::get('/rosters/{id}', [RosterController::class, 'show']);
    Route::get('/rosters/{id}/students', [RosterController::class, 'students']);

    // Logout
    Route::get('logout',[KeycloakController::class, 'logout']); 
});

Route::get('/', function () {
    return response()->json([
        'message' => 'Api de l\'application Quiz',
    ]);
});

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


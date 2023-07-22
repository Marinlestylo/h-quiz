<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KeycloakController;
use App\Http\Controllers\Api\KeywordController;
use App\Http\Controllers\Api\QuizController;
use App\Http\Controllers\Api\ActivityController;
use App\Http\Controllers\Api\RosterController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\StudentController;
use App\Http\Controllers\Api\CourseController;
use App\Http\Controllers\Api\QuestionController;
use App\Http\Controllers\Api\DrillController;

use App\Models\Activity;
use App\Models\Course;
use App\Models\Student;

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

// TODO: remove
Route::get('debug/login/{id}', function ($id) {
    Auth::loginUsingId($id);
    return redirect('http://localhost:5173/');
});

// TODO : mettre dans userController
Route::get('/user', function (Request $request) {
    // die($request->user());
    $name = '';
    $affiliation = '';
    $id = null;
    
    if ($request->user()){
        $name = $request->user()->getFullName();
        $affiliation = $request->user()->affiliation;
        $id = $request->user()->id;
    }
    return response()->json([
        'name' => $name,
        'role' => $affiliation,
        'id' => $id,
    ]);
});

Route::get('debug/logout', function () {
    Auth::logout();
});

// Route::get('/test-code', [ActivityController::class, 'compilation']);

Route::middleware('auth')->group(function () {
     // Logout
     Route::get('logout',[KeycloakController::class, 'logout']);
     Route::post('/test-code', [ActivityController::class, 'compilation']);
     Route::get('/activities/{id}', [ActivityController::class, 'show']);

     // Keyword
    Route::get('/keywords', [KeywordController::class, 'index']);
});

// Authentification
Route::middleware('checkUserRole:teacher')->group(function () {
    // User
    // TODO : mettre dans userController
    Route::get('/users', [UserController::class, 'index']);
    
    // Student
    Route::get('/students', [StudentController::class, 'index']);

    // Quiz
    Route::get('/quizzes', [QuizController::class, 'index']);
    Route::get('/quizzes/{id}', [QuizController::class, 'show']);
    Route::get('/quizzes/{id}/questions', [QuizController::class, 'questions']);
    Route::get('/quizzes-types', [QuizController::class, 'getTypes']);
    Route::post('/quizzes', [QuizController::class, 'create']);
    Route::post('/quizzes/question', [QuizController::class, 'addQuestion']);
    Route::delete('quizzes/question', [QuizController::class, 'deleteQuestion']);

    // Question
    Route::get('/questions', [QuestionController::class, 'index']);
    Route::get('/questions/{id}', [QuestionController::class, 'show'])->where('id', '[0-9]+');
    Route::get('/questions/{keyword}', [QuestionController::class, 'getQuestions']);
    Route::get('/questions-types', [QuestionController::class, 'getTypes']);
    Route::get('/questions-difficulties', [QuestionController::class, 'getDifficulties']);
    Route::post('/questions', [QuestionController::class, 'create']);
    Route::patch('/questions', [QuestionController::class, 'edit']);

    // Activity
    Route::get('/activities', [ActivityController::class, 'index']);
    Route::get('/activities/{id}/results', [ActivityController::class, 'results']);
    Route::get('/activities/{id}/questions', [ActivityController::class, 'questions']);
    Route::post('/activities', [ActivityController::class, 'create']);
    Route::delete('/activities/{id}', [ActivityController::class, 'delete']);
    // Route to start, open, close, hide and show an activity
    Route::patch('/activities/{id}', [ActivityController::class, 'edit']);

    // Course
    Route::get('/courses', [CourseController::class, 'index']);
    Route::get('/courses/{id}', [CourseController::class, 'show']);

    // Roster
    Route::get('/rosters', [RosterController::class, 'index']);
    Route::get('/rosters/{id}', [RosterController::class, 'show']);
    Route::get('/rosters/{id}/students', [RosterController::class, 'students']);
    Route::post('/rosters', [RosterController::class, 'create']);
    //TODO changer les routes pour les rendre plus REST
    Route::post('/rosters/add-student', [RosterController::class, 'addStudent']);
    Route::delete('/rosters/delete-student', [RosterController::class, 'deleteStudent']);
});

Route::middleware('checkUserRole:student')->group(function () {
    Route::get('/user/activities/', [ActivityController::class, 'owned']);
    Route::get('/activities/{id}/questions/{question_id}', [ActivityController::class,'question']);
    Route::post('/activities/{id}/questions/{question_id}', [ActivityController::class,'answer']);
    Route::post('/activities/{id}/finish', [ActivityController::class,'studentFinish']);

    // Drill
    Route::get('/drills/{keyword}', [DrillController::class, 'makeDrill']);
    Route::post('/drills/answer', [DrillController::class, 'answerDrill']);
});

Route::get('/', function () {
    return response()->json([
        'message' => 'Api de l\'application Quiz',
    ]);
});


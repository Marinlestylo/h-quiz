<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\User;

use Illuminate\Http\Request;

class QuizController extends Controller
{
    //
    function index() {
        $quizzes = Quiz::all();
        //get number of questions
        foreach ($quizzes as $quiz) {
            $quiz->question_count = $quiz->questions()->count();
        }

        // transform the user_id from the quiz to the user's full name
        foreach ($quizzes as $quiz) {
            $quiz->owner = $quiz->owner;
        }

        return [
            'count' => count($quizzes),
            'quizzes' => $quizzes,
        ];
    }
}

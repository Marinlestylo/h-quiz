<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\User;

use Auth;
use Log;

use Illuminate\Http\Request;

class QuizController extends Controller
{
    //TODO changer l'index
    function index()
    {
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

    function show($id) {
        $quiz = Quiz::withCount('questions')->find($id);
        $quiz['questions'] = url("/api/quizzes/{$quiz['id']}/questions");
        $quiz['activities'] = url("/api/quizzes/{$quiz['id']}/activities");

        $quiz['@create_activity'] = url("/api/activities/create");
        return $quiz;
    }

    function questions($id) {
        $questions = Quiz::find($id)->questions()->get()->each(function ($item, $key) {
        });
        return [
            'count' => count($questions),
            'quiz_id' => $id,
            'questions' => $questions
        ];
    }

    function create(Request $request)
    {
        //TODO : middleware ?
        // if( !Auth::user()->isTeacher() ){
        //     return response([
        //         'message' => "Only the teacher can create a quiz",
        //         'error' => "Bad Request"
        //     ], 400);
        // };

        Log::debug('Create quiz');
        $data = $request->all();
        $q = new Quiz();
        $q->fill($data);
        $q->user_id = Auth::id();

        $q->save();

        return response([
            'message' => 'Quiz created',
            'quiz' => $q->name
        ], 200);
    }
}

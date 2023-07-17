<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\User;
use App\Models\Question;

use App\Transformers\QuizTransformer;
use Auth;
use Log;

use Illuminate\Http\Request;

class QuizController extends Controller
{
    //TODO changer l'index
    // function index()
    // {
    //     $quizzes = Quiz::all();
    //     //get number of questions
    //     foreach ($quizzes as $quiz) {
    //         $quiz->question_count = $quiz->questions()->count();
    //     }

    //     // transform the user_id from the quiz to the user's full name
    //     foreach ($quizzes as $quiz) {
    //         $quiz->owner = $quiz->owner;
    //     }
        
    //     return [
    //         'count' => count($quizzes),
    //         'quizzes' => $quizzes,
    //     ];
    // }
    function index(Request $request) {
        if ($request->owned)
            $quiz = Quiz::where('user_id', Auth::id())->get();
        else
            $quiz = Quiz::all();

        return fractal($quiz, new QuizTransformer())->toArray();
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

    function addQuestion(Request $request) {
        Log::debug('Add question to quiz');

        $quiz = Quiz::findOrFail($request->quiz_id);
        if ($quiz->user_id != Auth::id()) {
            return response([
                'message' => "Seul le créateur du quiz peut le modifier",
                'error' => "Bad Request"
            ], 400);
        }

        // Check if question is already in quiz
        $questions = $quiz->questions;
        foreach ($questions as $question) {
            if ($question->id == $request->question_id) {
                return response([
                    'message' => "La question fait déjà partie du quiz",
                    'error' => "Bad Request"
                ], 400);
            }
        }

        $question = Question::findOrFail($request->question_id);

        $quiz->questions()->attach($question->id);

        $questions = Quiz::findOrFail($request->quiz_id)->questions;
        $quiz = fractal($quiz, new QuizTransformer())->toArray();
        return response([
            'message' => 'Question added',
            'quiz' => $quiz,
            'questions' => $questions
        ], 200);
    }

    function deleteQuestion(Request $request) {
        Log::debug('Delete question to quiz');

        $quiz = Quiz::findOrFail($request->quiz_id);
        if ($quiz->user_id != Auth::id()) {
            return response([
                'message' => "Seul le créateur du quiz peut le modifier",
                'error' => "Bad Request"
            ], 400);
        }

        $question = Question::findOrFail($request->question_id);

        $quiz->questions()->detach($question->id);

        $questions = Quiz::findOrFail($request->quiz_id)->questions;
        $quiz = fractal($quiz, new QuizTransformer())->toArray();
        return response([
            'message' => 'Question deleted',
            'quiz' => $quiz,
            'questions' => $questions
        ], 200);
    }

    function getTypes() {
        return getAllPossibleValuesFromEnum('quizzes', 'type');
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Question;

use Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class QuestionController extends Controller
{
    function index(Request $request)
    {
        // TODO : middleware ?
        // if (!$request->user()->isTeacher()) abort(403);

        $questions = Question::with('keywords');

        return fractal($questions->get(), new QuestionTransformer())->toArray();
    }

    function getQuestions(Request $request, $keyword)
    {
        $i = Auth::id();

        // TODO : middleware ?
        // if (!$request->user()->isTeacher()){
        //     return response([
        //         'message' => "Only teacher can get questions",
        //         'error' => "Bad Request"
        //     ], 400);
        // }

        if ($keyword == "all") {
            $q = Question::with('keywords')->get();
        } else {
            $q = Question::whereHas('keywords', function ($query) use ($keyword) {
                return $query->where('name', 'like', $keyword);
            })->get();
        }

        return $q;
    }

    function getTypes()
    {
        return $this->getAllValuesFromEnum('questions', 'type');
    }

    function getDifficulties()
    {
        return $this->getAllValuesFromEnum('questions', 'difficulty');
    }

    private static function getAllValuesFromEnum($table, $field)
    {
        $type = DB::select("SHOW COLUMNS FROM {$table} WHERE Field = '{$field}'")[0]->Type;
        preg_match("/^enum\(\'(.*)\'\)$/", $type, $matches);
        $enum = explode("','", $matches[1]);
        return $enum;
    }

    function create(Request $request)
    {
        Log::debug('Create question');
        $data = $request->all();
        $q = new Question();
        $q->fill($data);

        $q->save();
        return response([
            'message' => 'Question created',
            'roster' => $q
        ], 200);
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Question;
use App\Models\Activity;
use App\Models\Keyword;

use Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use App\Transformers\QuestionTransformer;

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

    function show($id){
        $q = Question::with('keywords')->find($id);
        return $q == null ? [] : $q;
    }

    function getTypes()
    {
        return getAllPossibleValuesFromEnum('questions', 'type');
    }

    function getDifficulties()
    {
        return getAllPossibleValuesFromEnum('questions', 'difficulty');
    }

    function create(Request $request)
    {
        Log::debug('Create question');
        $data = $request->all();
        $q = new Question();
        $q->fill($data);
        $q->save();

        // Add keywords
        if(array_key_exists('keywords', $data) && $data['keywords'])
        {
            foreach($data['keywords'] as $k)
            {
                $q->keywords()->attach($k['id']);
            }
        }
        return response([
            'message' => 'La question a bien été créée.',
        ], 201);
    }

    function edit(Request $request){
        Log::debug('Edit question');
        $data = $request->all();
        $q = Question::findOrFail($data['id']);

        if ($q->user_id != Auth::id()){
            return response([
                'message' => "Seul le créateur de la question peut la modifier.",
                'error' => "Bad Request"
            ], 400);
        }

        $q->fill($data);
        $q->save();

        // Delete keywords that are not in the new list
        foreach($q->keywords as $qk)
        {
            $exist = false;
            if(array_key_exists('keywords', $data) && $data['keywords'])
            {
                foreach($data['keywords'] as $dk)
                {
                    if( $dk['id'] == $qk['id'])
                    {
                        $exist = true;
                        break;
                    }
                }
            }
            if(!$exist)
            {
                $q->keywords()->detach($qk['id']);
            }
        }

        if(array_key_exists('keywords', $data) && $data['keywords'])
        {
            foreach($data['keywords'] as $k)
            {
                $nbr = Question::where('id', $q['id'])
                        ->whereHas('keywords', function($query) use ($k) {
		                    $query->where('keywords.id', $k['id']);
	                    })->count();

                if($nbr == 0){
                    $q->keywords()->attach($k['id']);
                }
            }
        }


        return response([
            'message' => 'La question a bien été modifiée.',
        ], 201);
    }
}

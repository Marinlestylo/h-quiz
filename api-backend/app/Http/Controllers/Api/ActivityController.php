<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Activity;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\Roster;
use App\Models\Answer;
use App\Models\Quiz;
use Validator;
use Arr;
use Auth;
use Log;

use App\Transformers\ActivityTransformer;
use App\Transformers\QuestionTransformer;

class ActivityController extends Controller
{
    //
    function index(Request $request)
    {
        $activities = Activity::query();

        if ($request->user()->isStudent())
            $activities = $request->user()->student->activities()->where('hidden', false);

        if ($request->user()->isTeacher() && $request->owned)
            $activities = $request->user()->activities();

        if (($roster_id = $request->input('roster_id')))
            $activities->where('roster_id', $roster_id);

        return fractal(
            $activities->orderBy('updated_at', 'desc')->get(), new ActivityTransformer)->toArray();
    }

    function create(Request $request)
    {
        Log::debug('Create Activity Request');
        $validator = Validator::make($request->all(), [
            'duration' => 'required|integer|min:60',
            'quiz_id' => 'required|exists:quizzes,id',
            'roster_id' => 'required|exists:rosters,id',
            'shuffle_questions' => 'boolean',
            'shuffle_propositions' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response([
                'message' => 'Les champs ne sont pas valides',
                'details' => $validator->messages(),
                'error' => 'Bad Request',
            ], 400);
        }

        if (Roster::findOrFail($request->input('roster_id'))->teacher_id != Auth::id()) {
            return response([
                'message' => "Seulement le professeur peut créer une activité pour cette class",
                'error' => "Bad Request"
            ], 400);
        }

        $quiz = Quiz::findOrFail($request->quiz_id);

        $activity = Activity::create([
            'user_id' => Auth::id(),
            'roster_id' => $request->roster_id,
            'quiz_id' => $quiz->id,
            'duration' => $request->input('duration', 600),
            'shuffle_questions' => $request->input('shuffle_questions', false),
            'shuffle_propositions' => $request->input('shuffle_propositions', false),
            'seed' => $request->input('seed', random_int(0, 4294967295))
        ]);

        return response([
            'message' => 'New activity created',
            'activity' => $activity,
        ], 200);
    }
}

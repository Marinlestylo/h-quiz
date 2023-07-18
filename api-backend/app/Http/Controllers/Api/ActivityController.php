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
            $activities->orderBy('updated_at', 'desc')->get(),
            new ActivityTransformer
        )->toArray();
    }

    function owned() {
        $request = request();
        $request->owned = true;

        return $this->index($request);
    }

    function show($id) {
        $activity = Activity::findOrFail($id);
        if ($activity->hidden) {
            return response([
                'message' => "Vous n'êtes pas autorisé à voir cette activité.",
                'error' => "Unauthorized"
            ], 403);
        }

        $request = request();
        $request->owned = true;
        $activities = $this->index($request);
        $current = Arr::first($activities['data'], function ($value, $key) use ($id) {
            return $value['id'] == $id;
        });
        if ($current == null){
            return response([
                'message' => "Vous n'êtes pas autorisé à voir cette activité.",
                'error' => "Unauthorized"
            ], 403);
        }

        return fractal($activity, new ActivityTransformer)->toArray();
    }

    /**
     * Questions
     */
    public function questions($activity_id) {
        $activity = Activity::findOrFail($activity_id);
        return fractal(
            $activity->questions(),
            new QuestionTransformer($activity))->toArray();
    }

    /**
     * Question (get or post question)
     */
    public function question($activity_id, $question_number, Request $request) {
        $activity = Activity::findOrFail($activity_id);
        $question = $activity->questions()[$question_number - 1];

        // Submit an answer?
        if ($request->isMethod('post') && $activity->status == 'running') {
            $answered = $request->answer;
            Answer::updateOrCreate(
                [
                    'activity_id' => $activity_id,
                    'student_id' => $request->user()->student->id,
                    'question_id' => $question->id,
                ],
                [
                    'answer' => $answered,
                    'is_correct' => $question->validate($answered)
                ]
            );
        }

        return fractal(
            $question,
            new QuestionTransformer($activity))->toArray();
    }

    function edit($id, Request $request)
    {
        $activity = Activity::findOrFail($id);

        if ($activity->user_id != Auth::id()) {
            return response([
                'message' => "Seulement le créateur de l'activité peut la modifier.",
                'error' => "Unauthorized"
            ], 403);
        }

        $action = $request->input('action');
        $message = '';

        switch ($action) {
            case 'play':
                if ($activity->hidden) {
                    return response([
                        'message' => "Vous ne pouvez pas démarrer une activité cachée.",
                        'error' => "Bad Request"
                    ], 400);
                }

                if ($activity->completed) {
                    return response([
                        'message' => "Vous ne pouvez pas démarrer une activité terminée.",
                        'error' => "Bad Request"
                    ], 400);
                }

                if ($activity->started) {
                    return response([
                        'message' => "Vous ne pouvez pas démarrer une activité qui a déjà été démarrée.",
                        'error' => "Bad Request"
                    ], 400);
                }

                $activity->started_at = Carbon::now();
                $activity->save();
                $message = 'L\'activité a été démarrée.';

                break;
            case 'hide':
                if ($activity->status != 'finished') {
                    return response([
                        'message' => "Vous ne pouvez pas cacher une activité qui n'est pas terminée.",
                        'error' => "Bad Request"
                    ], 400);
                }

                if ($activity->hidden) {
                    return response([
                        'message' => "Vous ne pouvez pas cacher une activité qui est déjà cachée.",
                        'error' => "Bad Request"
                    ], 400);
                }
                $activity->update(['hidden' => true]);
                $activity->save();
                $message = 'L\'activité a été cachée.';
                break;
            case 'show':
                if ($activity->status != 'finished') {
                    return response([
                        'message' => "Vous ne pouvez pas rendre visible une activité qui n'est pas terminée.",
                        'error' => "Bad Request"
                    ], 400);
                }
                if (!$activity->hidden) {
                    return response([
                        'message' => "L'activité est déjà visible.",
                        'error' => "Bad Request"
                    ], 400);
                }

                $activity->update(['hidden' => false]);
                $activity->save();
                $message = 'L\'activité a été rendue visible.';
                break;
            case 'open':
                if ($activity->hidden) {
                    return response([
                        'message' => "Vous ne pouvez pas ouvrir une activité cachée.",
                        'error' => "Bad Request"
                    ], 400);
                }

                if ($activity->status != 'idle') {
                    return response([
                        'message' => "Vous ne pouvez pas ouvrir cette activité.",
                        'error' => "Bad Request"
                    ], 400);
                }

                $activity->update(['opened_at' => Carbon::now()]);
                $activity->save();
                $message = 'L\'activité a été ouverte.';
                break;
            case 'close':
                if ($activity->status != 'opened') {
                    return response([
                        'message' => "Vous ne pouvez fermer que des activités ouvertes.",
                        'error' => "Bad Request"
                    ], 400);
                }

                $activity->update(['opened_at' => null]);
                $activity->save();
                $message = 'L\'activité a été fermée';
                break;
        }
        return response([
            'message' => $message,
        ], 200);
    }

    function delete($id)
    {
        $activity = Activity::findOrFail($id);

        if ($activity->user_id != Auth::id()) {
            return response([
                'message' => "Seulement le professeur qui a créé l'activité peut la supprimer.",
                'error' => "Unauthorized"
            ], 403);
        }

        if ($activity->status != 'idle') {
            return response([
                'message' => "Vous ne pouvez pas supprimer une activité qui est déjà ouverte.",
                'error' => "Bad Request"
            ], 400);
        }

        $activity->delete();
        return response([
            'message' => 'L\'activité a été supprimée.'
        ], 200);
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

        if ($quiz->type == 'exam' && $quiz->user_id != Auth::id()) {
            return response([
                'message' => "Vous ne pouvez pas utiliser cet examen",
                'error' => "Bad Request"
            ], 400);
        }


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

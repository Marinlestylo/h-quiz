<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\Activity;
use Auth;

class ActivityTransformer extends TransformerAbstract
{
    protected $details = false;

    public function __construct($details = false)
    {
        $this->details = $details;
    }

	public function transform(Activity $activity)
	{
        $user = Auth::user();

        /**
         * Common attributes
         */
	    $data = [
            'id'         => (int)$activity->id,
            'user_id'    => $activity->user_id,
            'duration'   => $activity->duration,
            'completed'  => $activity->completed,
            'hidden'    => (int) $activity->hidden,
            'status' => $activity->status,
            'quiz' => [
                'id' => $activity->quiz->id,
                'name' => $activity->quiz->name,
                'questions' => $activity->quiz->questions_count,
                'keywords' => $activity->quiz->keywords
            ],
            'roster' => fractal($activity->roster, new RosterTransformer())->toArray(),

            'updated_at' => $activity->updated_at,
            'created_at' => $activity->created_at,
            'started_at' => $activity->started_at,
            'ended_at' => $activity->ended_at,
        ];

        /**
         * Teacher's only attributes
         */
        if ($user->isTeacher()) {
            $data['shuffle_questions'] = $activity->shuffle_questions;
            $data['shuffle_propositions'] = $activity->shuffle_propositions;

            if ($activity->status == 'finished')
            {
                if ($activity->hidden)
                    $data['@show_url'] = url("/api/activities/{$activity['id']}/show");
                else
                    $data['@hide_url'] = url("/api/activities/{$activity['id']}/hide");
            }

            if ($activity->status == 'idle')
                $data['@open_url'] = url("/api/activities/{$activity['id']}/open");

            if ($activity->status == 'opened')
                $data['@start_url'] = url("/api/activities/{$activity['id']}/start");
        }

        if ($user->isStudent()) {
            if ($activity->status == 'finished') {
                $data['mark'] = $activity->getRank($user->student->id);
            }
        }

        /**
         * Navigation urls
         */
        $data['quiz_url'] = url("/api/quizzes/{$activity['quiz_id']}");
        $data['roster_url'] = url("/api/rosters/{$activity['roster_id']}");
        $data['questions_url'] = url("/api/activities/{$activity['id']}/questions");

        return $data;
	}
}

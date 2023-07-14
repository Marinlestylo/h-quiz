<?php

namespace App\Transformers;

use League\Fractal;
use App\Models\Quiz;

class QuizTransformer extends Fractal\TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Quiz $quiz)
	{
	    return [
            'id'         => (int)$quiz->id,
            'name'       => $quiz->name,
            'questions' => $quiz->questions_count,
            'difficulty' => $quiz->difficulty,
            'taken_times' => $quiz->taken,
            'owner' => [
                'id' => $quiz->owner->id,
                'name' => $quiz->owner->getFullName(),
            ],
            'keywords' => $quiz->keywords,
            'created_at' => $quiz->created_at->timestamp,
            'updated_at' => $quiz->updated_at->timestamp,

            // Links
            'quiz_url' => url("api/quizzes/$quiz->id"),
            'questions_url' => url("api/quizzes/$quiz->id/questions"),
            'activities_url' => url("api/quizzes/$quiz->id/activities"),
	    ];
	}
}

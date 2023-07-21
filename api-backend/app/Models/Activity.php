<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Auth;
use Illuminate\Support\Facades\Log;

class Activity extends Model
{
    use HasFactory;

    protected $dates = ['started_at', 'ended_at'];

    protected $guarded = [
        'id'
    ];

    protected $appends = [
        'status',    // Current activity status
        'elapsed',   // Number of seconds elapsed since the beginning
        'started',   // The activity has started and isn't yet finished
        'completed'  // The activity is finished
    ];

    function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    function teacher()
    {
        return $this->belongsTo(User::class);
    }

    function roster()
    {
        return $this->belongsTo(Roster::class);
    }

    function students()
    {
        return $this->belongsToMany(Student::class);
    }

    function answers()
    {
        return $this->hasMany(Answer::class);
    }

    function getOwnedAnswers()
    {
        $user = Auth::user();
        if ($user->isTeacher()) {
            return null;
        };
        return $this->answers->where('student_id', $user->student->id);
    }

    function getElapsedAttribute()
    {
        if (!$this->started_at)
            return 0;

        $elapsed = Carbon::now()->diffInSeconds($this->started_at);
        if ($elapsed < 0) {
            Log::critical("Negative time difference detected in activity {$this->id}");
            return $elapsed;
        }

        return min($elapsed, $this->duration);
    }

    function getStartedAttribute()
    {
        return $this->elapsed > 0 && $this->elapsed < $this->duration;
    }

    function getCompletedAttribute()
    {
        return $this->elapsed >= $this->duration;
    }

    function getStatusAttribute()
    {
        if ($this->finished_at || $this->elapsed >= $this->duration)
            return 'finished';
        if ($this->started_at)
            return 'running';
        if ($this->opened_at)
            return 'opened';
        return 'idle';
    }

    /**
     * For the current activity, returns the question order in
     * which they will be generated for the given student_id.
     */
    protected function getQuestionsOrder()
    {
        if (!$this->shuffle_questions) {
            $count = $this->quiz->questions_count;
            return range(0, $count - 1);
        }

        $student_id = Auth::user()->student->id;

        $hash = hash('sha1', "$this->id $this->quiz_id $student_id");
        $seed = unpack("L", substr($hash, 0, 4))[1];
        $count = $this->quiz->questions_count;

        mt_srand($seed, MT_RAND_MT19937);

        $array = range(0, $count - 1);
        for ($i = 0; $i < $count; ++$i) {
            list($chunk) = array_splice($array, mt_rand(0, $count - 1), 1);
            array_push($array, $chunk);
        }

        return $array;
    }

    function questions()
    {
        $question_orders = $this->getQuestionsOrder($this);
        $activity = $this;
        return $this->quiz->questions()->get()->each(function ($item, $key) use ($activity, $question_orders) {

            $item['answers_count'] = Answer::where('activity_id', $activity->id)->where('question_id', $item->id)->count();
            $correct_answers = Answer::where('activity_id', $activity->id)->where('question_id', $item->id)->where('is_correct', true)->count();
            $incorrect_answers = Answer::where('activity_id', $activity->id)->where('question_id', $item->id)->where('is_correct', false)->count();

            // Compute statistics for this question in this activity
            $item['statistics'] = [
                'correct_answers' => $correct_answers,
                'incorrect_answers' => $incorrect_answers,
                'missing_answers' => $activity->roster->students_count - $correct_answers - $incorrect_answers
            ];

            // Fetch student answer
            if (Auth::user()->isStudent()) {
                $item['answer'] = Answer::where('activity_id', $activity->id)
                    ->where('question_id', $item->id)
                    ->where('student_id', Auth::user()->student->id)
                    ->first();
            }

            // Fetch multiple choices stats
            if (Auth::user()->isTeacher() && $item->type == 'multiple-choice') {
                $choices = (object)[];
                Answer::where('activity_id', $activity->id)
                    ->where('question_id', $item->id)
                    ->select('answer')
                    ->get()->each(function ($item) use ($choices) {
                        $answer = $item->answer;
                        foreach ($answer as $proposition) {
                            $proposition -= 1;
                            if (property_exists($choices, $proposition)) {
                                $choices->$proposition++;
                            } else {
                                $choices->$proposition = 1;
                            }
                        }
                    });

                $item['choices'] = $choices;
            }

            // Get next and previous question
            // TODO: Question shuffling not yet working...
            if (Auth::user()->isStudent()) {
                $item['key'] = $key;
                $item['question_number'] = $key + 1;
                $item['previous_question'] = $key - 1 >= 0 ? $key : null;
                $item['next_question'] = $key + 1 < $activity->quiz->questions_count ? $key + 2 : null;
            }
        });
    }

    /**
     * Get the final rank
     */
    public function getRank($student_id = null)
    {
        if ($this->status != 'finished')
            return null;

        $totalPoints = $this->answers()
            ->where('student_id', $student_id)
            ->sum('points');

        $maximumPoints = $this->quiz->questions->sum('points');

        $mark = ($totalPoints / $maximumPoints) * 5 + 1;

        return round($mark, 1);
    }
}

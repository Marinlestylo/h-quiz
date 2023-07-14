<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $withCount = [
        'questions'
    ];

    protected $appends = [
        'difficulty',
        'keywords',
        'taken'
    ];

    function questions() {
        return $this->belongsToMany(Question::class)
            ->withPivot('order')
            ->orderBy('question_quiz.order');
    }

    function activities() {
        return $this->belongsTo(Activity::class);
    }

    function getDifficultyAttribute() {
        $difficulty = 0;
        $count = 0;
        foreach ($this->questions()->select('difficulty')->get() as $question) {
            switch($question->difficulty) {
                case 'easy': $difficulty += 0; break;
                case 'medium': $difficulty += 1; break;
                case 'hard': $difficulty += 2; break;
                case 'insane': $difficulty += 3; break;
            }
            $count += 1;
        };
        if( $count > 0){
            $difficulty = round($difficulty / $count);
        }
        return $difficulty;
    }

    /**
     * Returns the keywords associated to this quiz.
     */
    function getKeywordsAttribute() {
        $keywords = [];
        foreach ($this->questions()->with('keywords')->get() as $question) {
            foreach ($question->keywords as $keyword) {
                $keywords[] = $keyword->name;
            }
        };
        $keywords = array_unique($keywords);
        sort($keywords);
        return $keywords;
    }

    /**
     * How many times this quiz was taken.
     */
    function getTakenAttribute() {
        return $this->activities()->whereNotNull('started_at')->count();
    }

    function owner() {
        return $this->belongsTo(User::class, 'user_id');
    }
}

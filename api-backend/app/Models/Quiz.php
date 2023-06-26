<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    function questions() {
        return $this->belongsToMany(Question::class)
            ->withPivot('order')
            ->orderBy('question_quiz.order');
    }

    function activities() {
        return $this->belongsTo(Activity::class);
    }

    function owner() {
        return $this->belongsTo(User::class, 'user_id');
    }
}

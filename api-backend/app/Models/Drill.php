<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Student;
use App\Models\Question;


class Drill extends Model
{
    protected $fillable = ['student_id', 'question_id', 'keyword_id'];

    function question() {
        return $this->belongsTo(Question::class);
    }

    function student() {
        return $this->belongsTo(Student::class);
    }

    function keyword() {
        return $this->belongsTo(Keyword::class);
    }
}

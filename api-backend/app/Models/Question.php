<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

class Question extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $casts = [
        'validation' => 'array', // JSON
        'options' => 'array' // JSON
    ];

    //protected $hidden = ['pivot'];

    function keywords()
    {
        return $this->belongsToMany(Keyword::class);
    }

    function quiz()
    {
        return $this->belongsToMany(Quiz::class);
    }

    function answers()
    {
        return $this->hasMany(Answer::class);
    }

    function validate($value)
    {
        switch ($this->type) {
            case 'short-answer':
                return $this->validateShortAnswer($value);
            case 'long-answer':
                return $this->validateLongAnswer($value);
            case 'multiple-choice':
                return $this->validateMultipleChoice($value);
            case 'code':
                return $this->validateCode($value);
            case 'fill-in-the-gaps':
                return $this->validateFillInTheGaps($value);
        }
        return false;
    }

    protected function validateShortAnswer($value)
    {
        // we check if the value is the same as the expected answer
        if (Arr::has($this, 'validation.expected') && strval($value) == Arr::get($this, 'validation.expected')){
            return true;
        }

        // we check if the value matches the pattern
        if (Arr::has($this, 'validation.pattern')) {
            if (preg_match(Arr::get($this, 'validation.pattern'), strval($value))) {
                return true;
            }
        }
        return false;
    }

    // long answer has no automatic validation because it needs to be corrected by a teacher
    protected function validateLongAnswer($value)
    {
        return false;
    }

    protected function validateMultipleChoice($value)
    {
        $target = array_unique($this->validation);
        sort($target);
        $answer = array_unique($value);
        sort($value);

        return $value == $target;
    }

    protected function validateFillInTheGaps($value)
    {
        return $value == $this->validation;
    }

    // code has no automatic validation because it needs to be corrected by a teacher even if the ouput is the one expected
    protected function validateCode($value)
    {
        return false;
    }
}

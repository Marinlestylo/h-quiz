<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $casts = [
        'validation' => 'array', // JSON
        'options' => 'array' // JSON
    ];

    //protected $hidden = ['pivot'];

    function keywords() {
        return $this->belongsToMany(Keyword::class);
    }

    function quiz() {
        return $this->belongsToMany(Quiz::class);
    }

    function answers() {
        return $this->hasMany(Answer::class);
    }
}

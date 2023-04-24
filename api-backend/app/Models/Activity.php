<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    function quiz() {
        return $this->belongsTo(Quiz::class);
    }

    function teacher() {
        return $this->belongsTo(User::class);
    }

    function roster() {
        return $this->belongsTo(Roster::class);
    }

    function answers() {
        return $this->hasMany(Answer::class);
    }
}

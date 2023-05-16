<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Roster extends Model
{
    use HasFactory;

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    function students() {
        return $this->belongsToMany(Student::class);
    }

    function activities() {
        return $this->hasMany(Activity::class);
    }

    function teacher() {
        return $this->belongsTo(User::class, 'teacher_id');
    }
}

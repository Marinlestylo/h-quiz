<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    function rosters() {
        return $this->belongsToMany(Roster::class);
    }

    function user() {
        return $this->belongsTo(User::class);
    }
}

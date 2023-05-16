<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Roster;

class Course extends Model
{
    use HasFactory;

    function rosters() {
        return $this->hasMany(Roster::class)->withCount('students');
    }
}

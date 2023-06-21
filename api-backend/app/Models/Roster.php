<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class Roster extends Model
{
    use HasFactory;

    protected $appends = [
        'has_running_activities'
    ];

    protected $withCount = [
        'students'
    ];

    protected $fillable = ['year', 'name', 'course_id', 'semester', 'teacher_id'];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    function students()
    {
        return $this->belongsToMany(Student::class);
    }

    function activities()
    {
        return $this->hasMany(Activity::class);
    }

    function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    function orientations()
    {
        // get the count of each orientation
        $data = DB::table('students')
            ->select('orientation', DB::raw('count(*) as count'))
            ->join('roster_student', 'students.id', '=', 'roster_student.student_id')
            ->where('roster_student.roster_id', $this->id)
            ->groupBy('orientation')
            ->get();
        $orientations = [];
        foreach ($data as $orientation) {
            $orientations[$orientation->orientation] = $orientation->count;
        }
        return $orientations;
    }

    function getHasRunningActivitiesAttribute()
    {
        $has_running_activites = false;
        foreach ($this->activities()->get() as $activity) {
            if ($activity->started && !$activity->completed)
                $has_running_activites = true;
        }
        return $has_running_activites;
    }
}

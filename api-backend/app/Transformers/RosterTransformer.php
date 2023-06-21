<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\Roster;
use League\Fractal;

class RosterTransformer extends TransformerAbstract
{
    protected $links;

    public function __construct($links = false) {
        $this->links = $links;
    }

    protected function fullname(Roster $roster) {
        return $roster->course->name . '-'.
        $roster->course->department . '-' .
        $roster->name;
    }

    public function transform(Roster $roster, $links = false)
	{
	    $data = [
            'id' => $roster->id,
            'year' => $roster->year,
            'name' => $this->fullname($roster),
            'semester' => $roster->semester ? 'Printemps' : 'Automne',
            'students' => $roster->students_count,
            'orientations' => $roster->orientations(),
            'has_running_activities' => $roster->has_running_activities,
            'teacher' => [
                'id' => $roster->teacher->id,
                'name' => $roster->teacher->name
            ],
            'created_at' => $roster->created_at->timestamp,
        ];

        if ($this->links) {
            $data = array_merge($data, [
            // Links
            'course_url' => url("api/rosters/$roster->id/course"),
            'students_url' => url("api/rosters/$roster->id/students"),
            'activities_url' => url("api/rosters/$roster->id/activities"),
            ]);
        }

        return $data;
	}
}

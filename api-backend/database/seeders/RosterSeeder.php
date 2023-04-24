<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Course;
use App\Models\Roster;

class RosterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $playground = Course::where('name', 'PlayGround')->first();
        $playground_roster = Roster::create([
            'name' => 'A',
            'semester' => 0,
            'year' => 2020,
            'course_id' => $playground->id,
            'teacher_id' => 1
        ]);
    }
}

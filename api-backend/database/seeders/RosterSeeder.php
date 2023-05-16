<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Course;
use App\Models\Student;
use App\Models\User;
use App\Models\Roster;

class RosterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all students
        $students = Student::all();
        $courses = Course::all();
        $studentsCount = count($students);
        $studentPerClass = $studentsCount / count($courses) / 2;
        $k = 0;

        // Playground course with Bob and Alice
        $playground = Course::where('name', 'PlayGround')->first();
        $playground_roster = Roster::create([
            'name' => 'A',
            'semester' => 0,
            'year' => 2020,
            'course_id' => $playground->id,
            'teacher_id' => 1
        ]);

        $playground_roster->students()->attach(User::where('firstname', 'Bob')->first()->student);
        $playground_roster->students()->attach(User::where('firstname', 'Alice')->first()->student);

        // Generate the courses
        foreach (Course::all() as $course) {
            $roster = Roster::factory()->make();
            $roster->name = 'A';
            $roster->course_id = $course->id;
            $roster->save();
            for($i = 0; $i < $studentPerClass && $k < $studentsCount; $i++) {
                $roster->students()->attach($students[$k++]);
            }

            $roster = Roster::factory()->make();
            $roster->name = 'B';
            $roster->course_id = $course->id;
            $roster->save();
            for($i = 0; $i < $studentPerClass && $k < $studentsCount; $i++) {
                $roster->students()->attach($students[$k++]);
            }
        }
    }
}

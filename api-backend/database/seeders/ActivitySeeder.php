<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Activity;
use App\Models\Answer;
use App\Models\User;

class ActivitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $activity = Activity::create([
            'quiz_id' => 1,
            'roster_id' => 1,
            'duration' => 120,
            'user_id' => 1,
            'started_at' => '2020-09-01T14:58:05',
            'opened_at' => '2020-09-01T15:30:00',
            'ended_at' => '2020-09-01T15:40:00',
        ]);

        $bob = User::find(3)->student;
        $alice = User::find(4)->student;

        Answer::create([
            'activity_id' => 1,
            'student_id' => $alice->id,
            'question_id' => 1,
            'answer' => "0b111",
            'is_correct' => true
        ]);

        Answer::create([
            'activity_id' => 1,
            'student_id' => $bob->id,
            'question_id' => 1,
            'answer' => "0b111",
            'is_correct' => true
        ]);
        Answer::create([
            'activity_id' => 1,
            'student_id' => $alice->id,
            'question_id' => 2,
            'answer' => "1000",
            'is_correct' => true
        ]);

        Answer::create([
            'activity_id' => 1,
            'student_id' => $bob->id,
            'question_id' => 2,
            'answer' => "0b01001",
            'is_correct' => false
        ]);

        Answer::create([
            'activity_id' => 1,
            'student_id' => $alice->id,
            'question_id' => 3,
            'answer' => "0",
            'is_correct' => true
        ]);

        Answer::create([
            'activity_id' => 1,
            'student_id' => $bob->id,
            'question_id' => 3,
            'answer' => "zero",
            'is_correct' => false
        ]);

        Answer::create([
            'activity_id' => 1,
            'student_id' => $alice->id,
            'question_id' => 4,
            'answer' => [2],
            'is_correct' => true
        ]);

        Answer::create([
            'activity_id' => 1,
            'student_id' => $bob->id,
            'question_id' => 4,
            'answer' => [1],
            'is_correct' => false
        ]);
        Answer::create([
            'activity_id' => 1,
            'student_id' => $alice->id,
            'question_id' => 5,
            'answer' => ["Une file d'attente", "une file d'attente", "une file d'attente"],
            'is_correct' => false
        ]);

        Answer::create([
            'activity_id' => 1,
            'student_id' => $bob->id,
            'question_id' => 5,
            'answer' => ["Une file d'attente", "une file d'attente", "une pile"],
            'is_correct' => true
        ]);
        Answer::create([
            'activity_id' => 1,
            'student_id' => $alice->id,
            'question_id' => 6,
            'answer' => "int i = 42;",
            'is_correct' => true
        ]);

        Answer::create([
            'activity_id' => 1,
            'student_id' => $bob->id,
            'question_id' => 6,
            'answer' => "int i = 42;",
            'is_correct' => true
        ]);
        Answer::create([
            'activity_id' => 1,
            'student_id' => $alice->id,
            'question_id' => 7,
            'answer' => [1, 2, 3],
            'is_correct' => false
        ]);

        Answer::create([
            'activity_id' => 1,
            'student_id' => $bob->id,
            'question_id' => 7,
            'answer' => [3, 4],
            'is_correct' => true
        ]);

        Answer::create([
            'activity_id' => 1,
            'student_id' => $bob->id,
            'question_id' => 8,
            'answer' => [
                "une paire différentielle",
                "transistors bipolaires",
                "amplificateur de signal",
                "transistors bipolaires",
                "de Schottky"
            ],
            'is_correct' => false
        ]);
    }
}

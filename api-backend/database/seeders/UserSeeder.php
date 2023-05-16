<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use App\Models\Student;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'unique_id' => '1456',
            'firstname' => 'Jonathan',
            'lastname' => 'Friedli',
            'email' => 'jonathan.friedli@heig-vd.ch',
            'keycloak_id' => '7b9fba17-9a6a-4f40-96da-0dc513454f32',
            'gender' => '1',
            'affiliation' => 'member;staff',
        ]);

        User::create([
            'unique_id' => '7454',
            'firstname' => 'Yves',
            'lastname' => 'Chevallier',
            'email' => 'yves.chevallier@heig-vd.ch',
            'keycloak_id' => '7b9fba17-9a6a-4f40-96da-0dc513454f32',
            'gender' => '1',
            'affiliation' => 'member;staff',
        ]);

        $bob = User::create([
            'unique_id' => '666',
            'firstname' => 'Bob',
            'lastname' => 'Sponge',
            'email' => 'bob.sponge@heig-vd.ch',
            'gender' => '1',
            'affiliation' => 'member;student',
        ]);

        $alice = User::create([
            'unique_id' => '667',
            'firstname' => 'Alice',
            'lastname' => 'Carrolls',
            'email' => 'alice.carrolls@heig-vd.ch',
            'gender' => '1',
            'affiliation' => 'member;student',
        ]);

        Student::create([
            'orientation' => 'EAI',
            'type' => 'TP',
            'user_id' => $bob->id
        ]);

        Student::create([
            'orientation' => 'EAI',
            'type' => 'TP',
            'user_id' => $alice->id
        ]);

        User::factory()->count(50)->create()->each(function ($user) {
            if ($user->affiliation == 'member;student') {
                $user->student()->save(Student::factory()->make());
            }
        });
    }
}

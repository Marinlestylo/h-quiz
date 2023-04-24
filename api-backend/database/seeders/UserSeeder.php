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
            'firstname' => 'Tony',
            'lastname' => 'Maulaz',
            'email' => 'tony.maulaz@heig-vd.ch',
            'name' => 'Tony Maulaz',
            'password' => 'shibboleth',
            'gender' => '1',
            'affiliation' => 'member;staff',
            'api_token' => Str::random(60),
        ]);

        User::create([
            'unique_id' => '7454',
            'firstname' => 'Yves',
            'lastname' => 'Chevallier',
            'email' => 'yves.chevallier@heig-vd.ch',
            'name' => 'Yves Chevallier',
            'password' => 'shibboleth',
            'gender' => '1',
            'affiliation' => 'member;staff',
            'api_token' => Str::random(60),
        ]);

        $bob = User::create([
            'unique_id' => '666',
            'firstname' => 'Bob',
            'lastname' => 'Sponge',
            'email' => 'bob.sponge@heig-vd.ch',
            'name' => 'Bob',
            'password' => 'shibboleth',
            'gender' => '1',
            'affiliation' => 'member;student',
            'api_token' => Str::random(60),
        ]);

        $alice = User::create([
            'unique_id' => '666',
            'firstname' => 'Alice',
            'lastname' => 'Carrolls',
            'email' => 'alice.carrolls@heig-vd.ch',
            'name' => 'Alice',
            'password' => 'shibboleth',
            'gender' => '1',
            'affiliation' => 'member;student',
            'api_token' => Str::random(60),
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

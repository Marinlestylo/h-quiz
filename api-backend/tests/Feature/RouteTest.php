<?php

namespace Tests\Feature;


use Tests\TestCase;
use \Auth;
use \App\Models\User;

class RouteTest extends TestCase
{


    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate:fresh --seed');
    }

    /**
     * Test of the root of the API
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/api');

        $response->assertStatus(200);
    }

    /**
     * Test of a protected route of the API while not logged in
     */
    public function test_the_application_returns_a_401_on_protected_route(): void
    {
        $response = $this->get('/api/quizzes');

        $response->assertStatus(401);
    }

    /**
     * Test of a protected route of the API while logged in as teacher
     */
    public function test_the_application_returns_a_200_on_teacher_route_as_teacher(): void
    {
        // $this->seed(
        //     \Database\Seeders\UserSeeder::class,
        // );
        Auth::login(User::find(1));
        $response = $this->get('/api/quizzes');

        $response->assertStatus(200);
    }

    /**
     * Test of a protected route of the API while logged in as student
     */
    public function test_the_application_returns_a_403_on_teacher_route_as_student(): void
    {
        // $this->seed(
        //     \Database\Seeders\UserSeeder::class,
        // );
        Auth::login(User::find(3));
        $response = $this->get('/api/quizzes');

        $response->assertStatus(403);
    }

    /**
     * Test of a protected route of the API while logged in as student
     */
    public function test_the_application_returns_a_200_on_student_only_route(): void
    {
        // $this->seed(
        //     \Database\Seeders\UserSeeder::class,
        // );
        Auth::login(User::find(3));
        $response = $this->get('/api/user/activities');

        $response->assertStatus(200);
    }

    /**
     * Test of a protected route of the API while logged in as student
     */
    public function test_the_application_returns_a_403_on_student_only_route(): void
    {
        // $this->seed(
        //     \Database\Seeders\UserSeeder::class,
        // );
        Auth::login(User::find(1));
        $response = $this->get('/api/user/activities');

        $response->assertStatus(403);
    }


    /**
     * Test of a protected route of the API while logged in as student
     */
    public function test_the_application_returns_a_200_after_trying_to_modify_roster(): void
    {
        // $this->seed([
        //     \Database\Seeders\UserSeeder::class,
        //     \Database\Seeders\CourseSeeder::class,
        //     \Database\Seeders\RosterSeeder::class,
        // ]);
        Auth::login(User::find(1));
        $response = $this->delete('/api/rosters/delete-student', ['roster_id' => 1, 'student_id' => 1]);

        $response->assertStatus(200);
    }

    /**
     * Test of a protected route of the API while logged in as student
     */
    public function test_the_application_returns_a_400_after_trying_to_modify_another_teacher_roster(): void
    {
        // $this->seed([
        //     \Database\Seeders\UserSeeder::class,
        //     \Database\Seeders\CourseSeeder::class,
        //     \Database\Seeders\RosterSeeder::class,
        // ]);
        Auth::login(User::find(1));
        $response = $this->delete('/api/rosters/delete-student', ['roster_id' => 2, 'student_id' => 1]);

        $response->assertStatus(400);
    }
}

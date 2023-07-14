<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Student;



class StudentController extends Controller
{
    function index() {
        $students = Student::with('user')->get()->all();

        return [
            'count' => count($students),
            'students' => $students,
        ];
    }
}

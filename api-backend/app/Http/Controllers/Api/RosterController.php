<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Roster;
use App\Models\Student;

use App\Transformers\RosterTransformer;
use App\Transformers\StudentTransformer;

use Illuminate\Http\Request;
use Auth;
use Log;


class RosterController extends Controller
{
    function index(Request $request) {
        if ($request->owned)
            $rosters = Roster::where('teacher_id', Auth::id())->get();
        else
            $rosters = Roster::all();

        return fractal($rosters, new RosterTransformer(true))->toArray();
    }

    function show($id) {
        return Roster::with(['course','students','activities'])->find($id);
    }

    function students($id) {
        $students = Roster::findOrFail($id)->students;

        return fractal($students, new StudentTransformer())->toArray();
    }

    function deleteStudent(Request $request) {
        Log::debug('Delete student to roster');

        $r = Roster::findOrFail($request->roster_id);
        
        if ($r->teacher_id != Auth::id()) {
            return response([
                'message' => "Only the roster's teacher can delete a student",
                'error' => "Bad Request",
            ], 400);
        }

        $s = Student::findOrFail($request->student_id);

        $r->students()->detach($s->id);

        $students = Roster::findOrFail($request->roster_id)->students;
        $s = fractal($students, new StudentTransformer())->toArray();
        return response([
            'message' => 'Studdent deleted',
            'roster' => $r,
            'students' => $s
        ], 200);
    }

    function addStudent(Request $request) {
        Log::debug('Add student to roster');

        $r = Roster::findOrFail($request->roster_id);
        if ($r->teacher_id != Auth::id()) {
            return response([
                'message' => "Only the roster's teacher can create an activity",
                'error' => "Bad Request"
            ], 400);
        }

        $s = Student::findOrFail($request->student_id);

        $r->students()->attach($s->id);

        $students = Roster::findOrFail($request->roster_id)->students;
        $s = fractal($students, new StudentTransformer())->toArray();
        return response([
            'message' => 'Studdent added',
            'roster' => $r,
            'students' => $s
        ], 200);
    }
}

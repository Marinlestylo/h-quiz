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

    function create(Request $request) {
        Log::debug('Create roster');
        $data = $request->all();
        $r = new Roster();

        if ($data['year'] > 2155 || $data['year'] < 1970) {
            return response([
                'message' => "L'année doit être comprise entre 1970 et 2155.",
                'error' => "Bad Request"
            ], 400);
        }
       
        // create the roster
        $r->fill($data);
        $r->teacher_id = Auth::id();
        $r->save();

        return response([
            'message' => 'Le roster a bien été créé.',
            'roster' => $r
        ], 201);
    }

    function deleteStudent(Request $request) {
        Log::debug('Delete student to roster');

        $r = Roster::findOrFail($request->roster_id);
        
        if ($r->teacher_id != Auth::id()) {
            return response([
                'message' => "Vous devez être le professeur de ce roster pour pouvoir supprimer un étudiant.",
                'error' => "Forbidden",
            ], 403);
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
                'message' => "Vous devez être le professeur de ce roster pour pouvoir ajouter un étudiant.",
                'error' => "Forbidden"
            ], 403);
        }

        $s = Student::findOrFail($request->student_id);

        if ($r->students->contains($s->id)) {
            return response([
                'message' => "L'étudiant est déjà dans ce roster.",
                'error' => "Bad Request"
            ], 400);
        }

        $r->students()->attach($s->id);

        $students = Roster::findOrFail($request->roster_id)->students;
        $s = fractal($students, new StudentTransformer())->toArray();
        return response([
            'message' => 'L\'étudiant a bien été ajouté.',
            'roster' => $r,
            'students' => $s
        ], 201);
    }
}

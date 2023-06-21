<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Roster;

use App\Transformers\RosterTransformer;

use Illuminate\Http\Request;

class RosterController extends Controller
{
    function index(Request $request) {
        // if ($request->owned)
        //     $rosters = Roster::where('teacher_id', Auth::id())->get();
        // else
            $rosters = Roster::all();

        return fractal($rosters, new RosterTransformer(true))->toArray();
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Question;
use App\Models\Drill;
use App\Models\Keyword;
use Illuminate\Support\Carbon;

use App\Transformers\DrillTransformer;

use Auth;
use Illuminate\Support\Facades\Log as FacadesLog;
use Log;

class DrillController extends Controller
{
    function index()
    {
        return ('drill');
    }

    function makeDrill($keyword)
    {
        $k = Keyword::where('name', $keyword)->first();
        $q = Question::where('is_public', true)
            ->whereHas('keywords', function ($query) use ($keyword) {
                return $query->where('name', 'like', $keyword);
            })->get();

        $student_id = Auth::user()->student->id;

        $drills = [];
        foreach ($q as $question) {

            $existingDrill = Drill::where('student_id', $student_id)
                ->where('question_id', $question->id)
                ->where('keyword_id', $k->id)
                ->first();

            if (!$existingDrill) {
                Drill::create([
                    'student_id' => $student_id,
                    'question_id' => $question->id,
                    'keyword_id' => $k->id,
                ]);
            } else {
                $drills[] = $existingDrill;
            }
        }

        return fractal($drills, new DrillTransformer())->toArray();
    }

    function answerDrill(Request $request){
        $question_id = $request->question_id;
        $correct = false;
        $q = Question::findOrFail($question_id);
        $drill = Drill::findOrFail($request->drill_id);

        // Check if answer was correct
        $correct = $q->validate($request->answer);

        $rank = $this->getRank($request->time, $correct);

        // algorithm comes from here https://www.supermemo.com/en/blog/application-of-a-computer-to-improve-the-results-obtained-in-working-with-the-supermemo-method at this section : Algorithm SM-2 used in the computer-based variant of the SuperMemo method and involving the calculation of easiness factors for particular items:
        if($rank >= 3) {
            if($drill->repetitions == 0) {
                $drill->interval = 1;
            } else if($drill->repetitions == 1) {
                $drill->interval = 6;
            } else {
                $drill->interval *= $drill->easiness;
            }
            $drill->repetitions += 1;
        } else {
            $drill->repetitions = 0;
            $drill->interval = 1;
        }

        $drill->easiness = max(1.3, $drill->easiness + (0.1 - (5 - $rank) * (0.08 + (5 - $rank) * 0.02)));
        $nextRepetition = Carbon::parse($drill->next_repetition);
        $drill->next_repetition = $nextRepetition->addDays($drill->interval);

        $drill->save();

        return response()->json([
            'correct' => $correct,
            'drill' => fractal($drill, new DrillTransformer),
        ]);
    }

    // Rank is a number between 0 and 5.
    // 5 means less than 20 and correct
    // 4 means less than 40 and correct
    // 3 means more than 40 and correct
    // 2 means less than 20 and incorrect
    // 1 means less than 40 and incorrect
    // 0 means more than 40 and incorrect
    protected function getRank($time, $correct){
        $rank = 5;
        if ($time > 40){
            $rank -= 2;
        } else if ($time > 20){
            $rank -= 1;
        }

        if (!$correct){
            $rank -= 3;
        }
        return $rank;
    }
}

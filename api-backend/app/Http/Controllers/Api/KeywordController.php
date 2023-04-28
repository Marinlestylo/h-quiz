<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Models\Keyword;

class KeywordController extends Controller
{
    function index() {
        // $keywords = Keyword::withCount('questions')->get();
        $keywords = Keyword::all();
        return [
            'count' => count($keywords),
            'keywords' => $keywords,
        ];
    }
}

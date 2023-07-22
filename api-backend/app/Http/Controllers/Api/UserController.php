<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;

use Illuminate\Http\Request;

class UserController extends Controller
{
    function index()
    {
        $users = User::all()->each(function ($item, $key) {
            $item['activities'] = url("/api/users/{$item['id']}/activities");
            $item['rosters'] = url("/api/users/{$item['id']}/rosters");
        });
        return [
            'count' => count($users),
            'users' => $users,
        ];
    }

    function me(Request $request)
    {
        $name = '';
        $affiliation = '';
        $id = null;

        if ($request->user()) {
            $name = $request->user()->getFullName();
            $affiliation = $request->user()->affiliation;
            $id = $request->user()->id;
        }
        return response()->json([
            'name' => $name,
            'role' => $affiliation,
            'id' => $id,
        ]);
    }
}

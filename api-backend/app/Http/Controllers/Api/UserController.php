<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;

use Auth;

use Illuminate\Http\Request;

class UserController extends Controller
{
    function index() {
        $users = User::all()->each(function ($item, $key) {
            $item['activities'] = url("/api/users/{$item['id']}/activities");
            $item['rosters'] = url("/api/users/{$item['id']}/rosters");
        });
        return [
            'count' => count($users),
            'users' => $users,
        ];
    }
}

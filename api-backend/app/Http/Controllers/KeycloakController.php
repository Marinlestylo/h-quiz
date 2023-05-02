<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Exception;
use Socialite;
use App\Models\User;

class KeyCloakController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('keycloak')->redirect();
    }

    public function callback()
    {
        $remote_user = Socialite::driver('keycloak')->user();
        // dump($remote_user);
        // \Log::info($remote_user);
        // die();
        $user = User::updateOrCreate([
            'keycloak_id' => $remote_user->id,
        ], [
            'firstname' => $remote_user->user['given_name'],
            'lastname' => $remote_user->user['family_name'],
            'email' => $remote_user->email,
            'gender'=>$remote_user->user['gender'],
            'affiliation'=>'member;staff',
            'unique_id'=> 3252355,
            'remember_token' => $remote_user->refreshToken,
        ]);

        // $user2 = User::find(1);
        // if ($user2) {
        //     Auth::login($user2);
        // }

        Auth::login($user);
        // dump($user);
        // die();

        return redirect('/keywords');
    }
}
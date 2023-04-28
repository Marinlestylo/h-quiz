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
        var_dump($remote_user);
        // die();
        $user = User::updateOrCreate([
            'keycloak_id' => $remote_user->id,
        ], [
            'firstname' => $remote_user->given_name,
            'lastname' => $remote_user->family_name,
            'email' => $remote_user->email,
            'gender'=>$remote_user->gender,
            'affiliation'=>'member;staff',
            'refresh_token' => $remote_user->refreshToken,
        ]);

        Auth::login($user, $remember = true);

        return redirect('/');
    }
}
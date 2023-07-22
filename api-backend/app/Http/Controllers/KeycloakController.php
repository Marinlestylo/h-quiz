<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
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
        $remote_user = Socialite::driver('keycloak')->stateless()->user();
        // TODO : remove + check user creation
        // dump($remote_user);
        // die();
        $user = User::updateOrCreate([
            'keycloak_id' => $remote_user->id,
        ], [
            'firstname' => $remote_user->user['given_name'],
            'lastname' => $remote_user->user['family_name'],
            'email' => $remote_user->email,
            'gender'=>$remote_user->user['gender'],
            'affiliation'=>'member;staff',
            'remember_token' => $remote_user->refreshToken,
        ]);

        Auth::login($user);
        return redirect(session()->get('url.intended', '/api'));
    }

    public function login(Request $request)
    {
        $url = $request->input('redirect');
        session()->put('url.intended', $url);
        return Socialite::driver('keycloak')->redirect();
    }

    public function logout()
    {
        Auth::logout();
        $redirectUri = env('APP_URL')."/api/after";
        return redirect(Socialite::driver('keycloak')->getLogoutUrl($redirectUri, env('KEYCLOAK_CLIENT_ID')));
    }

    public function afterLogout()
    {
        return redirect(session()->get('url.intended', '/api'));
    }
}
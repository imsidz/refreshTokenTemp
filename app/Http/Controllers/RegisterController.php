<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();
        //Personal Token
        $token = $user->createToken('Register User')->accessToken;

        //Refresh Token this not work
        $response = Http::post(config('app.url') . '/oauth/token', [
            'form_params' => [
                'grant_type' => 'refresh_token',
                'refresh_token' => 'the-refresh-token',
                'client_id' => '3',
                'client_secret' => '7yEUe0qrsyAecdcFHn4wTQfEPRsK7dlsGvoH4amg',
                'scope' => '',
            ],
        ]);

        $refreshToken = json_decode((string) $response->getBody(), true);

        return response()->json([
            'user' => $user,
            'personalToken' => $token,
            'refreshToken' => $refreshToken
        ]);
    }

    public function generateRefreshToken()
    {
        //Refresh Token this is working

        $response = Http::post(config('app.url') . '/oauth/token', [
            'form_params' => [
                'grant_type' => 'refresh_token',
                'refresh_token' => 'the-refresh-token',
                'client_id' => '3',
                'client_secret' => '7yEUe0qrsyAecdcFHn4wTQfEPRsK7dlsGvoH4amg',
                'scope' => '',
            ],
        ]);

        $refreshToken = json_decode((string) $response->getBody(), true);

        return response()->json(
            [
                'token' => $refreshToken
            ]
        );
    }
}

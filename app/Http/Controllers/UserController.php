<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;


class UserController extends Controller
{
    // registration API.................
    public function register(Request $req)
    {
        $existingUser = User::where('email', $req->input('email'))->first();
        if ($existingUser) {
            return response()->json(['error' => ['User with this email already exists']], 409);
        }
        $user = new User;
        $user->name = $req->input('name');
        $user->email = $req->input('email');
        $user->password = Hash::make($req->input('password'));
        $user->save(); 

        return response()->json($user, 201);
    }

    // login api.....................
    function login(Request $req)
    {
        $credentials = $req->only('email', 'password');
    
        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials'], 401);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'could_not_create_token'], 500);
        }
    
        return response()->json(compact('token'));
    }
}

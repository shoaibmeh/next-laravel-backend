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
    public function store(Request $req)
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
    public function authenticate(Request $req)
    {
        $credentials = $req->only('email', 'password');
        $email = $credentials['email'];
        $password = $credentials['password'];
        if ($email === 'admin@123.com' && $password === 'aaaaa1A!') {
            try {
                if (! $token = JWTAuth::attempt($credentials)) {
                    return response()->json(['error' => 'invalid_credentials'], 401);
                }
            } catch (JWTException $e) {
                return response()->json(['error' => 'could_not_create_token'], 500);
            }
            return response()->json(compact('token'));
        }  
        return response()->json(['error' => 'unauthorized'], 401);
    }

    // api to list all users
    public function index()
    {
        $users = User::all();
        return response()->json($users, 200);
    }
    // delete user API
    public function destroy($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }
        $user->delete();
        return response()->json(['message' => 'User deleted successfully'], 200);
    }

    // get single User
    public function getUser($id)
    {
        $user = User::find($id);
        return response()->json($user);
    }
    // update product API
    public function update(Request $req, $id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }
        $user->name = $req->input('name', $user->name);
        $user->email = $req->input('email', $user->email); 
        if ($req->has('password')) {
            $user->password = Hash::make($req->input('password'));
        }
        $user->save();
        return response()->json($user, 200);
    }

}

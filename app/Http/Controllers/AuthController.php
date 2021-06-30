<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function register(Request $request) {
        $create_user = User::create([
            'username' => $request->input('username'),
            'password' => Hash::make($request->input('password')),
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            /*'avatar'=> $request->input('avatar')*/
        ]);
        return response([
            'message' => 'Succesfuly registered',
            'user' => $create_user
        ]);
    }
    public function login() {
        $login_data = request()->only(['username', 'password']);
        $token = JWTAuth::attempt($login_data);
        if (!$token) {
            return response([
                'message' => 'Incorrect login data!'
            ], 400);
        }
        else {
            return response([
                'message' => 'Succesfuly loged in',
                'token' => $token,
            ]);
        }
    }
    public function logout()
    {
        JWTAuth::invalidate(JWTAuth::getToken());
        return response([
            'message' => 'Logged out'
        ]);
    }
    public function reset_password() {
        $reset_password_data = request()->only(['email']);
        $new_password = Str::random(10);
        $hashed_pass = Hash::make($new_password);
        if(!User::where('email', $reset_password_data)->first()) {
            return response([
                'message' => 'No user with such email'
            ]);
        }
        else {
            DB::table('users')->where('email', $reset_password_data)->update([
                'password' => $hashed_pass
            ]);
            
            return response([
                'message' => 'Your password was reset',
                'new_pass' => $new_password
            ]);
        }
    }
}

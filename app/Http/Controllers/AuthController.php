<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\PasswordResetMail;

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
            DB::table('users')->where('username', $login_data['username'])->update([
                'remember_token' => $token
            ]);
            return response([
                'message' => 'Succesfuly loged in',
                'token' => $token,
            ]);
        }
    }
    public function logout()
    {
        $user = JWTAuth::toUser(JWTAuth::getToken());
        if(!$user) {
            return response([
                'message' => 'User is not logged in'
            ]);
        }
        else {
            JWTAuth::invalidate(JWTAuth::getToken());
            DB::table('users')->where('remember_token', JWTAuth::getToken())->update([
                'remember_token' => ''
            ]);
            return response([
                'message' => 'Logged out'
            ]);
        }
    }
    public function reset_password() {
        $reset_password_data = request()->only(['email']);
        $email = $reset_password_data['email'];
        $new_token = Str::random(20);
        $token_arr = $new_token;
        if(!User::where('email', $reset_password_data)->first()) {
            return response([
                'message' => 'No user with such email'
            ]);
        }
        else {
            DB::table('password_resets')->insert([
                'email' => $email,
                'token' => $token_arr
            ]);
            $details = [
                'title' => 'Password Reset Mail',
                'body' => 'Your link to reset password: http://127.0.0.1:8000/api/auth/reset_password/'.$new_token
            ];
            Mail::to($reset_password_data)->send(new PasswordResetMail($details));
            return response([
                'message' => 'Your password reset link was sent',
            ]);
        }
    }
    public function confirmation_token(Request $request, $token) {
        $data = DB::table('password_resets')->where('token', $request->token)->first();
        if (!$data) {
            return response([
                'message' => 'Wrong token'
            ]);
        }
        $user = User::where('email', $data->email)->first();
        if (!$user) {
            return response([
                'message' => 'Wrong email'
            ]);
        }
        $user->password = Hash::make($request->input('password'));
        $user->update();
        $user = JWTAuth::user();
        DB::table('password_resets')->where('email', $data->email)->delete();
        return response([
            'message' => 'Password changed'
        ]);
    }
}

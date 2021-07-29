<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index()
    {
        $all_users = User::all();
        return $all_users;
    }

    public function store(Request $request)
    {
        if($this->checkAdmin($request)) {
            $create_user = User::create([
                'username' => $request->input('username'),
                'password' => Hash::make($request->input('password')),
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'role' => $request->input('role')
            ]);
            return response([
                'message' => 'User succesfuly created',
                'user' => $create_user
            ]);
        }
        else {
            return response([
                'message' => 'You have no rights to create the user'
            ]);
        }
    }

    public function show($id)
    {
        $show_user = User::find($id);
        return $show_user;
    }

    public function update(Request $request, $id)
    {
        $user = JWTAuth::toUser(JWTAuth::getToken());
        if($user && $this->checkAdmin($request)) {
            $user = User::find($id);
            $user->update($request->all());
            return response([
                'message' => 'User succesfuly updated',
                'user' => $user
            ]);
        }
        else {
            return response([
                'message' => 'You have no rights to update user'
            ]);
        }
    }

    public function destroy(Request $request, $id)
    {
        $user = JWTAuth::toUser(JWTAuth::getToken());
        if($user && $this->checkAdmin($request)) {
            User::destroy($id);
            return response([
                'message' => 'User succesfuly deleted'
            ]);
        }
        else {
            return response([
                'message' => 'You have no rights to delete users'
            ]);
        }
    }

    public function upload_avatar(Request $request) {
        $user = $this->checkLogIn($request);
        if(!$user) {
            return response([
                'message' => 'User is not logged in'
            ]);
        }
        else {
            $user = JWTAuth::toUser(JWTAuth::getToken());
            $request->validate([
                'avatar' => 'required|image|mimes:jpeg,png,jpg,gif',
            ]);
            $extension = $request->avatar->extension();
            $avatar = $user->id.'.'.$extension;
            $request->avatar->move(public_path('avatars'), $avatar);
            $path = 'avatars/'.$avatar;
            User::whereKey($user->id)->update([
                'avatar' => $path
            ]);
            return $this->download_user_avatar($user->id);
        }
    }
    public function download_user_avatar($id) {
        $path = User::where('id', $id)->value('avatar');
        return response()->download(public_path($path));
    }
}

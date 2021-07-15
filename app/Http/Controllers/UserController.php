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
        if($user || $this->checkAdmin($request)) {
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
        if($user || $this->checkAdmin($request)) {
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
            $validator = Validator::make($request->all(), [
                'image' => 'image|required|mimes:png,jpg,jpeg'
            ]);
            if ($validator->fails()) {
                return response([
                    'message' => 'Not a png or jpg file'
                ]);
            }
            $extension = $request->file('image')->extension();
            if($extension == 'jpg') {
                $this->destroy_avatar($user->id, 'png');
            }
            if($extension == 'png') {
                $this->destroy_avatar($user->id, 'jpg');
            }
            $file = 'avatar'.$user->id.'.'.$extension;
            $request->file('image')->storeAs('public/avatars', $file);
            $user->image = 'avatar' . $user->id.'.'.$extension;
            $user->save();
            return response([
                'message' => 'Avatar uploaded'
            ]);
        }
    }
    public function destroy_avatar($id, $extension) {
        Storage::delete('public/avatars/avatar'.$id.'.'.$extension);
    }
}

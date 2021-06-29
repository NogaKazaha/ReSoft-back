<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $all_users = User::all();
        return $all_users;
    }

    public function store(Request $request)
    {
        $create_user = User::create($request->all());
        return $create_user;
    }

    public function show($id)
    {
        $show_user = User::find($id);
        return $show_user;
    }

    public function update(Request $request, $id)
    {
       $user = User::find($id);
       $user->update($request->all());
       return $user; 
    }

    public function destroy($id)
    {
        $destroy_user = User::destroy($id);
        return $destroy_user;
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    public function checkLogIn(Request $request)
    {
        $token = explode(".", explode(" ", $request->header("Authorization"))[1])[2];
        $user = DB::table('users')->where("remember_token", $token);
        if($user) {
            return true;
        }
        else {
            return false;
        }
    }
    public function checkAdmin(Request $request)
    {
        $token = explode(".", explode(" ", $request->header("Authorization"))[1])[2];
        $user_role = DB::table('users')->where("remember_token", $token)->value('role');
        if($user_role == 'admin') {
            return true;
        }
        else {
            return false;
        }
    }
}

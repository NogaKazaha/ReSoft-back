<?php

namespace App\Http\Controllers;

use App\Models\Favorites;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;

class FavoritesController extends Controller
{
    public function show_user_favorites($id) {
        $favorites = DB::table('favorites')->where('user_id', $id)->get();
        return response([
            'Favorites' => $favorites
        ]);
    }

    public function add_to_favorites(Request $request, $id) {
        $user = $this->checkLogIn($request);
        if(!$user) {
            return response([
                'message' => 'User is not logged in'
            ]);
        }
        else {
            $user = JWTAuth::toUser(JWTAuth::getToken());
            if(DB::table('favorites')->where('post_id', $id)->value('user_id') != $user->id ) {
                $creditanals = [
                    'user_id' => $user->id,
                    'post_id' => $id
                ];
                Favorites::create($creditanals);
                return response([
                    'message' => 'New Favorite added'
                ]);
            }
            else {
                return response([
                    'message' => 'This post is already in your favorites'
                ]);
            }
        }
    }

    public function delete_from_favorites(Request $request, $id) {
        $user = $this->checkLogIn($request);
        if(!$user) {
            return response([
                'message' => 'User is not logged in'
            ]);
        }
        else {
            $user = JWTAuth::toUser(JWTAuth::getToken());
            if(DB::table('favorites')->where('post_id', $id)->where('user_id', $user->id)->get()) {
                DB::table('favorites')->where('post_id', $id)->where('user_id', $user->id)->delete();
                return response([
                    'message' => 'Favorite deleted'
                ]);
            }
            else {
                return response([
                    'message' => 'No such favorite'
                ]);
            }
        }
    }
}

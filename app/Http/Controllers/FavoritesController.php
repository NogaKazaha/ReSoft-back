<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;

class FavoritesController extends Controller
{
    public function show_user_favorites($id) {
        $all_favorites = [];
        $username = DB::table('users')->where('id', $id)->value('username');
        $post_ids = DB::table('favorites')->where('user_id', $id)->pluck('post_id');
        foreach($post_ids as $id) {
            $item = DB::table('posts')->where('id', $id)->get();
            array_push($all_favorites, $item);
        }
        return response([
            'Favorites of user '.$username => $all_favorites
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
                Favorite::create($creditanals);
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

    public function show_only_favorites(Request $request) {
        $user = $this->checkLogIn($request);
        if(!$user) {
            return response([
                'message' => 'User is not logged in'
            ]);
        }
        else {
            $user = JWTAuth::toUser(JWTAuth::getToken());
            $all_favorites = [];
            $post_ids = DB::table('favorites')->where('user_id', $user->id)->pluck('post_id');
            foreach($post_ids as $id) {
                $item = DB::table('posts')->where('id', $id)->get();
                array_push($all_favorites, $item);
            }
        }
        return $all_favorites;
    }
    public function get_user_fav_ids(Request $request, $id) {
        $favorites = DB::table('favorites')->where('user_id', $id)->pluck('post_id');
        return $favorites;
    }
}

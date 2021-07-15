<?php

namespace App\Http\Controllers;

use App\Models\Like;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;

class LikesController extends Controller
{
    public function create_like_on_post(Request $request, $id) {
        $user = $this->checkLogIn($request);
        if(!$user) {
            return response([
                'message' => 'User is not logged in'
            ]);
        }
        else {
            $user = JWTAuth::toUser(JWTAuth::getToken());
            $like = $request->input('type');
            if($like == 'like') {
                $rating = DB::table('posts')->where('id', $id)->value('rating');
                $rating += 1;
                DB::table('posts')->where('id', $id)->update([
                    'rating' => $rating
                ]);
                $post_creator_id = DB::table('posts')->where('id', $id)->value('user_id');
                $user_rating = DB::table('users')->where('id', $post_creator_id)->value('rating');
                $user_rating += 1;
                DB::table('users')->where('id', $post_creator_id)->update([
                    'rating' => $user_rating
                ]);
                return response([
                    'message' => 'Like created'
                ]);
            }
            else {
                $rating = DB::table('posts')->where('id', $id)->value('rating');
                $rating -= 1;
                DB::table('posts')->where('id', $id)->update([
                    'rating' => $rating
                ]);
                $post_creator_id = DB::table('posts')->where('id', $id)->value('user_id');
                $user_rating = DB::table('users')->where('id', $post_creator_id)->value('rating');
                $user_rating -= 1;
                DB::table('users')->where('id', $post_creator_id)->update([
                    'rating' => $user_rating
                ]);
                return response([
                    'message' => 'Dislike created'
                ]);
            }
        }
    }
    public function create_like_on_comment(Request $request, $id) {
        $user = $this->checkLogIn($request);
        if(!$user) {
            return response([
                'message' => 'User is not logged in'
            ]);
        }
        else {
            $user = JWTAuth::toUser(JWTAuth::getToken());
            $like = $request->input('type');
            if($like == 'like') {
                $rating = DB::table('comments')->where('id', $id)->value('rating');
                $rating += 1;
                DB::table('comments')->where('id', $id)->update([
                    'rating' => $rating
                ]);
                $post_creator_id = DB::table('comments')->where('id', $id)->value('user_id');
                $user_rating = DB::table('users')->where('id', $post_creator_id)->value('rating');
                $user_rating += 1;
                DB::table('users')->where('id', $post_creator_id)->update([
                    'rating' => $user_rating
                ]);
                return response([
                    'message' => 'Like created',
                ]);
            }
            else {
                $rating = DB::table('comments')->where('id', $id)->value('rating');
                $rating -= 1;
                DB::table('comments')->where('id', $id)->update([
                    'rating' => $rating
                ]);
                $post_creator_id = DB::table('comments')->where('id', $id)->value('user_id');
                $user_rating = DB::table('users')->where('id', $post_creator_id)->value('rating');
                $user_rating -= 1;
                DB::table('users')->where('id', $post_creator_id)->update([
                    'rating' => $user_rating
                ]);
                return response([
                    'message' => 'Dislike created'
                ]);
            }
        }
    }

    public function get_likes_on_comment($id) {
        $likes = DB::table('likes')->where('comment_id', $id)->get();
        return $likes;
    }
}

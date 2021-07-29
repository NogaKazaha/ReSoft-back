<?php

namespace App\Http\Controllers;

use App\Models\Like;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;
use Carbon\Carbon;

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
            $rating = DB::table('posts')->where('id', $id)->value('rating');
            $post_creator_id = DB::table('posts')->where('id', $id)->value('user_id');
            $user_rating = DB::table('users')->where('id', $post_creator_id)->value('rating');
            if($like == 'like') {
                $rating += 1;
                $user_rating += 1; 
                $message = 'Like created';  
            }
            else {
                $rating -= 1;
                $user_rating -= 1;
                $message = 'Dislike created';
            }
            if(DB::table('post_likes')->where('user_id', $user->id)->where('post_id', $id)->value('type')) {
                return response([
                    'message' => 'Already has mark from this user'
                ]);
            }
            DB::table('posts')->where('id', $id)->update([
                'rating' => $rating
            ]);
            DB::table('users')->where('id', $post_creator_id)->update([
                'rating' => $user_rating
            ]);
            DB::table('post_likes')->insert([
                'user_id' => $user->id,
                'post_id' => $id,
                'type' => $like,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ]);
            return response([
                'message' => $message
            ]);
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
            $rating = DB::table('comments')->where('id', $id)->value('rating');
            $post_creator_id = DB::table('comments')->where('id', $id)->value('user_id');
            $user_rating = DB::table('users')->where('id', $post_creator_id)->value('rating');
            if($like == 'like') {
                $rating += 1;
                $user_rating += 1;
                $message = 'Like created';  
            }
            else {
                $rating -= 1;
                $user_rating -= 1;
                $message = 'Like created';  
            }
            if(DB::table('comment_likes')->where('user_id', $user->id)->where('comment_id', $id)->value('type')) {
                return response([
                    'message' => 'Already has mark from this user'
                ]);
            }
            DB::table('comments')->where('id', $id)->update([
                'rating' => $rating
            ]);
            DB::table('users')->where('id', $post_creator_id)->update([
                'rating' => $user_rating
            ]);
            DB::table('comment_likes')->insert([
                'user_id' => $user->id,
                'comment_id' => $id,
                'type' => $like,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ]);
            return response([
                'message' => $message
            ]);
        }
    }

    public function get_likes_on_comment($id) {
        $likes = DB::table('comment_likes')->where('comment_id', $id)->get();
        return $likes;
    }
    public function get_likes_on_post($id) {
        $likes = DB::table('post_likes')->where('post_id', $id)->get();
        return $likes;
    }
    public function delete_like_on_post(Request $request, $id) {
        $user = $this->checkLogIn($request);
        if(!$user) {
            return response([
                'message' => 'User is not logged in'
            ]);
        }
        $user = JWTAuth::toUser(JWTAuth::getToken());
        $like = DB::table('post_likes')->where('post_id', $id)->where('user_id', $user->id)->value('id');
        $type = DB::table('post_likes')->where('user_id', $user->id)->where('post_id', $id)->value('type');
        if(!$like) {
            return response([
                'message' => 'No such like on post'
            ]);
        }
        $rating = DB::table('users')->where('id', $user->id)->value('rating');
        $post_rating = DB::table('posts')->where('id', $id)->value('rating');
        if($type == 'like') {
            $rating -= 1;
            $post_rating -= 1;
        }
        else {
            $rating += 1;
            $post_rating += 1;
        }
        DB::table('users')->where('id', $user->id)->update([
            'rating' => $rating
        ]);
        DB::table('posts')->where('id', $id)->update([
            'rating' => $post_rating
        ]);
        DB::table('post_likes')->where('post_id', $id)->where('user_id', $user->id)->delete();
        return response([
            'message' => 'Like removed'
        ]);
    }
    public function delete_like_on_comment(Request $request, $id) {
        $user = $this->checkLogIn($request);
        if(!$user) {
            return response([
                'message' => 'User is not logged in'
            ]);
        }
        $user = JWTAuth::toUser(JWTAuth::getToken());
        $like = DB::table('comment_likes')->where('comment_id', $id)->where('user_id', $user->id)->value('id');
        $type = DB::table('comment_likes')->where('user_id', $user->id)->where('comment_id', $id)->value('type');
        if(!$like) {
            return response([
                'message' => 'No such like on comment'
            ]);
        }
        $rating = DB::table('users')->where('id', $user->id)->value('rating');
        $comment_rating = DB::table('comments')->where('id', $id)->value('rating');
        if($type == 'like') {
            $rating -= 1;
            $comment_rating -= 1;
        }
        else {
            $rating += 1;
            $comment_rating += 1;
        }
        DB::table('users')->where('id', $user->id)->update([
            'rating' => $rating
        ]);
        DB::table('comments')->where('id', $id)->update([
            'rating' => $comment_rating
        ]);
        DB::table('comment_likes')->where('comment_id', $id)->where('user_id', $user->id)->delete();
        return response([
            'message' => 'Like removed'
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Like;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;

class LikesController extends Controller
{
    public function get_likes_on_comment($id) {
        $likes = DB::table('likes')->where('comment_id', $id)->get();
        return $likes;
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
            $user_id = $user->id;
            $comment = DB::table('comments')->where('id', $id)->get();
            $comment_user = $comment->user_id;
            $like = new Like;
            $like_type = $request['type'];
            if($like_type == 'like') {
                $current_raing = Db::table('users')->where('id', $comment_user)->pluck('rating');
                $new_rating = $current_raing + 1;
                Db::table('users')->where('id', $comment_user)->update([
                    'rating' => $new_rating
                ]);
                $like->type = $like_type;
                $like->user_id = $user_id;
                $like->comment_id = $comment->id;
                $like->save();
                return $like;
            }
            else {
                $current_raing = Db::table('users')->where('id', $comment_user)->pluck('rating');
                $new_rating = $current_raing - 1;
                Db::table('users')->where('id', $comment_user)->update([
                    'rating' => $new_rating
                ]);
                $like->type = $like_type;
                $like->user_id = $user_id;
                $like->comment_id = $comment->id;
                $like->save();
                return $like;
            }
        }
    }
}

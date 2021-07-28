<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class CommentsController extends Controller
{
    public function index(Request $request)
    {
        $all_comments = Comment::all();
        $all_comments = $this->sort_by($request, $all_comments);
        return $all_comments;
    }

    public function store(Request $request, $id)
    {
        $user = $this->checkLogIn($request);
        if(!$user) {
            return response([
                'message' => 'User is not logged in'
            ]);
        }
        else {
            $user = JWTAuth::toUser(JWTAuth::getToken());
            $user_id = $user->id;
            $creditianals = [
                'user_id' => $user_id,
                'post_id' => $id,
                'content' => $request->input('content')
            ];
            $create_comment = Comment::create($creditianals);
            return response([
                'message' => 'Comment created',
                'comment' => $create_comment
            ]);
        }
    }

    public function show($id)
    {
        $show_comment = Comment::find($id);
        return $show_comment;
    }

    public function update(Request $request, $id)
    {
        $user = $this->checkLogIn($request);
        if(!$user) {
            return response([
                'message' => 'User is not logged in'
            ]);
        }
        $user = JWTAuth::toUser(JWTAuth::getToken());
        $user_id = $user->id;
        $creator_id = DB::table('comments')->where('id', $id)->value('user_id');
        if($user_id != $creator_id && !$this->checkAdmin($request)) {
            return response([
                'message' => 'You can not delete this post'
            ]);
        }
        else {
            $update_comment = Comment::find($id);
            $update_comment->update($request->all());
            return response([
                'message' => 'Comment update',
                'post' => $update_comment
            ]);
        }
    }

    public function destroy(Request $request, $id)
    {
        $user = $this->checkLogIn($request);
        if(!$user) {
            return response([
                'message' => 'User is not logged in'
            ]);
        }
        $user = JWTAuth::toUser(JWTAuth::getToken());
        $user_id = $user->id;
        $creator_id = DB::table('comments')->where('id', $id)->value('user_id');
        if($user_id != $creator_id && !$this->checkAdmin($request)) {
            return response([
                'message' => 'You can not delete this post'
            ]);
        }
        else {
            Comment::destroy($id);
            return response([
                'message' => 'Comment deleted'
            ]);
        }
    }
    public function sort_by(Request $request, $comments) {
        if($request->input('sort_by') == 'rating') {
            if($request->input('order_by') == 'desc') {
                $sort_by = $comments->sortByDesc('rating');
            }
            else {
                $sort_by = $comments->sortBy('rating');
            }
        }
        else if($request->input('sort_by') == 'date') {
            if($request->input('order_by') == 'desc') {
                $sort_by = $comments->sortByDesc('updated_at');
            }
            else {
                $sort_by = $comments->sortBy('updated_at');
            }
        }
        else {
            $sort_by = $comments;
        }
        return $sort_by;
    }
}

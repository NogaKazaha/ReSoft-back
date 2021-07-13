<?php

namespace App\Http\Controllers;

use App\Models\Post;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class PostsController extends Controller
{
    public function index()
    {
        $all_posts = Post::all();
        return $all_posts;
    }

    public function store(Request $request)
    {
        $user = $this->checkLogIn($request);
        if(!$user) {
            return response([
                'message' => 'User is not logged in'
            ]);
        }
        else {
            $user = JWTAuth::toUser(JWTAuth::getToken());
            $title = $request->input('title');
            $content = $request->input('content');
            $categories = $request->input('categories');
            $creditianals = [
                'user_id' => $user->id,
                'title' => $title,
                'content' => $content,
                'categories' => $categories
            ];
            $create_post = Post::create($creditianals);
            return response([
                'message' => 'Post created',
                'post' => $create_post
            ]);
        } 
    }

    public function show($id)
    {
        $show_post = Post::find($id);
        return $show_post;
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
        $creator_id = DB::table('posts')->where('id', $id)->value('user_id');
        if($user_id != $creator_id) {
            return response([
                'message' => 'You can not update this post'
            ]);
        }
        else if($user_id == $creator_id || $this->checkAdmin($request)) {
            $post = Post::find($id);
            $post->update($request->all());
            return response([
                'message' => 'Post update',
                'post' => $post
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
        $creator_id = DB::table('posts')->where('id', $id)->value('user_id');
        if($user_id != $creator_id) {
            return response([
                'message' => 'You can not delete this post'
            ]);
        }
        else if($user_id == $creator_id || $this->checkAdmin($request)) {
            Post::destroy($id);
            return response([
                'message' => 'Post deleted'
            ]);
        }   
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Post;
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
        $user = JWTAuth::toUser(JWTAuth::getToken());
        if(!$user) {
            return response([
                'message' => 'User is not logged in'
            ]);
        }
        $title = $request->input('title');
        $content = $request->input('content');
        $creditianals = [
            'user_id' => $user->id,
            'title' => $title,
            'content' => $content
        ];
        $create_post = Post::create($creditianals);
        return $create_post;
    }

    public function show($id)
    {
        $show_post = Post::find($id);
        return $show_post;
    }

    public function update(Request $request, $id)
    {
       $post = Post::find($id);
       $post->update($request->all());
       return $post; 
    }

    public function destroy($id)
    {
        $destroy_post = Post::destroy($id);
        return $destroy_post;
    }
}

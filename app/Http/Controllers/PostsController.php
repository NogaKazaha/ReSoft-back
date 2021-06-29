<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostsController extends Controller
{
    public function index()
    {
        $all_posts = Post::all();
        return $all_posts;
    }

    public function store(Request $request)
    {
        $create_post = Post::create($request->all());
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

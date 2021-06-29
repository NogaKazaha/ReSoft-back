<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;

class CommentsController extends Controller
{
    public function index()
    {
        $all_comments = Comment::all();
        return $all_comments;
    }

    public function store(Request $request)
    {
        $create_comment = Comment::create($request->all());
        return $create_comment;
    }

    public function show($id)
    {
        $show_comment = Comment::find($id);
        return $show_comment;
    }

    public function update(Request $request, $id)
    {
        $update_comment = Comment::find($id);
        $update_comment->update($request->all());
        return $update_comment;
    }

    public function destroy($id)
    {
        $destroy_comment = Comment::destroy($id);
        return $destroy_comment;
    }
}

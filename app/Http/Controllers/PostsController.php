<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

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
            $categories_arr = explode(' ',$categories);
            foreach($categories_arr as $category) {
                if(Category::where('title', $category)->exists()) {
                    continue;
                }
                $creditianals = [
                    'title' => $category,
                    'description' => 'Will be edit soon by admin'
                ];
                Category::create($creditianals);
            }
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
            $subscribers = DB::table('subscriptions')->where('post_id', $id)->pluck("user_id");
            foreach($subscribers as $subscriber_id) {
                $subscriber = User::find($subscriber_id);
                $this->send_notification($subscriber, $id);
            }
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

    public function send_notification(User $user, $post_id)
    {
        $link = 'http://127.0.0.1:8000/api/posts/show/'.$post_id;
        $data = [
            'username' => $user->username,
            'link' => $link
        ];
        Mail::send('notification', $data, function ($message) use ($user) {
            $message->to($user->email);
            $message->subject('New notification for subscribed post');
        });
        return "Notification sent";
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;

class SubscriptionsController extends Controller
{
    public function show_user_subscriptions($id) {
        $favorites = DB::table('subscriptions')->where('user_id', $id)->get();
        return response([
            'Subscriptions' => $favorites
        ]);
    }

    public function add_to_subscriptions(Request $request, $id) {
        $user = $this->checkLogIn($request);
        if(!$user) {
            return response([
                'message' => 'User is not logged in'
            ]);
        }
        else {
            $user = JWTAuth::toUser(JWTAuth::getToken());
            if(DB::table('subscriptions')->where('post_id', $id)->value('user_id') != $user->id ) {
                $creditanals = [
                    'user_id' => $user->id,
                    'post_id' => $id
                ];
                Subscription::create($creditanals);
                return response([
                    'message' => 'New subscription added'
                ]);
            }
            else {
                return response([
                    'message' => 'This post is already in your subscriptions'
                ]);
            }
        }
    }

    public function delete_from_subscriptions(Request $request, $id) {
        $user = $this->checkLogIn($request);
        if(!$user) {
            return response([
                'message' => 'User is not logged in'
            ]);
        }
        else {
            $user = JWTAuth::toUser(JWTAuth::getToken());
            if(DB::table('subscriptions')->where('post_id', $id)->where('user_id', $user->id)->get()) {
                DB::table('subscriptions')->where('post_id', $id)->where('user_id', $user->id)->delete();
                return response([
                    'message' => 'Subscription deleted'
                ]);
            }
            else {
                return response([
                    'message' => 'No such subscription'
                ]);
            }
        }
    }
    public function get_user_subs_ids(Request $request, $id) {
        $favorites = DB::table('subscriptions')->where('user_id', $id)->pluck('post_id');
        return $favorites;
    }
}

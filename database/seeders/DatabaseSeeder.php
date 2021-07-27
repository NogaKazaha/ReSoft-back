<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'username' => "NogaKazaha",
            'name' => 'Oleg',
            'email' => "nogakazahawork@gmail.com",
            'password' => Hash::make('qweasdzxc'),
            'role' => "admin",
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
        DB::table('users')->insert([
            'username' => "user1",
            'name' => Str::random(10),
            'email' => "user1@gmail.com",
            'password' => Hash::make('user1'),
            'role' => "user",
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
        DB::table('users')->insert([
            'username' => "user2",
            'name' => Str::random(10),
            'email' => "user2@gmail.com",
            'password' => Hash::make('user2'),
            'role' => "user",
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
        DB::table('users')->insert([
            'username' => "user3",
            'name' => Str::random(10),
            'email' => "user3@gmail.com",
            'password' => Hash::make('user3'),
            'role' => "user",
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
        DB::table('users')->insert([
            'username' => "user4",
            'name' => Str::random(10),
            'email' => "user4@gmail.com",
            'password' => Hash::make('user4'),
            'role' => "user",
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);


        DB::table('categories')->insert([
            'title' => "html",
            'description' => Str::random(10),
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
        DB::table('categories')->insert([
            'title' => "css",
            'description' => Str::random(10),
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
        DB::table('categories')->insert([
            'title' => "js",
            'description' => Str::random(10),
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
        DB::table('categories')->insert([
            'title' => "json",
            'description' => Str::random(10),
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
        DB::table('categories')->insert([
            'title' => "php",
            'description' => Str::random(10),
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);


        DB::table('posts')->insert([
            'user_id' => 1,
            'title' => Str::random(10),
            'rating' => 1,
            'content' => Str::random(10),
            'categories' => 'html css js',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
        DB::table('posts')->insert([
            'user_id' => 1,
            'title' => Str::random(10),
            'rating' => 2,
            'content' => Str::random(10),
            'categories' => 'html',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
        DB::table('posts')->insert([
            'user_id' => 2,
            'title' => Str::random(10),
            'rating' => -1,
            'content' => Str::random(10),
            'categories' => 'js',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
        DB::table('posts')->insert([
            'user_id' => 3,
            'title' => Str::random(10),
            'rating' => 0,
            'status' => 'inactive',
            'content' => Str::random(10),
            'categories' => 'php',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
        DB::table('posts')->insert([
            'user_id' => 4,
            'title' => Str::random(10),
            'rating' => 0,
            'content' => Str::random(10),
            'categories' => 'json',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
        
        
        DB::table('comments')->insert([
            'user_id' => 1,
            'post_id' => 2,
            'content' => Str::random(10),
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
        DB::table('comments')->insert([
            'user_id' => 2,
            'post_id' => 1,
            'rating' => 1,
            'content' => Str::random(10),
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
        DB::table('comments')->insert([
            'user_id' => 3,
            'post_id' => 1,
            'rating' => -1,
            'content' => Str::random(10),
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
        DB::table('comments')->insert([
            'user_id' => 1,
            'post_id' => 2,
            'content' => Str::random(10),
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
        DB::table('comments')->insert([
            'user_id' => 1,
            'post_id' => 3,
            'content' => Str::random(10),
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);


        DB::table('post_likes')->insert([
            'user_id' => 2,
            'post_id' => 1,
            'type' => 'like',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
        DB::table('post_likes')->insert([
            'user_id' => 2,
            'post_id' => 2,
            'type' => 'like',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
        DB::table('post_likes')->insert([
            'user_id' => 3,
            'post_id' => 2,
            'type' => 'like',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
        DB::table('post_likes')->insert([
            'user_id' => 1,
            'post_id' => 3,
            'type' => 'dislike',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
        DB::table('comment_likes')->insert([
            'user_id' => 1,
            'comment_id' => 2,
            'type' => 'like',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
        DB::table('comment_likes')->insert([
            'user_id' => 1,
            'comment_id' => 3,
            'type' => 'dislike',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\PostLikeRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

use Illuminate\Support\Facades\DB;

class PostLikeCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation { update as traitUpdate; }

    public function setup()
    {
        CRUD::setModel(\App\Models\PostLike::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/post-like');
        CRUD::setEntityNameStrings('post like', 'post likes');
    }

    protected function setupListOperation()
    {
        CRUD::removeButton('delete');
        CRUD::column('id');
        CRUD::column('user_id');
        CRUD::column('post_id');
        CRUD::column('type');
    }
    protected function setupShowOperation()
    {
        CRUD::removeButton('delete');
        CRUD::column('id');
        CRUD::column('user_id');
        CRUD::column('post_id');
        CRUD::column('type');
    }

    protected function setupCreateOperation()
    {
        CRUD::setValidation(PostLikeRequest::class);

        CRUD::removeButton('delete');
        CRUD::setFromDb(); 
        CRUD::modifyField('type', [
            'type' => 'enum',
        ]);
    }

    protected function setupUpdateOperation()
    {
        CRUD::addField([
            'name' => 'type',
            'type' => 'enum',
            'lable' => 'Type'
        ]);
    }

    public function store() {
        $author_id = request()->user_id;
        $type = request()->type;
        $post_rate = DB::table('posts')->where('id', request()->post_id)->value('rating');
        $post_creator = DB::table('posts')->where('id', request()->post_id)->value('user_id');
        $creator_rate = DB::table('users')->where('id', $post_creator)->value('rating');
        if($type == 'like') {
            DB::table('post_likes')->insert([
                'post_id' => request()->post_id,
                'user_id' => $author_id,
                'type' => 'like'
            ]);
            DB::table('posts')->where('id', request()->post_id)->update([
                'rating' => $post_rate + 1
            ]);
            DB::table('users')->where('id', $post_creator)->update([
                'rating' => $creator_rate + 1
            ]);
        }
        else {
            DB::table('post_likes')->insert([
                'post_id' => request()->post_id,
                'user_id' => $author_id,
                'type' => 'dislike'
            ]);
            DB::table('posts')->where('id', request()->post_id)->update([
                'rating' => $post_rate - 1
            ]);
            DB::table('users')->where('id', $post_creator)->update([
                'rating' => $creator_rate - 1
            ]);
        }
        return redirect('/admin/post-like');
    }

    public function update()
    {
        $post_like = CRUD::getCurrentEntry();
        $response = $this->traitUpdate();
        $post_like->update(request()->all());
        if($post_like->type == 'like') {
            DB::table('post_likes')->where('post_id', $post_like->post_id)->where('user_id', $post_like->user_id)->update([
                'type' => 'like'
            ]);
            $post_rate = DB::table('posts')->where('id', $post_like->post_id)->value('rating');
            DB::table('posts')->where('id', $post_like->post_id)->update([
                'rating' => $post_rate + 2
            ]);
            $user_id =  DB::table('posts')->where('id', $post_like->post_id)->value('user_id');
            $user_rating = DB::table('users')->where('id', $user_id)->value('rating');
            DB::table('users')->where('id', $user_id)->update([
                'rating' => $user_rating + 2
            ]);
        }
        else {
            DB::table('post_likes')->where('post_id', $post_like->post_id)->where('user_id', $post_like->user_id)->update([
                'type' => 'dislike'
            ]);
            $post_rate = DB::table('posts')->where('id', $post_like->post_id)->value('rating');
            DB::table('posts')->where('id', $post_like->post_id)->update([
                'rating' => $post_rate - 2
            ]);
            $user_id =  DB::table('posts')->where('id', $post_like->post_id)->value('user_id');
            $user_rating = DB::table('users')->where('id', $user_id)->value('rating');
            DB::table('users')->where('id', $user_id)->update([
                'rating' => $user_rating - 2
            ]);
        }
        return $response;
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CommentLikeRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

use Illuminate\Support\Facades\DB;

class CommentLikeCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation { update as traitUpdate; }
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation { destroy as traitDestroy; }

    public function setup()
    {
        CRUD::setModel(\App\Models\CommentLike::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/comment-like');
        CRUD::setEntityNameStrings('comment like', 'comment likes');
    }

    protected function setupListOperation()
    {
        CRUD::removeButton('delete');
        CRUD::column('id');
        CRUD::column('user_id');
        CRUD::column('comment_id');
        CRUD::column('type');
    }

    protected function setupCreateOperation()
    {
        CRUD::setValidation(CommentLikeRequest::class);

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
        $comment_rate = DB::table('comments')->where('id', request()->comment_id)->value('rating');
        $comment_creator = DB::table('comments')->where('id', request()->comment_id)->value('user_id');
        $creator_rate = DB::table('users')->where('id', $comment_creator)->value('rating');
        if($type == 'like') {
            DB::table('comment_likes')->insert([
                'comment_id' => request()->comment_id,
                'user_id' => $author_id,
                'type' => 'like'
            ]);
            DB::table('comments')->where('id', request()->comment_id)->update([
                'rating' => $comment_rate + 1
            ]);
            DB::table('users')->where('id', $comment_creator)->update([
                'rating' => $creator_rate + 1
            ]);
        }
        else {
            DB::table('comment_likes')->insert([
                'comment_id' => request()->comment_id,
                'user_id' => $author_id,
                'type' => 'dislike'
            ]);
            DB::table('comments')->where('id', request()->comment_id)->update([
                'rating' => $comment_rate - 1
            ]);
            DB::table('users')->where('id', $comment_creator)->update([
                'rating' => $creator_rate - 1
            ]);
        }
        return redirect('/admin/comment-like');
    }

    public function update()
    {
        $comment_like = CRUD::getCurrentEntry();
        $response = $this->traitUpdate();
        $comment_like->update(request()->all());
        if($comment_like->type == 'like') {
            DB::table('comment_likes')->where('comment_id', $comment_like->comment_id)->where('user_id', $comment_like->user_id)->update([
                'type' => 'like'
            ]);
            $comment_rate = DB::table('comments')->where('id', $comment_like->comment_id)->value('rating');
            DB::table('comments')->where('id', $comment_like->comment_id)->update([
                'rating' => $comment_rate + 2
            ]);
            $user_id =  DB::table('comments')->where('id', $comment_like->comment_id)->value('user_id');
            $user_rating = DB::table('users')->where('id', $user_id)->value('rating');
            DB::table('users')->where('id', $user_id)->update([
                'rating' => $user_rating + 2
            ]);
        }
        else {
            DB::table('comment_likes')->where('comment_id', $comment_like->comment_id)->where('user_id', $comment_like->user_id)->update([
                'type' => 'dislike'
            ]);
            $comment_rate = DB::table('comments')->where('id', $comment_like->comment_id)->value('rating');
            DB::table('comments')->where('id', $comment_like->comment_id)->update([
                'rating' => $comment_rate - 2
            ]);
            $user_id =  DB::table('comments')->where('id', $comment_like->comment_id)->value('user_id');
            $user_rating = DB::table('users')->where('id', $user_id)->value('rating');
            DB::table('users')->where('id', $user_id)->update([
                'rating' => $user_rating - 2
            ]);
        }
        return $response;
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\PostRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PostCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation { update as traitUpdate; }

    public function setup()
    {
        CRUD::setModel(\App\Models\Post::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/post');
        CRUD::setEntityNameStrings('post', 'posts');
    }

    protected function setupListOperation()
    {
        CRUD::column('id');
        CRUD::column('user_id');
        CRUD::column('title');
        CRUD::column('content');
        CRUD::column('categories');
        CRUD::column('status');
    }

    protected function setupShowOperation()
    {
        CRUD::column('id');
        $posts = CRUD::getCurrentEntry();
        $plus = DB::table("post_likes")->where("post_id", $posts->id)->where("type", 'like')->count();
        $minus = DB::table("post_likes")->where("post_id", $posts->id)->where("type", 'dislike')->count();
        $posts->likes = $plus - $minus;
        CRUD::column('user_id');
        CRUD::column('title');
        CRUD::column('content');
        CRUD::column('categories');
        CRUD::column('status');
        CRUD::column('likes');
        CRUD::modifyColumn('likes', [
            'label' => 'Rating',
            'type' => 'integer',
            'name' => 'likes',
        ]);
        CRUD::column('created_at');
    }

    protected function setupCreateOperation()
    {
        CRUD::setValidation(PostRequest::class);

        CRUD::field('user_id');
        CRUD::field('title');
        CRUD::field('content');
        CRUD::field('categories');
        CRUD::field('status');
        CRUD::modifyField('status', [
            'type' => 'enum',
        ]);
    }

    protected function setupUpdateOperation()
    {
        CRUD::field('title');
        CRUD::field('content');
        CRUD::field('categories');
        CRUD::field('status');
        CRUD::modifyField('status', [
            'type' => 'enum',
        ]);
    }

    public function store() {
        $post = Post::create(request()->all());
        $categories_arr = explode(' ',request()->categories);
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
            foreach($categories_arr as $category) {
                $id = Category::where('title', $category)->value('id');
                $creditianals = [
                    'post_id' => $post->id,
                    'category_id' => $id,
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
                ];
                DB::table('posts_categories_ids')->insert($creditianals);
            }

        return redirect('/admin/post/');
    }

    public function update()
    {
        request()->validate([
            'status' => 'in:active,inactive'
        ]);

        $post = CRUD::getCurrentEntry();
        $response = $this->traitUpdate();
        $post->update(request()->all());
        if(request()->categories){
            DB::table('posts_categories_ids')->where('post_id', $post->id)->delete();
            $new_categories = request()->categories;
                $categories_arr = explode(' ',$new_categories);
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
                foreach($categories_arr as $category) {
                    $cat_id = Category::where('title', $category)->value('id');
                    $creditianals = [
                        'post_id' => $post->id,
                        'category_id' => $cat_id,
                        'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                        'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
                    ];
                    DB::table('posts_categories_ids')->insert($creditianals);
                }
        }

        return $response;

    }
}

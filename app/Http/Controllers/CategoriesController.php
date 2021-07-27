<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoriesController extends Controller
{
    public function index()
    {
        $all_categories = Category::all();
        return $all_categories;
    }

    public function store(Request $request)
    {
        if($this->checkAdmin($request)) {
            $create_category = Category::create($request->all());
            return response([
                'message' => 'Category created',
                'category' => $create_category
            ]);
        }
        else {
            return response([
                'message' => 'You can not create categories'
            ]);
        }
        
    }

    public function show($id)
    {
        $show_category = Category::find($id);
        return $show_category;
    }

    public function update(Request $request, $id)
    {
        if($this->checkAdmin($request)) {
            $update_category = Category::find($id);
            $update_category->update($request->all());
            return response([
                'message' => 'Category updated',
                'category' => $update_category
            ]);
        }
        else {
            return response([
                'message' => 'You can not update categories'
            ]);
        }
    }

    public function destroy(Request $request, $id)
    {
        if($this->checkAdmin($request)) {
            Category::destroy($id);
            return response([
                'message' => 'Category deleted'
            ]);
        }
        else {
            return response([
                'message' => 'You can not delete category'
            ]);
        }
    }

    public function get_post_categories($id) {
        $categories = DB::table('posts')->where('id', $id)->value('categories');
        $categories_arr = explode(' ',$categories);
        return response([
            'categories' => $categories_arr
        ]);
    }
}

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
        $create_category = Category::create($request->all());
        return $create_category;
    }

    public function show($id)
    {
        $show_category = Category::find($id);
        return $show_category;
    }

    public function update(Request $request, $id)
    {
        $update_category = Category::find($id);
        $update_category->update($request->all());
        return $update_category;
    }

    public function destroy($id)
    {
        $destroy_category = Category::destroy($id);
        return $destroy_category;
    }
}

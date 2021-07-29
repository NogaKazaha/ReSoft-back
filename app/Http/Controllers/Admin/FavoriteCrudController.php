<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\FavoriteRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

use App\Models\Favorite;

class FavoriteCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        CRUD::setModel(\App\Models\Favorite::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/favorite');
        CRUD::setEntityNameStrings('favorite', 'favorites');
    }

    protected function setupListOperation()
    {
        CRUD::column('id');
        CRUD::column('user_id');
        CRUD::column('post_id');
    }

    protected function setupShowOperation()
    {
        CRUD::column('id');
        CRUD::column('user_id');
        CRUD::column('post_id');
    }

    protected function setupCreateOperation()
    {
        CRUD::setValidation(FavoriteRequest::class);

        CRUD::field('user_id');
        CRUD::field('post_id');
    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}

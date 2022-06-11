<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

use App\Models\User;
use App\Models\Categories;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    function create_user(){
        return User::factory()->create();
    }

    function create_categories_from_seeder(){
        $this->seed('CategoriesTableSeeder');
        return Categories::all();
    }

    function create_category($category_name){
        return Categories::create(['name'=>$category_name]);
    }
}

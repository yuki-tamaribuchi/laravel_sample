<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    public function test_store_category(){
        $data = ['name' => 'sample'];
        $this->json('POST', 'api/category', $data, ['Accept' => 'application/json'])
            ->assertStatus(201)
            ->assertJsonStructure(['name']);
    }

    public function test_store_category_already_exists(){
        $category_name = 'sample';
        $category = $this->create_category('sample');
        $data = ['name' => 'sample'];
        $this->json('POST', 'api/category', $data, ['Accept' => 'application/json'])
            ->assertStatus(409)
            ->assertJsonStructure(['message']);
    }

    public function test_store_category_wrong_key(){
        $data = ['wrong_name' => 'sample'];
        $this->json('POST', 'api/category', $data, ['Accept' => 'application/json'])
            ->assertStatus(400)
            ->assertJsonStructure(['message']);
    }
}
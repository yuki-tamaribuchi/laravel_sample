<?php

namespace Tests\Feature\Database;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class DatabaseTest extends TestCase
{
    public function test_database(){
        $this->assertTrue(
            Schema::hasColumns('users', [
                'biograph',
                'last_login_at'
            ]),
            true
        );

        $this->assertTrue(
            Schema::hasColumns('posts', [
                'title',
                'content'
            ]),
            true
        );

        $this->assertTrue(
            Schema::hasColumns('categories', [
                'name'
            ]));

        $this->assertTrue(
            Schema::hasColumns('post_category', [
                'post_id',
                'category_id'
            ]));
    }
    
}

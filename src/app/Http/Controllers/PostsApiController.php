<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;

class PostsApiController extends Controller
{
    function __construct(){
        $this->posts_table = DB::table('posts');
        $this->post_category_table = DB::table('post_category');
    }

    public function index(){
        $posts = $this->posts_table->get();
        $posts = json_encode($posts);
        return response()->json(['posts' => $posts]);
    }
}

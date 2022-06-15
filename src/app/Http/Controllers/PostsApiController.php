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

    public function show(Request $request){
        $post = $this->posts_table
            ->join('post_category','posts.id', '=', 'post_category.post_id')
            ->join('categories', 'post_category.category_id', '=', 'categories.id')
            ->join('users', 'posts.user_id', '=', 'users.id')
            ->where('posts.id', $request->post)
            ->get();
        if ($post->isEmpty()){
            return response()->json(null, Response::HTTP_NOT_FOUND);
        }
        $post = json_encode($post);
        return response()->json(['post'=>$post]);
    }
}

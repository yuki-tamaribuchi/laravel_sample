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

    public function store(Request $request){
        $request_content = $request->getContent();
        $request_content = json_decode($request_content, true);
        if(($validation_result=$this->validation($request_content))!=null){
            return $validation_result;
        }
        $title = $request_content['title'];
        $content = $request_content['content'];
        $user_id = $request_content['user_id'];
        $categories = $request_content['categories'];
        
        try{
            $this->posts_table->insert([
                [
                    'title'=>$title,
                    'content'=>$content,
                    'user_id'=>$user_id,
                    'created_at'=>date('Y-m-d H:i:s'),
                    'updated_at'=>date('Y-m-d H:i:s')
                ]
            ]);
            $post = $this->posts_table->where('title', $title)->where('content', $content)->where('user_id', $user_id)->orderBy('id', 'desc')->first();
            $categories_data = $this->create_categories_data($categories, $post->id);
            $categories = $this->post_category_table->insert($categories_data);
        } catch(Exception $e) {
            $this->posts_table->where('id', $post->id)->delete();
        }
        $post = json_encode($post);
        return response()->json(['post'=>$post], Response::HTTP_CREATED);
    }
}

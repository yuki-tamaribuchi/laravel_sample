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

    public function update(Request $request){
        $request_content = $request->getContent();
        $request_content = json_decode($request_content, true);
        if(($validation_result=$this->validation($request_content))!=null){
            return $validation_result;
        }
        $post_id = $request->post;
        $title = $request_content['title'];
        $content = $request_content['content'];
        $categories = $request_content['categories'];

        try{
            $this->posts_table->where('id', $post_id)->update([
                'title'=>$title,
                'content'=>$content,
                'updated_at'=>date('Y-m-d H:i:s')
            ]);
            $this->post_category_table->where('post_id', $post_id)->delete();
            $categories_data = $this->create_categories_data($categories, $post_id);
            $categories = $this->post_category_table->insert($categories_data);
        } catch(Exceptions $e){

        }
    }

    public function destroy(Request $request){
        $this->post_category_table->where('post_id', $request->post)->delete();
        $this->posts_table->where('id', $request->post)->delete();
    }

    private function validation($content){
        if (($validation_result=$this->key_validation($content))!=null){
            return $validation_result;
        }

        if (($validation_result=$this->value_validation($content))!=null){
            return $validation_result;
        }
    }

    private function key_validation($content){
        if (!array_key_exists('title', $content)){
            return $this->key_error_response('title');
        }

        if (!array_key_exists('content', $content)){
            return $this->key_error_response('content');
        }

        if (!array_key_exists('user_id', $content)){
            return $this->key_error_response('user_id');
        }

        if (!array_key_exists('categories', $content)){
            return $this->key_error_response('categories');
        }
    }

    private function key_error_response($key_name){
        return response()->json(['message' => str_replace('KEY_NAME', $key_name, "Please specify KEY_NAME.")], Response::HTTP_BAD_REQUEST);
    }

    private function value_validation($content){
        if (gettype($content['user_id'])!='integer'){
            return $this->value_error_response('user_id as integer');
        }

        if (!$this->validate_categories($content['categories'])){
            return $this->value_error_response('categories as array of integer');
        }
    }

    private function value_error_response($message){
        return response()->json(['message' => str_replace('MESSAGE', $message, "Please specify MESSAGE.")], Response::HTTP_BAD_REQUEST);
    }

    private function validate_categories($categories){
        if (gettype($categories)!='array'){
            return false;
        }

        foreach ($categories as $category){
            if (gettype($category)!='integer'){
                return false;
            }
        }
        return true;
    }

    private function create_categories_data($categories, $post_id){
        $categories_data = [];
        foreach($categories as $category){
            array_push($categories_data, ['post_id'=>$post_id, 'category_id'=>$category]);
        }
        return $categories_data;
    }

    private function get_post($post_id){
        
    }
}

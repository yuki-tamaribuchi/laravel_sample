<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Posts;
use App\Models\Categories;

class PostsController extends Controller
{
    public function __construct(){
        $this->middleware('auth', ['only'=>['create', 'store', 'destroy-confirm', 'destroy']]);
    }

    public function create(){
        $post = new Posts();
        $categories = Categories::all();
        return view('posts.create', ['post' => $post, 'categories' => $categories]);
    }

    public function store(Request $request){
        $validator = $request->validate([
            'title' => 'required',
            'content' => 'required'
        ]);

        $title = $request->get('title');
        $content = $request->get('content');
        $categories = $request->get('categories');
        $user_id = Auth::user()->id;

        $post = Posts::create([
            'title' => $title,
            'content' => $content,
            'user_id' => $user_id
        ]);
        $post->categories()->sync($categories);

        return redirect(route('posts.show', ["post" => $post->id]));
    }

    public function show(Request $request){
        $post = Posts::getPostById($request->post);
        
        if ($post){
            return view('posts.show', ["post" => $post]);
        } else {
            return abort(404);
        }
    }

    public function edit(Request $request){
        $post = Posts::getPostById($request->post);
        $categories = Categories::all();
        if (Auth::user()->id == $post->user_id){
            return view('posts.edit', ['post' => $post, 'categories' => $categories]);
        } else {
            return abort(403);
        }
    }

    public function update(Request $request){
        $validator = $request->validate([
            'title'=>'required',
            'content'=>'required'
        ]);
        $post = Posts::getPostById($request->post);

        if (Auth::user()->id == $post->user_id){
            $title = $request->get('title');
            $content = $request->get('content');
            $categories = $request->get('categories');
            $post->title = $title;
            $post->content = $content;
            $post->save();
            $post->categories()->sync($categories);

            return redirect(route('posts.show', ['post'=>$post->id]));
        } else {
            return abort(403);
        }
    }

    public function destroy_confirm(Request $request){
        $post = Posts::getPostById($request->post);
        if (Auth::user()->id == $post->user_id){
            return view('posts.destroy-confirm', ['post'=>$post]);
        } else {
            return abort(403);
        }
    }

    public function destroy(Request $request){
        $post = Posts::getPostById($request->post);
        if (Auth::user()->id == $post->user_id){
            Posts::where('id', $post->id)->delete();
            return view('posts.destroy-completed');
        } else {
            return abort(403);
        }
    }
}
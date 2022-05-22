<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Posts;

class PostsController extends Controller
{
    public function create(){
        return view('posts.create');
    }

    public function store(Request $request){
        $validator = $request->validate([
            'title' => 'required',
            'content' => 'required'
        ]);

        $title = $request->get('title');
        $content = $request->get('content');
        $user_id = Auth::user()->id;

        $post = Posts::create([
            'title' => $title,
            'content' => $content,
            'user_id' => $user_id
        ]);

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
        if (Auth::user()->id == $post->user_id){
            return view('posts.edit', ['post' => $post]);
        } else {
            return abort(403);
        }
    }
}
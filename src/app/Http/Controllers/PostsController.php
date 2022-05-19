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
}

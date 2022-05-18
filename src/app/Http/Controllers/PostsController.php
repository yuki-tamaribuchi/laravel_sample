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
}

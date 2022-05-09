<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HelloController extends Controller
{
    public function getHello(Request $request){
        $name = $request->input('name');
        return view('hello')->with('name', $name);
    }
}

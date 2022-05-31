<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \Symfony\Component\HttpFoundation\Response;

use App\Models\Categories;

class CategoryController extends Controller
{
    public function store(Request $reqeust){
        $content = $reqeust->getContent();
        $json = json_decode($content, true);
        
        if (array_key_exists('name', $json)){
            $name = $json['name'];

            $category_in_db = Categories::where('name', $name)->first();
            if ($category_in_db == null){
                $category = Categories::create([
                    'name'=>$name
                ]);
                return response()->json(['name' => $category->name], Response::HTTP_CREATED);
            } else {
                return response()->json(['message' => str_replace('NAME', $name, 'Category "NAME" is already exists.')], Response::HTTP_CONFLICT);
            }   
        } else {
            return response()->json(['message' => 'Please specify category name as {"name": "YOUR_CATEGORY_NAME"}.'], Response::HTTP_BAD_REQUEST);
        }
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\User;
use App\Models\Categories;

class Posts extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'user_id'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function categories(){
        return $this->belongsToMany(Categories::class, 'post_category', 'post_id', 'category_id');
    }

    public static function getPostById($id){
        $post = self::with('user', 'categories')->where('id', $id)->first();
        return $post;
    }
}

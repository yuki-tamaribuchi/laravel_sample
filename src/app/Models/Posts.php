<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Http\Models\User;

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

    public static function getPostById($id){
        $post = self::where('id', $id)->first();
        return $post;
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Posts;

class Categories extends Model
{
    use HasFactory;

    protected $fillable = [
        'name'
    ];

    public function posts(){
        return $this->belongsToMany(Posts::class, 'post_category', 'category_id', 'post_id');
    }
}

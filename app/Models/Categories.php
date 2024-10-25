<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use TCG\Voyager\Models\Post;

class Categories extends Model
{
    use HasFactory;

    protected $fillable = [
        'parent_id',
        'name',
        'name_fr',
        'slug',
        'slug_fr',
        'image',
    ];

    public function posts()
    {
        return $this->hasMany(Post::class, 'category_id');
    }
}

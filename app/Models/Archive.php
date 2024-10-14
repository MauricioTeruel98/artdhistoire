<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use TCG\Voyager\Models\Post;

class Archive extends Model
{
    use HasFactory;

    protected $fillable = [
        'post_id',
        'route',
        'icon',
        'type',
        'title',
        'title_fr',
        'route_fr',
        'order'
    ];
    
    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}

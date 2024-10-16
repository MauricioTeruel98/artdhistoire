<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use TCG\Voyager\Models\Post;

class ArchivesEn extends Model
{
    protected $table = 'archives_en';

    use HasFactory;

    protected $fillable = [
        'post_id',
        'route',
        'icon',
        'type',
        'title',
        'order'
    ];
    
    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}

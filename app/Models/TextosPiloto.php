<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use TCG\Voyager\Models\Post;

class TextosPiloto extends Model
{
    protected $table = 'textos_piloto';
    use HasFactory;

    protected $fillable = [
        'title',
        'text_1',
        'text_2',
        'title_en',
        'text_1_en',
        'text_2_en',
    ];
    
    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}

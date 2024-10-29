<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VideoOnline extends Model
{
    use HasFactory;

    protected $table = 'videosonline';

    protected $fillable = [
        'title',
        'subtitle',
        'text',
        'texto_secondary',
        'title_fr',
        'subtitle_fr',
        'text_fr',
        'texto_secondary_fr',
        'bio',
        'image',
        'home_image'
    ];

    public function videos()
    {
        return $this->hasMany(Video::class, 'videoonline_id');
    }

    public function videosEn()
    {
        return $this->hasMany(VideoEn::class, 'videoonline_id');
    }
}

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
    ];

    public function videos()
    {
        return $this->hasMany(Video::class, 'videoonline_id');
    }
}

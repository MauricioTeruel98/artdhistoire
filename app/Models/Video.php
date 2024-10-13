<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use HasFactory;

    protected $table = 'videos';

    protected $fillable = [
        'iframe',
        'title',
        'text',
        'videoonline_id',
        'imagen',
        'video',
        'order',
        'title_fr',
        'text_fr',
    ];

    public function videoOnline()
    {
        return $this->belongsTo(VideoOnline::class, 'videoonline_id');
    }
}

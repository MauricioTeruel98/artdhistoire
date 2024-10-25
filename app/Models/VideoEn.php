<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VideoEn extends Model
{
    use HasFactory;

    protected $table = 'videos_en';

    protected $fillable = [
        'iframe',
        'title',
        'text',
        'videoonline_id',
        'imagen',
        'video',
        'order',
    ];

    public function videoOnline()
    {
        return $this->belongsTo(VideoOnline::class, 'videoonline_id');
    }
}

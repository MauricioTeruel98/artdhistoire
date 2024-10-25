<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TextosGenerales extends Model
{
    protected $table = 'textos_generales';
    use HasFactory;

    protected $fillable = [
        'texto_header',
        'texto_home',
        'texto_about_first',
        'texto_about_second',
        'texto_header_en',
        'texto_home_en',
        'texto_about_first_en',
        'texto_about_second_en'
    ];
}

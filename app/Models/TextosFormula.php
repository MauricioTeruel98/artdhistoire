<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use TCG\Voyager\Models\Post;

class TextosFormula extends Model
{
    use HasFactory;

    protected $table = 'textos_formulas';

    protected $fillable = [
        'formula_normal',
        'formula_normal_en',
        'formula_estudiante',
        'formula_estudiante_en',
        'formula_personalizada',
        'formula_personalizada_en',
        'texto_debajo_formula',
        'texto_debajo_formula_en'
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use TCG\Voyager\Models\Post;
use Illuminate\Support\Facades\Storage;

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
        'order',
    ];

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($category) {
            // Obtener todos los posts de la categoría
            $posts = $category->posts;

            foreach ($posts as $post) {
                // Obtener y eliminar los archivos PDF en francés
                $archives = $post->archives;
                foreach ($archives as $archive) {
                    if ($archive->route) {
                        Storage::disk('public')->delete($archive->route);
                    }
                    if ($archive->route_fr) {
                        Storage::disk('public')->delete($archive->route_fr);
                    }
                    $archive->delete();
                }

                // Obtener y eliminar los archivos PDF en inglés
                $archivesEn = $post->archivesEn;
                foreach ($archivesEn as $archive) {
                    if ($archive->route) {
                        Storage::disk('public')->delete($archive->route);
                    }
                    $archive->delete();
                }

                // Eliminar el post
                $post->delete();
            }
        });
    }

    public function posts()
    {
        return $this->hasMany(Post::class, 'category_id');
    }
}
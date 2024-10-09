<?php

namespace App\Http\Controllers;

use App\Models\Archive;
use Illuminate\Http\Request;
use TCG\Voyager\Models\Post;
use TCG\Voyager\Facades\Voyager;

class ArchiveController extends Controller
{
    public function index(Request $request)
    {
        $post_id = $request->query('post_id');
        $post = Post::findOrFail($post_id);
        $archives = $post->archives;

        return Voyager::view('voyager::archives.index', compact('post', 'archives'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'post_id' => 'required|exists:posts,id',
            'title' => 'required|string|max:255',
            'file' => 'required|file|mimes:pdf',
            'type' => 'required|string|in:nonDisponible,contexto,teoria,bio,social',
        ]);

        $file = $request->file('file');
        $path = $file->store('archives', 'public');

        Archive::create([
            'post_id' => $request->post_id,
            'title' => $request->title,
            'route' => $path,
            'type' => $request->type,
        ]);

        return redirect()->back()->with('success', 'Archivo subido correctamente.');
    }

    public function destroy(Archive $archive)
    {
        $post_id = $archive->post_id;
        \Storage::disk('public')->delete($archive->route);
        $archive->delete();
    
        $post = Post::findOrFail($post_id);
        $archives = $post->archives;
    
        return view('vendor.voyager.archives.index', compact('post', 'archives'))
            ->with('success', 'Archivo eliminado correctamente.');
    }
}
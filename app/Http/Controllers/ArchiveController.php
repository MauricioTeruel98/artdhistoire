<?php

namespace App\Http\Controllers;

use App\Models\Archive;
use Illuminate\Http\Request;
use TCG\Voyager\Models\Post;
use TCG\Voyager\Facades\Voyager;
use Illuminate\Support\Facades\Storage;

class ArchiveController extends Controller
{
    public function index(Request $request)
    {
        $post_id = $request->query('post_id');
        $post = Post::orderBy('order', 'DESC')->findOrFail($post_id);
        $archives = $post->archives()->orderBy('order', 'ASC')->get();

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

        return redirect()->back()->with('success', 'Archivos subidos correctamente.');
    }

    public function edit(Archive $archive)
    {
        $post = $archive->post;
        return Voyager::view('voyager::archives.edit', compact('archive', 'post'));
    }

    public function update(Request $request, Archive $archive)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'file' => 'nullable|file|mimes:pdf',
            'type' => 'required|string|in:nonDisponible,contexto,teoria,bio,social',
        ]);

        $archive->title = $request->title;
        $archive->title_fr = $request->title_fr;
        $archive->type = $request->type;

        if ($request->hasFile('file')) {
            Storage::disk('public')->delete($archive->route);
            $path = $request->file('file')->store('archives', 'public');
            $archive->route = $path;
        }

        if ($request->hasFile('file_fr')) {
            Storage::disk('public')->delete($archive->route_fr);
            $path_fr = $request->file('file_fr')->store('archives', 'public');
            $archive->route_fr = $path_fr;
        }

        $archive->save();

        return redirect()->back()
            ->with('success', 'Archivo actualizado correctamente.');
    }

    public function destroy(Archive $archive)
    {
        $post_id = $archive->post_id;

        // Verificar si la ruta del archivo existe antes de intentar eliminarlo
        if ($archive->route) {
            Storage::disk('public')->delete($archive->route);
        }

        if ($archive->route_fr) {
            Storage::disk('public')->delete($archive->route_fr);
        }

        $archive->delete();

        return redirect()->back()
            ->with('success', 'Archivo eliminado correctamente.');
    }

    public function reorder(Request $request)
    {
        $archives = Archive::all();

        foreach ($archives as $archive) {
            $id = $archive->id;
            foreach ($request->order as $order => $item) {
                if ($id == $item) {
                    $archive->update(['order' => $order]);
                }
            }
        }

        return response('Actualizado Successfully.', 200);
    }
}

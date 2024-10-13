<?php

namespace App\Http\Controllers;

use App\Models\Video;
use App\Models\VideoOnline;
use Illuminate\Http\Request;
use TCG\Voyager\Facades\Voyager;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class VideoItemController extends Controller
{
    public function create($videoonline_id)
    {
        $videoOnline = VideoOnline::findOrFail($videoonline_id);
        return Voyager::view('voyager::videos.create', compact('videoOnline'));
    }

    public function store(Request $request, $videoonline_id)
    {
        try {
            $request->validate([
                'title' => 'required|string|max:255',
                'title_fr' => 'required|string|max:255',
                'text' => 'required|string',
                'text_fr' => 'required|string',
                'iframe' => 'nullable|string',
                'imagen' => 'nullable|image',
                'video' => 'nullable|file|mimetypes:video/*',
            ]);

            $video = new Video();
            $video->title = $request->title;
            $video->title_fr = $request->title_fr;
            $video->iframe = $request->iframe;
            $video->text = $request->text;
            $video->text_fr = $request->text_fr;
            $video->videoonline_id = $videoonline_id;

            if ($request->hasFile('imagen')) {
                $imagePath = $request->file('imagen')->store('videos/images', 'public');
                $video->imagen = Storage::url($imagePath);
            }

            if ($request->hasFile('video')) {
                $videoPath = $request->file('video')->store('videos/videos', 'public');
                $video->video = Storage::url($videoPath);
            }

            $video->save();

            Log::info('Video guardado correctamente: ' . $video->id);
            Log::info('Ruta del video: ' . $video->video);

            return redirect('/admin/videosonline/' . $videoonline_id . '/edit')
                ->with('success', 'Video agregado correctamente.');
        } catch (\Exception $e) {
            Log::error('Error al guardar el video: ' . $e->getMessage());
            return back()->with('error', 'Hubo un error al guardar el video. Por favor, inténtelo de nuevo.');
        }
    }

    public function edit($id)
    {
        $video = Video::findOrFail($id);
        $videoOnline = $video->videoOnline;
        return Voyager::view('voyager::videos.edit', compact('video', 'videoOnline'));
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'title' => 'required|string|max:255',
                'title_fr' => 'required|string|max:255',
                'text' => 'required|string',
                'text_fr' => 'required|string',
                'iframe' => 'nullable|string',
                'imagen' => 'nullable|image',
                'video' => 'nullable|file|mimetypes:video/*',
            ]);

            $video = Video::findOrFail($id);
            $video->title = $request->title;
            $video->title_fr = $request->title_fr;
            $video->iframe = $request->iframe;
            $video->text = $request->text;
            $video->text_fr = $request->text_fr;

            if ($request->hasFile('imagen')) {
                if ($video->imagen) {
                    Storage::disk('public')->delete(str_replace('/storage/', '', $video->imagen));
                }
                $imagePath = $request->file('imagen')->store('videos/images', 'public');
                $video->imagen = Storage::url($imagePath);
            }

            if ($request->hasFile('video')) {
                if ($video->video) {
                    Storage::disk('public')->delete(str_replace('/storage/', '', $video->video));
                }
                $videoPath = $request->file('video')->store('videos/videos', 'public');
                $video->video = Storage::url($videoPath);
            }

            $video->save();

            return redirect('/admin/videosonline/' . $video->videoonline_id . '/edit')
                ->with('success', 'Video actualizado correctamente.');
        } catch (\Exception $e) {
            Log::error('Error al actualizar el video: ' . $e->getMessage());
            return back()->with('error', 'Hubo un error al actualizar el video. Por favor, inténtelo de nuevo.');
        }
    }

    public function destroy($id)
    {
        try {
            $video = Video::findOrFail($id);
            $videoonline_id = $video->videoonline_id;

            if ($video->imagen) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $video->imagen));
            }
            if ($video->video) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $video->video));
            }

            $video->delete();

            return redirect('/admin/videosonline/' . $videoonline_id . '/edit')
                ->with('success', 'Video eliminado correctamente.');
        } catch (\Exception $e) {
            Log::error('Error al eliminar el video: ' . $e->getMessage());
            return back()->with('error', 'Hubo un error al eliminar el video. Por favor, inténtelo de nuevo.');
        }
    }
}

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
                'title' => 'nullable|string|max:255',
                'text' => 'nullable|string',
                'iframe' => 'nullable|string',
                'imagen' => 'nullable|image',
                'fileName' => 'required|string', // Asegúrate de que el nombre del archivo esté presente
            ]);

            $video = new Video();
            $video->title = $request->title;
            $video->iframe = $request->iframe;
            $video->text = $request->text;
            $video->videoonline_id = $videoonline_id;

            if ($request->hasFile('imagen')) {
                $imagePath = $request->file('imagen')->store('videos/images', 'public');
                $video->imagen = Storage::url($imagePath); // Asegúrate de que la URL sea accesible
            }

            // Usar el nombre del archivo del video subido
            $video->video = Storage::url('videos/' . $request->fileName);

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
                'title' => 'nullable|string|max:255',
                'text' => 'nullable|string',
                'iframe' => 'nullable|string',
                'imagen' => 'nullable|image',
                'fileName' => 'nullable|string', // Asegúrate de que el nombre del archivo esté presente si se sube un nuevo video
            ]);

            $video = Video::findOrFail($id);
            $video->title = $request->title;
            $video->iframe = $request->iframe;
            $video->text = $request->text;

            if ($request->hasFile('imagen')) {
                if ($video->imagen) {
                    Storage::disk('public')->delete(str_replace('/storage/', '', $video->imagen));
                }
                $imagePath = $request->file('imagen')->store('videos/images', 'public');
                $video->imagen = Storage::url($imagePath); // Asegúrate de que la URL sea accesible
            }

            // Usar el nombre del archivo del video subido si está presente
            if ($request->fileName) {
                if ($video->video) {
                    Storage::disk('public')->delete(str_replace('/storage/', '', $video->video));
                }
                $video->video = Storage::url('videos/' . $request->fileName);
            }

            $video->save();

            return redirect('/admin/videosonline/' . $video->videoonline_id . '/edit')
                ->with('success', 'Video actualizado correctamente.');
        } catch (\Exception $e) {
            Log::error('Error al actualizar el video: ' . $e->getMessage());
            return back()->with('error', 'Hubo un error al actualizar el video. Por favor, inténtelo de nuevo.');
        }
    }

    public function reorder(Request $request)
    {
        $videoIds = $request->input('order');
        foreach ($videoIds as $index => $id) {
            Video::where('id', $id)->update(['order' => $index + 1]);
        }
        return response()->json(['success' => true]);
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

            return redirect()->back()
                ->with('success', 'Video eliminado correctamente.');
        } catch (\Exception $e) {
            Log::error('Error al eliminar el video: ' . $e->getMessage());
            return back()->with('error', 'Hubo un error al eliminar el video. Por favor, inténtelo de nuevo.');
        }
    }
}

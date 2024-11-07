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
            Log::info('Iniciando almacenamiento de video', [
                'request_data' => $request->all(),
                'videoonline_id' => $videoonline_id
            ]);

            $request->validate([
                'title' => 'nullable|string|max:255',
                'text' => 'nullable|string',
                'iframe' => 'nullable|string',
                'imagen' => 'nullable|image',
                'videoUrl' => 'required|string',
            ]);

            $video = new Video();
            $video->title = $request->title;
            $video->iframe = $request->iframe;
            $video->text = $request->text;
            $video->videoonline_id = $videoonline_id;

            if ($request->hasFile('imagen')) {
                Log::info('Procesando imagen');
                $imagePath = $request->file('imagen')->store('videos/images', 'public');
                $video->imagen = Storage::url($imagePath);
            }

            // Guardamos la URL completa del video
            $video->video = $request->videoUrl;

            Log::info('Guardando video con datos:', [
                'title' => $video->title,
                'video_url' => $video->video,
                'videoonline_id' => $video->videoonline_id
            ]);

            $video->save();

            Log::info('Video guardado correctamente', [
                'video_id' => $video->id,
                'video_url' => $video->video
            ]);

            return redirect('/admin/videosonline/' . $videoonline_id . '/edit')
                ->with('success', 'Video agregado correctamente.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Error de validación:', [
                'errors' => $e->errors(),
            ]);
            return back()
                ->withErrors($e->errors())
                ->withInput()
                ->with('error', 'Por favor, corrija los errores en el formulario.');
        } catch (\Exception $e) {
            Log::error('Error al guardar el video:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()
                ->with('error', 'Hubo un error al guardar el video: ' . $e->getMessage())
                ->withInput();
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
                'videoUrl' => 'nullable|string', // Actualizado para manejar la nueva URL
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
                $video->imagen = Storage::url($imagePath);
            }

            // Actualizar la URL del video si se proporciona una nueva
            if ($request->videoUrl) {
                $video->video = $request->videoUrl;
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

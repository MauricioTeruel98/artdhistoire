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
            $video = new Video();
            $video->title = $request->title;
            $video->iframe = $request->iframe;
            $video->text = $request->text;
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

            return redirect('/admin/videosonline/'.$videoonline_id.'/edit')
                ->with('success', 'Video agregado correctamente.');
        } catch (\Exception $e) {
            Log::error('Error al guardar el video: ' . $e->getMessage());
            return back()->with('error', 'Hubo un error al guardar el video. Por favor, inténtelo de nuevo.');
        }
    }
}
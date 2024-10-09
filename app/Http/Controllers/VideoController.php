<?php

namespace App\Http\Controllers;

use App\Models\Video;
use App\Models\VideoOnline;
use Illuminate\Http\Request;

class VideoController extends Controller
{
    public function index()
    {
        $videosOnline = VideoOnline::orderBy('order', 'DESC')->get();
        return view('pages.online.index', compact('videosOnline'));
    }

    public function show(Request $request)
    {
        $videoOnline = VideoOnline::where('id', $request->id)->firstOrFail();

        // Obtener el video anterior
        $previousVideo = VideoOnline::where('id', '<', $videoOnline->id)
            ->orderBy('id', 'desc')
            ->first();

        // Obtener el siguiente video
        $nextVideo = VideoOnline::where('id', '>', $videoOnline->id)
            ->orderBy('id', 'asc')
            ->first();

        return view('pages.online.show', compact('videoOnline', 'previousVideo', 'nextVideo'));
    }

    public function reorder(Request $request)
    {
        $videoIds = $request->input('order');
        foreach ($videoIds as $index => $id) {
            Video::where('id', $id)->update(['order' => $index + 1]);
        }
        return response()->json(['success' => true]);
    }
}

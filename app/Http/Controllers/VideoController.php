<?php

namespace App\Http\Controllers;

use App\Models\Video;
use App\Models\VideoOnline;
use Illuminate\Http\Request;

class VideoController extends Controller
{
    public function index()
    {
        $videosOnline = VideoOnline::all();
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

    public function showIlustrations(Request $request)
    {

        $videoOnline = VideoOnline::where('id', $request->id)->firstOrFail();

        return view('pages.online.ilustraciones', compact('videoOnline'));
    }
}

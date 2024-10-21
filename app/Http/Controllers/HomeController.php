<?php

namespace App\Http\Controllers;

use App\Models\Slider;
use App\Models\VideoOnline;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $videos = VideoOnline::all();
        $slider  = Slider::all();
        return view('pages.home', compact('videos', 'slider'));
    }

    public function contact()
    {
        return view('pages.contact');
    }

    public function tutorial()
    {
        
        return view('pages.tutorial');
    }
}

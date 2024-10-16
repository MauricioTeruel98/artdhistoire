<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use App\Models\Slider;
use App\Models\Archive;
use Illuminate\Http\Request;
use TCG\Voyager\Models\Post;
use Illuminate\Support\Facades\Auth;

class InteractiveController extends Controller
{
    public function index()
    {
        $slider  = Slider::all();
        return view('pages.interactive.index', compact('slider'));
    }

    public function show(Request $request)
    {
        $interactive = Categories::where('id',$request->id)->with('posts')->first();
        $slider  = Slider::all();
        
        return view('pages.interactive.show', compact('interactive', 'slider'));
    }

    public function showPdf($id)
    {
        // Verifica si el usuario está autenticado
        if (!Auth::check()) {
            dd('Redirigiendo a login'); // Añade esta línea
            return redirect()->route('login')->with('pdf_message', 'Se requiere iniciar sesión para ver este contenido PDF.');
        }

        $archive = Archive::findOrFail($id);
        $slider = Slider::all();
        return view('pages.interactive.pdf', compact('archive', 'slider'));
    }

    public function pilote()
    {
        $slider = Slider::all();
        $pilote = Categories::where('slug', 'demonstration-pilote-lisa-art-histoire')->with('posts')->first();
        return view('pages.interactive.pilote', compact('slider', 'pilote'));
    }
}

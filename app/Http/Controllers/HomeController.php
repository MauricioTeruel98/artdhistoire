<?php

namespace App\Http\Controllers;

use App\Models\Archive;
use App\Models\ArchivesEn;
use App\Models\Slider;
use App\Models\TextosGenerales;
use App\Models\VideoOnline;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $textos = TextosGenerales::first();
        $videos = VideoOnline::all();
        $slider  = Slider::all();

        $pdfs = collect(); // Inicializar como colección vacía

        if ($request->has('search') && $request->search != '') {
            $query = app()->getLocale() == 'fr' ? Archive::query() : ArchivesEn::query();
            $query->where('title', 'like', '%' . $request->search . '%');
            $pdfs = $query->paginate(10);
        } elseif ($request->has('list_all')) {
            $query = app()->getLocale() == 'fr' ? Archive::query() : ArchivesEn::query();
            $pdfs = $query->paginate(10);
        }


        return view('pages.home', compact('videos', 'slider', 'textos', 'pdfs'));
    }

    public function searchPdfs(Request $request)
    {
        $query = app()->getLocale() == 'fr' ? Archive::query() : ArchivesEn::query();
        if ($request->has('search') && $request->search != '') {
            $query->where('title', 'like', '%' . $request->search . '%');
        }
        $pdfs = $query->paginate(5);

        return response()->json([
            'data' => $pdfs->items(),
            'links' => (string) $pdfs->links('vendor.pagination.simple-bootstrap-5')
        ]);
    }

    public function contact()
    {
        $textos = TextosGenerales::first();
        return view('pages.contact', compact('textos'));
    }

    public function tutorial()
    {
        return view('pages.tutorial');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use App\Models\Slider;
use App\Models\Archive;
use App\Models\ArchivesEn;
use App\Models\TextosFormula;
use App\Models\TextosPiloto;
use Illuminate\Http\Request;
use TCG\Voyager\Models\Post;
use Illuminate\Support\Facades\Auth;
use TCG\Voyager\Facades\Voyager;

class InteractiveController extends Controller
{
    public function __construct()
    {
        $this->middleware('subscriptionOrWhitelist')->only(['showPdf']);
    }

    public function index()
    {
        $slider = Slider::all();
        $user = Auth::user();
        $subscribedCategoryIds = [];
        $textosFormula = TextosFormula::first();
        $isEnglish = app()->getLocale() != 'fr';
    
        // Determinar el monto base según idioma y tipo de usuario
        $amount = $user && $user->is_student && $user->validated_student ?
            ($isEnglish ? Voyager::setting('site.abono_estudiant_DOLARES') : Voyager::setting('site.abono_estudiant')) :
            ($isEnglish ? Voyager::setting('site.abono_normal_DOLARES') : Voyager::setting('site.abono_normal'));

        if ($user) {
            $subscribedCategoryIds = $user->subscriptions()
                ->where('status', 'active')
                ->where('end_date', '>', now())
                ->with('categories')
                ->get()
                ->pluck('categories')
                ->flatten()
                ->pluck('id')
                ->unique()
                ->toArray();
        }

        $categories = Categories::whereNotIn('id', $subscribedCategoryIds)
            ->where('is_pilote', '!=', 1)
            ->get();

        return view('pages.interactive.index', compact(
            'categories',
            'slider',
            'textosFormula',
            'amount' // Agregamos el amount al compact
        ));
    }
    public function show(Request $request, $id)
    {
        $interactive = Categories::where('id', $id)->with('posts')->firstOrFail();
        $slider = Slider::all();

        return view('pages.interactive.show', compact('interactive', 'slider'));
    }

    public function showPdf($id, $category_id)
    {
        $isEnglish = app()->getLocale() == 'en';
        
        if($isEnglish){
            $archive = ArchivesEn::findOrFail($id);
        }else{
            $archive = Archive::findOrFail($id);
        }

        $slider = Slider::all();
        return view('pages.interactive.pdf', compact('archive', 'slider', 'category_id'));
    }

    public function showPdfPilote($id)
    {
        $isEnglish = app()->getLocale() == 'en';
        
        if($isEnglish){
            $archive = ArchivesEn::findOrFail($id);
        }else{
            $archive = Archive::findOrFail($id);
        }
        $slider = Slider::all();
        $isPilote = true;

        // Verificar si el archivo pertenece a una categoría piloto
        $post = Post::find($archive->post_id);
        if (!$post || !$post->category || !$post->category->is_pilote) {
            abort(404);
        }

        return view('pages.interactive.pdf', compact('archive', 'slider', 'isPilote'));
    }

    public function pilote()
    {
        $textosPiloto = TextosPiloto::orderBy('order', 'DESC')->first();
        $slider = Slider::all();
        $pilote = Categories::where('is_pilote', 1)->with('posts')->orderBy('created_at', 'DESC')->first();
        return view('pages.interactive.pilote', compact('slider', 'pilote', 'textosPiloto'));
    }
}

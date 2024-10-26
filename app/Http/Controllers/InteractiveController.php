<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use App\Models\Slider;
use App\Models\Archive;
use App\Models\TextosPiloto;
use Illuminate\Http\Request;
use TCG\Voyager\Models\Post;
use Illuminate\Support\Facades\Auth;

class InteractiveController extends Controller
{
    public function __construct()
    {
        $this->middleware('subscriptionOrWhitelist')->only(['show', 'showPdf']);
    }

    public function index()
    {
        $slider = Slider::all();
        $user = Auth::user();
        $subscribedCategoryIds = [];

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

        $categories = Categories::whereNotIn('id', $subscribedCategoryIds)->get();

        return view('pages.interactive.index', compact('slider', 'categories'));
    }
    public function show(Request $request, $id)
    {
        $interactive = Categories::where('id', $id)->with('posts')->firstOrFail();
        $slider = Slider::all();

        return view('pages.interactive.show', compact('interactive', 'slider'));
    }

    public function showPdf($id)
    {
        $archive = Archive::findOrFail($id);
        $slider = Slider::all();
        return view('pages.interactive.pdf', compact('archive', 'slider'));
    }

    public function showPdfPilote($id)
    {
        $archive = Archive::findOrFail($id);
        $slider = Slider::all();
        return view('pages.interactive.pdf', compact('archive', 'slider'));
    }

    public function pilote()
    {
        $textosPiloto = TextosPiloto::orderBy('order', 'DESC')->first();
        $slider = Slider::all();
        $pilote = Categories::where('is_pilote', 1)->with('posts')->orderBy('created_at', 'DESC')->first();
        return view('pages.interactive.pilote', compact('slider', 'pilote', 'textosPiloto'));
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Archive;
use App\Models\ArchivesEn;
use App\Models\Categories;
use App\Models\Slider;
use App\Models\TextosGenerales;
use App\Models\VideoOnline;
use App\Models\Video;
use App\Models\VideoEn;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use TCG\Voyager\Models\Post;

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

    public function searchContent(Request $request)
    {
        $searchQuery = $request->input('search');
        $locale = app()->getLocale();
        $perPage = 10;

        // Sagas (Categories)
        $sagas = Categories::select(
            'id',
            'name as title',
            'name_fr as title_fr',
            DB::raw('"category" as type'),
            DB::raw('NULL as post_id')  // Añadimos post_id como NULL
        )
            ->where('name', 'like', '%' . $searchQuery . '%')
            ->orWhere('name_fr', 'like', '%' . $searchQuery . '%');

        // Interactive (Posts)
        $interactive = Post::select(
            'id',
            'title',
            'title_fr',
            DB::raw('"post" as type'),
            DB::raw('NULL as post_id')  // Añadimos post_id como NULL
        )
            ->where('title', 'like', '%' . $searchQuery . '%')
            ->orWhere('title_fr', 'like', '%' . $searchQuery . '%');

        // PDFs
        if ($locale == 'fr') {
            $pdfs = Archive::select(
                'id',
                'title',
                DB::raw('NULL as title_fr'),
                DB::raw('"pdf" as type'),
                'post_id'
            )
                ->where('title', 'like', '%' . $searchQuery . '%');
        } else {
            $pdfs = ArchivesEn::select(
                'id',
                'title',
                DB::raw('NULL as title_fr'),
                DB::raw('"pdf" as type'),
                'post_id'
            )
                ->where('title', 'like', '%' . $searchQuery . '%');
        }

        // Videos Online
        $videosOnline = VideoOnline::select(
            'id',
            'title',
            'title_fr',
            DB::raw('"video_online" as type'),
            DB::raw('NULL as post_id')  // Añadimos post_id como NULL
        )
            ->where('title', 'like', '%' . $searchQuery . '%')
            ->orWhere('title_fr', 'like', '%' . $searchQuery . '%');

        // Combinar resultados
        $results = $pdfs
            ->union($videosOnline)
            ->union($interactive)
            ->union($sagas)
            ->paginate($perPage);

        // Transformar resultados
        $results->getCollection()->transform(function ($item) {
            switch ($item->type) {
                case 'category':
                    $item->type = app()->getLocale() == 'fr' ? 'Saga' : 'Saga';
                    break;
                case 'post':
                    $item->type = app()->getLocale() == 'fr' ? 'PDF' : 'PDF';
                    break;
                case 'pdf':
                    $item->type = app()->getLocale() == 'fr' ? 'PDF' : 'PDF';
                    if ($item->post_id) {
                        $post = Post::find($item->post_id);
                        $item->category_id = $post ? $post->category_id : null;
                        // Verificar si la categoría es piloto
                        if ($post && $post->category) {
                            $item->is_pilote = $post->category->is_pilote;
                        }
                    }
                    break;
                case 'video_online':
                    $item->type = app()->getLocale() == 'fr' ? 'Video Online' : 'Video Online';
                    break;
            }
            return $item;
        });
    
        return response()->json([
            'data' => $results->items(),
            'links' => (string) $results->links('vendor.pagination.simple-bootstrap-5')
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

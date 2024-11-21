<?php

namespace TCG\Voyager\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Constraint;
use Intervention\Image\Facades\Image;
use TCG\Voyager\Facades\Voyager;

class VoyagerController extends Controller
{
    public function index(Request $request)
    {
        try {
            // Obtener el mes seleccionado (por defecto el mes actual)
            $selectedMonth = $request->get('month', now()->format('Y-m'));
            
            // Crear fechas de inicio y fin del mes seleccionado
            $startDate = \Carbon\Carbon::createFromFormat('Y-m', $selectedMonth)->startOfMonth();
            $endDate = \Carbon\Carbon::createFromFormat('Y-m', $selectedMonth)->endOfMonth();

            // Obtener estadísticas de ventas
            $sales = \App\Models\Sale::completed()
                ->select(
                    DB::raw('DATE(created_at) as date'),
                    DB::raw('SUM(amount) as total'),
                    DB::raw('COUNT(*) as count'),
                    'currency'
                )
                ->whereBetween('created_at', [$startDate, $endDate])
                ->groupBy('date', 'currency')
                ->orderBy('date', 'DESC')
                ->get();

            // Separar ventas por moneda
            $salesUSD = $sales->where('currency', 'USD')->values();
            $salesEUR = $sales->where('currency', 'EUR')->values();
            
            // Generar lista de meses para el selector
            $months = [];
            for ($i = 0; $i < 12; $i++) {
                $date = now()->subMonths($i);
                $months[$date->format('Y-m')] = $date->format('F Y');
            }

            return Voyager::view('voyager::index', compact('salesUSD', 'salesEUR', 'months', 'selectedMonth'));
        } catch (\Exception $e) {
            \Log::error('Error al obtener ventas: ' . $e->getMessage());
            return Voyager::view('voyager::index', [
                'salesUSD' => collect([]), 
                'salesEUR' => collect([]),
                'months' => [],
                'selectedMonth' => now()->format('Y-m')
            ]);
        }
    }

    public function logout()
    {
        Auth::logout();

        return redirect()->route('voyager.login');
    }

    public function upload(Request $request)
    {
        $fullFilename = null;
        $resizeWidth = 1800;
        $resizeHeight = null;
        $slug = $request->input('type_slug');
        $file = $request->file('image');

        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->firstOrFail();

        if ($this->userCannotUploadImageIn($dataType, 'add') && $this->userCannotUploadImageIn($dataType, 'edit')) {
            abort(403);
        }

        $path = $slug . '/' . date('FY') . '/';

        $filename = basename($file->getClientOriginalName(), '.' . $file->getClientOriginalExtension());
        $filename_counter = 1;

        // Make sure the filename does not exist, if it does make sure to add a number to the end 1, 2, 3, etc...
        while (Storage::disk(config('voyager.storage.disk'))->exists($path . $filename . '.' . $file->getClientOriginalExtension())) {
            $filename = basename($file->getClientOriginalName(), '.' . $file->getClientOriginalExtension()) . (string) ($filename_counter++);
        }

        $fullPath = $path . $filename . '.' . $file->getClientOriginalExtension();

        $ext = $file->guessClientExtension();

        if (in_array($ext, ['jpeg', 'jpg', 'png', 'gif'])) {
            $image = Image::make($file)
                ->resize($resizeWidth, $resizeHeight, function (Constraint $constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
            if ($ext !== 'gif') {
                $image->orientate();
            }
            $image->encode($file->getClientOriginalExtension(), 75);

            // move uploaded file from temp to uploads directory
            if (Storage::disk(config('voyager.storage.disk'))->put($fullPath, (string) $image, 'public')) {
                $status = __('voyager::media.success_uploading');
                $fullFilename = $fullPath;
            } else {
                $status = __('voyager::media.error_uploading');
            }
        } else {
            $status = __('voyager::media.uploading_wrong_type');
        }

        // Return URL for TinyMCE
        return Voyager::image($fullFilename);
    }

    public function assets(Request $request)
    {
        try {
            if (class_exists(\League\Flysystem\Util::class)) {
                // Flysystem 1.x
                $path = dirname(__DIR__, 3) . '/publishable/assets/' . \League\Flysystem\Util::normalizeRelativePath(urldecode($request->path));
            } elseif (class_exists(\League\Flysystem\WhitespacePathNormalizer::class)) {
                // Flysystem >= 2.x
                $normalizer = new \League\Flysystem\WhitespacePathNormalizer();
                $path = dirname(__DIR__, 3) . '/publishable/assets/' . $normalizer->normalizePath(urldecode($request->path));
            }
        } catch (\LogicException $e) {
            abort(404);
        }

        if (File::exists($path)) {
            $mime = '';
            if (Str::endsWith($path, '.js')) {
                $mime = 'text/javascript';
            } elseif (Str::endsWith($path, '.css')) {
                $mime = 'text/css';
            } else {
                $mime = File::mimeType($path);
            }
            $response = response(File::get($path), 200, ['Content-Type' => $mime]);
            $response->setSharedMaxAge(31536000);
            $response->setMaxAge(31536000);
            $response->setExpires(new \DateTime('+1 year'));

            return $response;
        }

        return response('', 404);
    }

    protected function userCannotUploadImageIn($dataType, $action)
    {
        return auth()->user()->cannot($action, app($dataType->model_name))
            || $dataType->{$action . 'Rows'}->where('type', 'rich_text_box')->count() === 0;
    }
}
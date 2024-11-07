<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VideoUploadController extends Controller
{
    public function uploadChunk(Request $request)
    {
        $fileName = $request->input('fileName');
        $chunk = $request->file('video');
        $chunkIndex = $request->input('chunk');
        $totalChunks = $request->input('totalChunks');

        $tempDir = storage_path('app/temp/' . $fileName);
        if (!is_dir($tempDir)) {
            mkdir($tempDir, 0777, true);
        }

        $chunk->move($tempDir, $chunkIndex);

        // Verificar si todos los chunks han sido subidos
        if (count(scandir($tempDir)) - 2 == $totalChunks) { // -2 para excluir '.' y '..'
            $finalPath = storage_path('app/public/videos/' . $fileName);
            $this->assembleChunks($tempDir, $finalPath);
            Storage::url('videos/' . $fileName);
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => true]);
    }

    private function assembleChunks($tempDir, $finalPath)
    {
        $files = scandir($tempDir);
        natsort($files);

        $out = fopen($finalPath, 'wb');
        foreach ($files as $file) {
            if ($file == '.' || $file == '..') continue;
            $chunk = fopen($tempDir . '/' . $file, 'rb');
            stream_copy_to_stream($chunk, $out);
            fclose($chunk);
        }
        fclose($out);

        // Limpiar los archivos temporales
        array_map('unlink', glob("$tempDir/*"));
        rmdir($tempDir);
    }
}
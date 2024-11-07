<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Google\Client;
use Google\Service\Drive;
use Illuminate\Support\Facades\Log;

class VideoUploadController extends Controller
{
    private $folderId = '1MY4aSsPyRXiS2SLL1hp-zgss_AJEuOcE'; // ID de la carpeta en Drive para el idioma francés
    private $folderIdEn = '1WBJ-btzSkiA0d8I8nayRwQXsnqzhF3qQ'; // ID de la carpeta en Drive para el idioma inglés

    public function uploadChunk(Request $request)
    {
        try {
            // Validar la solicitud
            $request->validate([
                'video' => 'required|file|mimes:mp4',
                'fileName' => 'required|string',
            ]);

            // Configurar el cliente de Google
            $client = new Client();

            // Verificar si existe el archivo de credenciales
            $credentialsPath = storage_path('app/certificado.json');
            if (!file_exists($credentialsPath)) {
                Log::error('Archivo de credenciales no encontrado en: ' . $credentialsPath);
                return response()->json([
                    'success' => false,
                    'error' => 'Archivo de credenciales no encontrado'
                ], 500);
            }

            // Configurar el cliente con las credenciales OAuth 2.0
            $client->setAuthConfig($credentialsPath);
            $client->setAccessType('offline');
            $client->setPrompt('consent');

            // Añadir los scopes necesarios
            $client->addScope(Drive::DRIVE);
            $client->addScope(Drive::DRIVE_FILE);

            // Establecer la URI de redirección
            $client->setRedirectUri(url('/google-drive/callback'));

            // Deshabilitar verificación SSL solo para desarrollo
            $client->setHttpClient(
                new \GuzzleHttp\Client([
                    'verify' => false
                ])
            );

            // Si no hay token almacenado, necesitamos obtener uno
            if (!$this->hasStoredToken()) {
                $authUrl = $client->createAuthUrl();
                return response()->json([
                    'success' => false,
                    'needsAuth' => true,
                    'authUrl' => $authUrl
                ]);
            }

            // Cargar el token almacenado
            $accessToken = $this->getStoredToken();
            $client->setAccessToken($accessToken);

            // Refrescar el token si ha expirado
            if ($client->isAccessTokenExpired()) {
                if ($client->getRefreshToken()) {
                    $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
                    $this->storeToken($client->getAccessToken());
                } else {
                    return response()->json([
                        'success' => false,
                        'needsAuth' => true,
                        'authUrl' => $client->createAuthUrl()
                    ]);
                }
            }

            $service = new Drive($client);

            // Configurar los metadatos del archivo
            $fileMetadata = new Drive\DriveFile([
                'name' => $request->fileName,
                'parents' => [app()->getLocale() == 'fr' ? $this->folderId : $this->folderIdEn]
            ]);

            // Obtener el contenido del archivo
            $content = file_get_contents($request->file('video')->getRealPath());

            // Subir el archivo a Google Drive
            $file = $service->files->create($fileMetadata, [
                'data' => $content,
                'mimeType' => 'video/mp4',
                'uploadType' => 'multipart',
                'fields' => 'id'
            ]);

            // Configurar permisos públicos para el archivo
            $permission = new Drive\Permission([
                'type' => 'anyone',
                'role' => 'reader'
            ]);

            $service->permissions->create($file->id, $permission);

            // Generar la URL directa para streaming
            $videoUrl = "https://drive.google.com/file/d/" . $file->id . "/preview";

            Log::info('Video subido exitosamente', [
                'fileId' => $file->id,
                'fileName' => $request->fileName,
                'videoUrl' => $videoUrl
            ]);

            return response()->json([
                'success' => true,
                'videoUrl' => $videoUrl,
                'fileId' => $file->id,
                'fileName' => $request->fileName
            ]);
        } catch (\Exception $e) {
            Log::error('Error durante la subida: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'error' => 'Error durante la subida: ' . $e->getMessage()
            ], 500);
        }
    }

    public function setFolderPermissions()
    {
        try {
            $client = $this->getConfiguredClient();
            $service = new Drive($client);

            $permission = new Drive\Permission([
                'type' => 'anyone',
                'role' => 'reader',
                'allowFileDiscovery' => true
            ]);

            $service->permissions->create(app()->getLocale() == 'fr' ? $this->folderId : $this->folderIdEn, $permission, [
                'fields' => 'id',
                'supportsAllDrives' => true
            ]);

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            Log::error('Error setting folder permissions: ' . $e->getMessage());
            return response()->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    private function getConfiguredClient()
    {
        $client = new Client();
        $client->setAuthConfig(storage_path('app/certificado.json'));
        $client->setAccessType('offline');
        $client->setPrompt('consent');
        $client->addScope(Drive::DRIVE);
        $client->addScope(Drive::DRIVE_FILE);
        $client->setRedirectUri(url('/google-drive/callback'));

        $client->setHttpClient(
            new \GuzzleHttp\Client([
                'verify' => false
            ])
        );

        if ($this->hasStoredToken()) {
            $client->setAccessToken($this->getStoredToken());

            if ($client->isAccessTokenExpired() && $client->getRefreshToken()) {
                $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
                $this->storeToken($client->getAccessToken());
            }
        }

        return $client;
    }

    private function hasStoredToken()
    {
        return Storage::exists('google-drive-token.json');
    }

    private function getStoredToken()
    {
        return json_decode(Storage::get('google-drive-token.json'), true);
    }

    private function storeToken($token)
    {
        Storage::put('google-drive-token.json', json_encode($token));
    }

    public function handleCallback(Request $request)
    {
        try {
            $client = $this->getConfiguredClient();
            $authCode = $request->get('code');
            $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);
            $this->storeToken($accessToken);

            return redirect()->to('/admin/videosonline')
                ->with('success', 'Autenticación completada exitosamente. Puede subir su video ahora.');
        } catch (\Exception $e) {
            Log::error('Error durante la autenticación: ' . $e->getMessage());
            return redirect()->to('/admin/videosonline')
                ->with('error', 'Error durante la autenticación: ' . $e->getMessage());
        }
    }
}

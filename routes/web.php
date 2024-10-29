<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InteractiveController;
use App\Http\Controllers\VideoItemController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\VideoEnItemController;
use App\Models\Categories;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('language/{locale}', [LanguageController::class, 'switch'])->name('language.switch');

Route::get('/interactive/index', [InteractiveController::class, 'index'])->name('interactive.index');
Route::get('/interactive', [InteractiveController::class, 'pilote'])->name('interactive.pilote');
//Route::get('/interactive/{id}', [InteractiveController::class, 'show'])->name('interactive.show');

Route::get('/videos-online', [VideoController::class, 'index'])->name('videos.index');
Route::get('/video-online/{id}', [VideoController::class, 'show'])->name('video.show');
Route::get('/video-online/{id}/ilustrations', [VideoController::class, 'showIlustrations'])->name('video.show.ilustrations');

Route::get('/about', [HomeController::class, 'contact'])->name('contact');

Route::get('/tutorial', [HomeController::class, 'tutorial'])->name('tutorial');

Route::post('/contact', [ContactController::class, 'submit']);

Route::get('/search-pdfs', [HomeController::class, 'searchPdfs'])->name('search.pdfs');
Route::get('/search-content', [HomeController::class, 'searchContent'])->name('search.content');

Route::get('/subscription-required/{category_id}', function ($category_id) {
    $category = Categories::findOrFail($category_id);
    return view('pages.subscription.required', compact('category'));
})->name('subscription.required');


Route::middleware(['auth'])->group(function () {
    Route::post('/subscription/trial', [SubscriptionController::class, 'createTrialSubscription'])->name('subscription.trial');
    // ... otras rutas que requieran autenticación ...
});


/**
 * RUTAS DE SUSCRIPCION
 */

Route::post('/subscription/trial', [SubscriptionController::class, 'createTrialSubscription'])->name('subscription.trial');

Route::post('/subscription/create', [SubscriptionController::class, 'create'])->name('subscription.create');
Route::get('/subscription/success', [SubscriptionController::class, 'success'])->name('subscription.success');
Route::get('/subscription/cancel', [SubscriptionController::class, 'cancel'])->name('subscription.cancel');
Route::post('/stripe/webhook', [SubscriptionController::class, 'handleStripeWebhook']);


// Ruta protegida para usuarios autenticados
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/certificate/upload/{category_id}', [CertificateController::class, 'showUploadForm'])->name('certificate.upload');
    Route::post('/certificate/store', [CertificateController::class, 'store'])->name('certificate.store');
});

Route::get('/interactive/index', [InteractiveController::class, 'index'])->name('interactive.index');
Route::get('/interactive', [InteractiveController::class, 'pilote'])->name('interactive.pilote');

Route::get('/interactive/pdf/{id}/pilote', [InteractiveController::class, 'showPdfPilote'])->name('interactive.pdf.pilote');
Route::get('/interactive/{id}', [InteractiveController::class, 'show'])->name('interactive.show');

Route::middleware(['auth', 'subscriptionOrWhitelist'])->group(function () {
    Route::get('/interactive/pdf/{id}/{category_id}', [InteractiveController::class, 'showPdf'])->name('interactive.pdf');
});

Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();

    Route::get('videoonline/{videoonline_id}/videos/create', [VideoItemController::class, 'create'])->name('videoonline.videos.create');
    Route::post('videoonline/{videoonline_id}/videos', [VideoItemController::class, 'store'])->name('videoonline.videos.store');
    Route::get('videos/{id}/edit/edit', [VideoItemController::class, 'edit'])->name('videoonline.videos.edit.edit');
    Route::put('videos/{id}', [VideoItemController::class, 'update'])->name('videoonline.videos.update');
    Route::delete('videos/{id}/destroy', [VideoItemController::class, 'destroy'])->name('videoonline.videos.destroy');
    Route::post('video/reorder', [VideoItemController::class, 'reorder'])->name('videos.reorder');


    Route::get('videoonline-en/{videoonline_id}/videos/create', [VideoEnItemController::class, 'create'])->name('videoonline.en.videos.create');
    Route::post('videoonline-en/{videoonline_id}/videos', [VideoEnItemController::class, 'store'])->name('videoonline.en.videos.store');
    Route::get('videos-en/{id}/edit/edit', [VideoEnItemController::class, 'edit'])->name('videoonline.en.videos.edit.edit');
    Route::put('videos-en/{id}', [VideoEnItemController::class, 'update'])->name('videoonline.en.videos.update');
    Route::delete('videos-en/{id}/destroy', [VideoEnItemController::class, 'destroy'])->name('videoonline.en.videos.destroy');
    Route::post('video-en/reorder', [VideoEnItemController::class, 'reorder'])->name('videos.en.reorder');


    Route::get('archives', [App\Http\Controllers\ArchiveController::class, 'index'])->name('voyager.archives.index');
    Route::post('archives/reorder', [App\Http\Controllers\ArchiveController::class, 'reorder'])->name('archives.reorder');
    Route::post('archives', [App\Http\Controllers\ArchiveController::class, 'store'])->name('voyager.archives.store');
    Route::get('archives/{archive}/edit/edit', [App\Http\Controllers\ArchiveController::class, 'edit'])->name('voyager.archives.edit.edit');
    Route::put('archives/{archive}', [App\Http\Controllers\ArchiveController::class, 'update'])->name('voyager.archives.update');
    Route::delete('archives/{archive}/delete', [App\Http\Controllers\ArchiveController::class, 'destroy'])->name('voyager.archives.destroy.destroy');

    Route::get('archives-en', [App\Http\Controllers\ArchivesEnController::class, 'index'])->name('voyager.archives.en.index');
    Route::post('archives-en/reorder', [App\Http\Controllers\ArchivesEnController::class, 'reorder'])->name('archives.en.reorder');
    Route::post('archives-en', [App\Http\Controllers\ArchivesEnController::class, 'store'])->name('voyager.archives.en.store');
    Route::get('archives-en/{archive}/edit/edit', [App\Http\Controllers\ArchivesEnController::class, 'edit'])->name('voyager.archives.en.edit.edit');
    Route::put('archives-en/{archive}', [App\Http\Controllers\ArchivesEnController::class, 'update'])->name('voyager.archives.en.update');
    Route::delete('archives-en/{archive}/delete', [App\Http\Controllers\ArchivesEnController::class, 'destroy'])->name('voyager.archives.en.destroy.destroy');
});

/**
 * 
 * RUTAS DE AUTH
 */

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.update.password');
});

Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::post('/subscribe-newsletter', [NewsletterController::class, 'subscribe']);

// Redireccionar a Google
Route::get('login/google', function () {
    return Socialite::driver('google')->redirect();
});

// Callback de Google
Route::get('login/google/callback', function () {
    $googleUser = Socialite::driver('google')->stateless()->setHttpClient(new \GuzzleHttp\Client(['verify' => false]))->user();


    // Lógica para crear o autenticar usuario
    $user = User::updateOrCreate([
        'email' => $googleUser->getEmail(),
    ], [
        'name' => $googleUser->getName(),
        'google_id' => $googleUser->getId(),
        'password' => bcrypt(Str::random(16)),
        // Puedes almacenar otros datos relevantes
    ]);

    Auth::login($user);

    return redirect('/');
});

// Redireccionar a Facebook
Route::get('login/facebook', function () {
    return Socialite::driver('facebook')->redirect();
});

// Callback de Facebook
Route::get('login/facebook/callback', function () {
    $facebookUser = Socialite::driver('facebook')->stateless()->user();

    // Lógica para crear o autenticar usuario
    $user = User::updateOrCreate([
        'email' => $facebookUser->getEmail(),
    ], [
        'name' => $facebookUser->getName(),
        'facebook_id' => $facebookUser->getId(),
        // Otros datos relevantes
    ]);

    Auth::login($user);

    return redirect('/');
});

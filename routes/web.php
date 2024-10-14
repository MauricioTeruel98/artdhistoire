<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InteractiveController;
use App\Http\Controllers\VideoItemController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\SubscriptionController;
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
Route::get('/interactive/{id}', [InteractiveController::class, 'show'])->name('interactive.show');

Route::get('/videos-online', [VideoController::class, 'index'])->name('videos.index');
Route::get('/video-online/{id}', [VideoController::class, 'show'])->name('video.show');

Route::get('/about', [HomeController::class, 'contact'])->name('contact');

Route::get('/tutorial', [HomeController::class, 'tutorial'])->name('tutorial');

Route::post('/contact', [ContactController::class, 'submit']);

Route::get('/subscription-required', function () {
    return view('pages.subscription.required');
})->name('subscription.required');


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

    Route::post('/video/reorder', [VideoController::class, 'reorder'])->name('videos.reorder');
});

Route::middleware(['auth', 'subscription'])->group(function () {
    Route::get('/interactive/pdf/{id}', [InteractiveController::class, 'showPdf'])->name('interactive.pdf');
});

Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();

    Route::get('videoonline/{videoonline_id}/videos/create', [VideoItemController::class, 'create'])->name('videoonline.videos.create');
    Route::post('videoonline/{videoonline_id}/videos', [VideoItemController::class, 'store'])->name('videoonline.videos.store');
    Route::get('videos/{id}/edit/edit', [VideoItemController::class, 'edit'])->name('videoonline.videos.edit.edit');
    Route::put('videos/{id}', [VideoItemController::class, 'update'])->name('videoonline.videos.update');
    Route::delete('videos/{id}/destroy', [VideoItemController::class, 'destroy'])->name('videoonline.videos.destroy');


    Route::get('archives', [App\Http\Controllers\ArchiveController::class, 'index'])->name('voyager.archives.index');
    Route::post('archives/reorder', [App\Http\Controllers\ArchiveController::class, 'reorder'])->name('archives.reorder');
    Route::post('archives', [App\Http\Controllers\ArchiveController::class, 'store'])->name('voyager.archives.store');
    Route::get('archives/{archive}/edit/edit', [App\Http\Controllers\ArchiveController::class, 'edit'])->name('voyager.archives.edit.edit');
    Route::put('archives/{archive}', [App\Http\Controllers\ArchiveController::class, 'update'])->name('voyager.archives.update');
    Route::delete('archives/{archive}', [App\Http\Controllers\ArchiveController::class, 'destroy'])->name('voyager.archives.destroy');
});

/**
 * 
 * RUTAS DE AUTH
 */

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

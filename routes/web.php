<?php


use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BotController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use App\Models\LocationPoint;

Route::post('/telegram/webhook', [BotController::class, 'webhook']);

Route::get('/', [PageController::class, 'showMainPage']);
Route::get('/services/{service}', [PageController::class, 'showService'])->name('services.show');


Route::middleware('guest')->group(function () {
    Route::get('signup', [AuthController::class, 'showRegistrationForm'])->name('signup');
    Route::post('register', [AuthController::class, 'register']);
    Route::get('signin', [AuthController::class, 'showLoginForm'])->name('signin');
    Route::post('signin/store', [AuthController::class, 'login'])->name('signin.store');
});

Route::middleware('auth')->group(function () {
    // Профиль
    Route::put('/profile', [ProfileController::class, 'update']);
    Route::post('/profile/image', [ProfileController::class, 'uploadImage']);

    // Записи
    Route::resource('appointments', AppointmentController::class)->only([
        'create',
        'store',
        'destroy'
    ]);


    Route::get('/appointment/{locationId}/available-hours', [AppointmentController::class, 'availableHours'])
        ->name('appointment.availableHours');


    // Dashboard
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard', [
            'auth' => [
                'user' => Auth::user()->load([
                    'appointments.service',
                    'appointments.location',
                    'appointments.status',
                    'promocodes',
                ])->only([
                    'id',
                    'name',
                    'phone',
                    'telegram_id',
                    'image',
                    'appointments',
                    'promocodes'
                ])
            ]
        ]);
    })->middleware('auth');
});

Route::post('logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');



// Route::get('/admin/login', Login::class)->name('filament.auth.login');
// Route::post('/admin/logout', LogoutController::class)
//     ->name('filament.auth.logout');

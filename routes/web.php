<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PortfolioController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\Admin\MessageController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\SeoController;
use App\Http\Controllers\Admin\AnalyticsController;
use App\Http\Controllers\Admin\MaintenanceController;
use App\Http\Controllers\Admin\ClientController;

// ============================================================
// PUBLIC ROUTES
// ============================================================
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/services', [HomeController::class, 'services'])->name('services');
Route::get('/portfolio', [HomeController::class, 'portfolio'])->name('portfolio');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::post('/contact', [HomeController::class, 'sendMessage'])->name('contact.send');
Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews');

// SEO
Route::get('/sitemap.xml', [HomeController::class, 'sitemap'])->name('sitemap');
Route::get('/robots.txt',  [HomeController::class, 'robots'])->name('robots');

// ============================================================
// ADMIN
// ============================================================
Route::prefix('admin')->name('admin.')->group(function () {

    Route::get('/login',  [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::post('/logout',[AuthController::class, 'logout'])->name('logout');

    Route::middleware('auth')->group(function () {

        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        Route::resource('portfolio', PortfolioController::class)->except(['show']);
        Route::resource('clients', ClientController::class)->except(['show']);
        Route::resource('services', ServiceController::class)->except(['show']);
        Route::resource('testimonials', TestimonialController::class)->except(['show']);

        Route::get('/messages',              [MessageController::class, 'index'])->name('messages.index');
        Route::get('/messages/{message}',    [MessageController::class, 'show'])->name('messages.show');
        Route::delete('/messages/{message}', [MessageController::class, 'destroy'])->name('messages.destroy');

        Route::get('/settings',  [SettingsController::class, 'index'])->name('settings');
        Route::post('/settings', [SettingsController::class, 'update'])->name('settings.update');
        Route::post('/settings/maintenance/migrate', [MaintenanceController::class, 'migrate'])
            ->middleware('throttle:3,1')->name('maintenance.migrate');
        Route::post('/settings/maintenance/optimize-clear', [MaintenanceController::class, 'optimizeClear'])
            ->middleware('throttle:3,1')->name('maintenance.optimize-clear');

        Route::resource('users', UserController::class)->except(['show']);
        Route::post('/users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('users.reset-password');

        Route::get('/profile',           [UserController::class, 'profile'])->name('profile');
        Route::post('/profile',          [UserController::class, 'updateProfile'])->name('profile.update');
        Route::post('/profile/password', [UserController::class, 'updatePassword'])->name('profile.password');

        Route::get('/seo',  [SeoController::class, 'index'])->name('seo');
        Route::post('/seo', [SeoController::class, 'update'])->name('seo.update');

        Route::get('/analytics',              [AnalyticsController::class, 'index'])->name('analytics');
        Route::post('/analytics/purge',       [AnalyticsController::class, 'purge'])->name('analytics.purge');
        Route::post('/analytics/reset-google',[AnalyticsController::class, 'resetGoogleCounter'])->name('analytics.reset-google');

        Route::post('/reviews/refresh', [ReviewController::class, 'refresh'])->name('reviews.refresh');
    });
});

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\SocialAuthController;
use App\Http\Controllers\Client\DashboardController as ClientDashboard;
use App\Http\Controllers\Provider\DashboardController as ProviderDashboard;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\EarningsController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Page d'accueil publique
Route::get('/', function () {
    return view('welcome');
});

// Routes d'authentification
Auth::routes();

// Route de déconnexion personnalisée
Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');

// Routes OAuth
Route::get('auth/{provider}', [SocialAuthController::class, 'redirectToProvider'])->name('oauth.redirect');
Route::get('auth/{provider}/callback', [SocialAuthController::class, 'handleProviderCallback'])->name('oauth.callback');

// Routes principales (protégées)
Route::middleware(['auth'])->group(function () {
    // Tableau de bord
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    
    // Dashboard selon le rôle
    Route::get('/client/dashboard', [ClientDashboard::class, 'index'])->name('client.dashboard');
    Route::get('/provider/dashboard', [ProviderDashboard::class, 'index'])->name('provider.dashboard');
    Route::get('/admin/dashboard', [AdminDashboard::class, 'index'])->name('admin.dashboard');
    
    // Profil
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
    
    // Services
    Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
    Route::get('/services/create', [ServiceController::class, 'create'])->name('services.create');
    Route::post('/services', [ServiceController::class, 'store'])->name('services.store');
    Route::get('/services/{service}', [ServiceController::class, 'show'])->name('services.show');
    Route::get('/services/{service}/edit', [ServiceController::class, 'edit'])->name('services.edit');
    Route::put('/services/{service}', [ServiceController::class, 'update'])->name('services.update');
    Route::delete('/services/{service}', [ServiceController::class, 'destroy'])->name('services.destroy');
    Route::get('/my-services', [ServiceController::class, 'myServices'])->name('services.my');
    
    // Réservations
    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/create/{service}', [BookingController::class, 'create'])->name('bookings.create');
    Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
    Route::get('/bookings/{booking}', [BookingController::class, 'show'])->name('bookings.show');
    Route::put('/bookings/{booking}/confirm', [BookingController::class, 'confirm'])->name('bookings.confirm')->middleware('provider');
    Route::put('/bookings/{booking}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');
    Route::put('/bookings/{booking}/complete', [BookingController::class, 'complete'])->name('bookings.complete')->middleware('provider');
    
    // Messages
    Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/create', [MessageController::class, 'create'])->name('messages.create');
    Route::post('/messages/search-users', [MessageController::class, 'searchUsers'])->name('messages.search-users');
    Route::get('/messages/{user}', [MessageController::class, 'show'])->name('messages.show');
    Route::post('/messages', [MessageController::class, 'store'])->name('messages.store');
    Route::put('/messages/{message}/read', [MessageController::class, 'markAsRead'])->name('messages.read');
    
    // Avis
    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::put('/reviews/{review}', [ReviewController::class, 'update'])->name('reviews.update');
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');
    Route::get('/my-reviews', [ReviewController::class, 'myReviews'])->name('reviews.my');
    
    // Revenus
    Route::get('/earnings', [EarningsController::class, 'index'])->name('earnings');
    Route::get('/earnings/{payment}', [EarningsController::class, 'show'])->name('earnings.show');
    
    // Paiements
    Route::post('/payments/{booking}/pay', [PaymentController::class, 'pay'])->name('payments.pay');
    Route::get('/payments/{booking}/success', [PaymentController::class, 'success'])->name('payments.success');
    Route::get('/payments/{booking}/cancel', [PaymentController::class, 'cancel'])->name('payments.cancel');
});

// Routes admin
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');
    
    // Utilisateurs
    Route::get('/users', [AdminDashboard::class, 'users'])->name('users.index');
    Route::get('/users/create', [AdminDashboard::class, 'createUser'])->name('users.create');
    Route::post('/users', [AdminDashboard::class, 'storeUser'])->name('users.store');
    Route::get('/users/{user}', [AdminDashboard::class, 'showUser'])->name('users.show');
    Route::put('/users/{user}/verify', [AdminDashboard::class, 'verifyUser'])->name('users.verify');
    Route::put('/users/{user}/toggle', [AdminDashboard::class, 'toggleUser'])->name('users.toggle');
    Route::put('/users/{user}/block', [AdminDashboard::class, 'blockUser'])->name('users.block');
    Route::put('/users/{user}/unblock', [AdminDashboard::class, 'unblockUser'])->name('users.unblock');
    Route::delete('/users/{user}', [AdminDashboard::class, 'deleteUser'])->name('users.destroy');
    
    // Services
    Route::get('/services', [AdminDashboard::class, 'services'])->name('services.index');
    Route::get('/services/{service}', [AdminDashboard::class, 'showService'])->name('services.show');
    Route::put('/services/{service}/approve', [AdminDashboard::class, 'approveService'])->name('services.approve');
    Route::put('/services/{service}/reject', [AdminDashboard::class, 'rejectService'])->name('services.reject');
    Route::put('/services/{service}/toggle', [AdminDashboard::class, 'toggleService'])->name('services.toggle');
    Route::put('/services/{service}/report', [AdminDashboard::class, 'reportService'])->name('services.report');
    Route::delete('/services/{service}', [AdminDashboard::class, 'deleteService'])->name('services.destroy');
    
    // Réservations
    Route::get('/bookings', [AdminDashboard::class, 'bookings'])->name('bookings.index');
    Route::get('/bookings/{booking}', [AdminDashboard::class, 'showBooking'])->name('bookings.show');
    Route::get('/bookings/provider/{user}', [AdminDashboard::class, 'providerBookings'])->name('bookings.provider');
    Route::put('/bookings/{booking}/report', [AdminDashboard::class, 'reportBooking'])->name('bookings.report');
    Route::put('/bookings/{booking}/cancel', [AdminDashboard::class, 'cancelBooking'])->name('bookings.cancel');
    
    // Categories
    Route::resource('/categories', CategoryController::class);
    Route::put('/categories/{category}/toggle', [CategoryController::class, 'toggle'])->name('categories.toggle');
    
    // Rapports
    Route::get('/reports', [\App\Http\Controllers\Admin\ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/users', [\App\Http\Controllers\Admin\ReportController::class, 'users'])->name('reports.users');
    Route::get('/reports/services', [\App\Http\Controllers\Admin\ReportController::class, 'services'])->name('reports.services');
    Route::get('/reports/revenue', [\App\Http\Controllers\Admin\ReportController::class, 'revenue'])->name('reports.revenue');
});
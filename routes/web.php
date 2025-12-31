<?php

use App\Livewire\ManageCategories;
use App\Livewire\Dashboard;
use App\Livewire\TransactionDetails;
use App\Livewire\NewTransaction;
use App\Livewire\Planilhas;
use App\Livewire\Relatorios;
use App\Livewire\Perfil;
use App\Livewire\Configuracoes;
use App\Livewire\UpgradePlan;
use App\Livewire\Admin\AdminDashboard;
use App\Livewire\Admin\UserManagement;
use App\Livewire\Admin\AppSettings;
use App\Http\Controllers\Auth\SocialiteController;
use Illuminate\Support\Facades\Auth;

// Landing Page
// Landing Page
Route::get('/', function () {
    $testimonials = App\Models\Testimonial::with('user')
        ->where('is_approved', true)
        ->latest()
        ->take(3)
        ->get();
    return view('landing', compact('testimonials'));
})->name('landing');

// Dashboard (Home)
Route::get('/dashboard', Dashboard::class)->middleware('auth')->name('dashboard');

// Categories
Route::get('/categories', ManageCategories::class)->middleware('auth')->name('categories');

// Transactions (specific routes BEFORE parameterized routes)
Route::get('/transactions', App\Livewire\Transactions::class)->middleware('auth')->name('transactions.index');
Route::get('/transactions/new', NewTransaction::class)->middleware('auth')->name('transactions.new');
Route::get('/transactions/{id}/edit', NewTransaction::class)->middleware('auth')->name('transactions.edit');
Route::get('/transactions/{id}', TransactionDetails::class)->middleware('auth')->name('transactions.show');

// Premium Features (protected by premium middleware)
Route::middleware(['auth', 'premium'])->group(function () {
    Route::get('/planilhas', Planilhas::class)->name('planilhas');
    Route::get('/relatorios', Relatorios::class)->name('relatorios');
});

// Upgrade Page (for non-premium users)
Route::get('/upgrade', UpgradePlan::class)->middleware('auth')->name('upgrade');

// Other Screens
Route::get('/perfil', Perfil::class)->middleware('auth')->name('perfil');
Route::get('/meu-depoimento', App\Livewire\User\MyTestimonial::class)->middleware('auth')->name('my-testimonial');
Route::get('/configuracoes', Configuracoes::class)->middleware('auth')->name('configuracoes');

// Loans Management
Route::middleware(['auth'])->prefix('loans')->group(function () {
    Route::get('/', App\Livewire\Loans\Index::class)->name('loans.index');
    Route::get('/create', App\Livewire\Loans\Create::class)->name('loans.create');
    Route::get('/{loan}', App\Livewire\Loans\Show::class)->name('loans.show');
    Route::get('/{loan}/edit', App\Livewire\Loans\Edit::class)->name('loans.edit');
});

// Admin Area (protected by admin middleware)
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/', AdminDashboard::class)->name('admin.dashboard');
    Route::get('/users', UserManagement::class)->name('admin.users');
    Route::get('/settings', AppSettings::class)->name('admin.settings');
    Route::get('/monitoring', App\Livewire\Admin\Monitoring::class)->name('admin.monitoring');
    Route::get('/testimonials', App\Livewire\Admin\ManageTestimonials::class)->name('admin.testimonials');
});

Route::middleware(['auth', App\Http\Middleware\EnsureUserIsPremium::class])->group(function () {
    // Rotas estritamente premium (nenhuma por enquanto, pois budgets terá paywall visual)
});

Route::middleware(['auth'])->group(function () {
    Route::get('/orcamentos', App\Livewire\Budgets::class)->name('budgets');
});

Route::get('/admin/stop-impersonating', function () {
    if (session()->has('impersonator_id')) {
        auth()->loginUsingId(session('impersonator_id'));
        session()->forget('impersonator_id');
        return redirect()->route('admin.users');
    }
    return redirect()->route('dashboard');
})->name('admin.stop-impersonating');

// Auth
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/auth/google', [SocialiteController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [SocialiteController::class, 'handleGoogleCallback']);

Route::post('/logout', function () {
    Auth::logout();
    return redirect('/login');
})->name('logout');

// Static Pages
Route::view('/terms', 'static.terms')->name('terms');
Route::view('/privacy', 'static.privacy')->name('privacy');
Route::view('/help', 'static.help')->name('help');

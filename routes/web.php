<?php

use App\Http\Controllers\HomeController;
use App\Livewire\Banners\BannerManager;
use App\Livewire\Categories\CategoryManager;
use App\Livewire\Products\ProductManager;
use App\Livewire\Quotes\QuoteManager;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/product/detail/{slug}', [HomeController::class, 'productDetail'])->name('product.detail');
Route::get('/product/{slug}', [HomeController::class, 'productDetail']);
Route::post('/quotes/submit', [HomeController::class, 'submitQuote'])->name('quotes.submit');

Route::get('/lang/{locale}', function (string $locale) {
    if (! in_array($locale, ['en', 'de'])) {
        $locale = 'en';
    }

    session(['locale' => $locale]);

    $cookieVal = $locale === 'de' ? '/en/de' : '/en/en';
    cookie()->queue('googtrans', $cookieVal, 60 * 24 * 30, '/', null, false, false);

    return redirect()->back();
})->name('lang.switch');


Route::middleware(['auth', 'admin'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');

    // Products
    Route::get('admin/products', ProductManager::class)->name('products.index');
    Route::redirect('products', 'admin/products');

    // Categories
    Route::get('admin/categories', CategoryManager::class)->name('categories.index');
    Route::redirect('categories', 'admin/categories');

    // Banners
    Route::get('admin/banners', BannerManager::class)->name('banners.index');
    Route::redirect('banners', 'admin/banners');

    // Quotes
    Route::get('admin/quotes', QuoteManager::class)->name('quotes.index');
    Route::redirect('quotes', 'admin/quotes');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';

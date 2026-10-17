<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Category;
use App\Models\Product;
use App\Models\QuoteItem;
use App\Models\QuoteRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Show the promotional products storefront landing page.
     */
    public function index(Request $request): View
    {
        $categories = Category::active()->orderBy('sort_order')->orderBy('name')->get();
        $heroBanners = Banner::active()->hero()->get();
        $categoryTiles = Banner::active()->categoryTiles()->get();

        // Highlight sections matching Sinovation
        $newProducts = Product::active()->new()
            ->with(['primaryImage', 'prices', 'categories', 'variants'])
            ->latest()
            ->take(6)
            ->get();

        $hotProducts = Product::active()->hot()
            ->with(['primaryImage', 'prices', 'categories', 'variants'])
            ->latest()
            ->take(6)
            ->get();

        $ecoProducts = Product::active()->eco()
            ->with(['primaryImage', 'prices', 'categories', 'variants'])
            ->latest()
            ->take(6)
            ->get();

        // Catalog query with instant filters
        $catalogQuery = Product::active()->with(['primaryImage', 'prices', 'categories', 'variants']);

        if ($request->filled('search')) {
            $catalogQuery->search($request->string('search'));
        }

        if ($request->filled('category')) {
            $catSlug = (string) $request->string('category');
            $catalogQuery->whereHas('categories', function ($q) use ($catSlug) {
                $q->where('slug', $catSlug);
            });
        }

        if ($request->get('filter') === 'new') {
            $catalogQuery->where('is_new', true);
        } elseif ($request->get('filter') === 'hot') {
            $catalogQuery->where('is_hot', true);
        } elseif ($request->get('filter') === 'eco') {
            $catalogQuery->where('is_eco', true);
        }

        $catalogProducts = $catalogQuery->latest()->get();

        return view('welcome', [
            'categories' => $categories,
            'heroBanners' => $heroBanners,
            'categoryTiles' => $categoryTiles,
            'newProducts' => $newProducts,
            'hotProducts' => $hotProducts,
            'ecoProducts' => $ecoProducts,
            'catalogProducts' => $catalogProducts,
            'currentCategory' => $request->get('category', ''),
            'currentFilter' => $request->get('filter', 'all'),
            'searchQuery' => $request->get('search', ''),
        ]);
    }

    /**
     * Handle quote inquiry submission from the landing page.
     */
    public function submitQuote(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:190'],
            'company' => ['nullable', 'string', 'max:150'],
            'phone' => ['nullable', 'string', 'max:40'],
            'destination' => ['nullable', 'string', 'max:100'],
            'needed_by' => ['nullable', 'date'],
            'product_id' => ['nullable', 'integer', 'exists:products,id'],
            'variant_id' => ['nullable', 'integer', 'exists:product_variants,id'],
            'quantity' => ['required', 'integer', 'min:1'],
            'print_option' => ['nullable', 'string', 'max:150'],
            'message' => ['nullable', 'string', 'max:1000'],
            'artwork' => ['nullable', 'file', 'max:10240'], // 10MB
        ]);

        $artworkPath = null;
        if ($request->hasFile('artwork')) {
            $artworkPath = $request->file('artwork')->store('quote_artworks', 'public');
        }

        $quoteNumber = 'QT-' . date('Y') . '-' . strtoupper(Str::random(5));

        $quote = QuoteRequest::create([
            'user_id' => auth()->id(),
            'quote_number' => $quoteNumber,
            'name' => $validated['name'],
            'company' => $validated['company'] ?? null,
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'destination' => $validated['destination'] ?? null,
            'needed_by' => $validated['needed_by'] ?? null,
            'message' => $validated['message'] ?? null,
            'artwork_path' => $artworkPath,
            'status' => 'new',
        ]);

        if (! empty($validated['product_id'])) {
            QuoteItem::create([
                'quote_id' => $quote->id,
                'product_id' => $validated['product_id'],
                'variant_id' => $validated['variant_id'] ?? null,
                'quantity' => $validated['quantity'],
                'print_option' => $validated['print_option'] ?? null,
                'notes' => $validated['message'] ?? null,
            ]);
        }

        return redirect()->to(url()->previous() . '#quote-success')->with([
            'quote_success' => true,
            'quote_number' => $quoteNumber,
            'customer_name' => $validated['name'],
        ]);
    }

    /**
     * Show a single product details page matching Sinovation design.
     */
    public function productDetail(string $slug): View
    {
        $product = Product::active()
            ->where(function ($query) use ($slug) {
                $query->where('slug', $slug)
                    ->orWhere('product_code', $slug);
            })
            ->with(['primaryImage', 'images', 'prices', 'variants', 'categories'])
            ->firstOrFail();

        $categories = Category::active()->orderBy('sort_order')->orderBy('name')->get();

        // Related products in the same category
        $catIds = $product->categories->pluck('id')->all();
        $relatedProducts = Product::active()
            ->where('id', '!=', $product->id)
            ->when(! empty($catIds), function ($query) use ($catIds) {
                $query->whereHas('categories', function ($q) use ($catIds) {
                    $q->whereIn('categories.id', $catIds);
                });
            })
            ->with(['primaryImage', 'prices', 'categories', 'variants'])
            ->latest()
            ->take(6)
            ->get();

        return view('product-detail', [
            'product' => $product,
            'categories' => $categories,
            'relatedProducts' => $relatedProducts,
        ]);
    }
}


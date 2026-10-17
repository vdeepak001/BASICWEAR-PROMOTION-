@extends('layouts.storefront')

@section('content')
<div class="pb-16 w-full" x-data="{ heroSlide: 0 }">
    <!-- HERO BANNER CAROUSEL — images and titles come from the banners table -->
    @if ($heroBanners->isNotEmpty())
    <section class="w-full px-4 sm:px-6 lg:px-8 xl:px-10 pt-3 sm:pt-4">
        <div class="relative overflow-hidden rounded-2xl shadow-lg bg-gray-900 aspect-[2.2/1] sm:aspect-[2.8/1] lg:aspect-[3.8/1] xl:aspect-[4.5/1] max-h-[460px]">
            @foreach ($heroBanners as $hIdx => $hBanner)
                <div
                    x-show="heroSlide === {{ $hIdx }}"
                    x-transition:enter="transition ease-out duration-700"
                    x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-300"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    @if ($hIdx !== 0) x-cloak @endif
                    class="absolute inset-0"
                >
                    <img src="{{ $hBanner->image_url }}" alt="{{ $hBanner->title }}" class="size-full object-cover" />
                    <div class="absolute inset-0 bg-gradient-to-t from-black/85 via-black/40 to-transparent flex items-end p-6 sm:p-10 lg:p-12">
                        <div class="max-w-xl text-white space-y-2">
                            <span class="inline-block px-2.5 py-1 rounded-md text-[10px] font-extrabold uppercase tracking-wider bg-[#ed7100] text-white">
                                Factory Direct Advantage
                            </span>
                            <h2 class="text-xl sm:text-3xl lg:text-4xl font-extrabold leading-tight text-white drop-shadow">
                                {{ $hBanner->title ?: 'Custom Promotional Merchandise' }}
                            </h2>
                            <p class="text-xs sm:text-sm text-gray-200 line-clamp-2">
                                Full colour 3D branding, air &amp; sea freight delivery, quantity tier price breaks, and bespoke manufacturing.
                            </p>
                            <div class="pt-2 flex items-center gap-3">
                                @if ($hBanner->link_url)
                                    <a href="{{ $hBanner->link_url }}" class="inline-flex items-center gap-1 px-4 py-2 rounded-lg bg-white text-gray-900 text-xs font-bold hover:bg-[#ed7100] hover:text-white transition-colors">
                                        <span>Explore Range</span>
                                        <svg class="size-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                                        </svg>
                                    </a>
                                @endif
                                <button type="button" @click="openQuoteModal(null)" class="inline-flex items-center gap-1 px-4 py-2 rounded-lg bg-[#ed7100] hover:bg-orange-600 text-white text-xs font-bold transition-colors cursor-pointer">
                                    <span>Instant Quote</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

            <!-- Slider Controls -->
            @if ($heroBanners->count() > 1)
                <button
                    type="button"
                    @click="heroSlide = (heroSlide === 0 ? {{ $heroBanners->count() - 1 }} : heroSlide - 1)"
                    class="absolute left-3 top-1/2 -translate-y-1/2 p-2 rounded-full bg-black/50 hover:bg-black/80 text-white backdrop-blur transition-colors cursor-pointer"
                >
                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                    </svg>
                </button>
                <button
                    type="button"
                    @click="heroSlide = (heroSlide === {{ $heroBanners->count() - 1 }} ? 0 : heroSlide + 1)"
                    class="absolute right-3 top-1/2 -translate-y-1/2 p-2 rounded-full bg-black/50 hover:bg-black/80 text-white backdrop-blur transition-colors cursor-pointer"
                >
                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                    </svg>
                </button>
            @endif
        </div>
    </section>
    @endif

    <!-- VISUAL CATEGORY GRID TILES (Sinovation 1:1 Squares - Full Width) -->
    <section class="w-full px-4 sm:px-6 lg:px-8 xl:px-10 py-6 sm:py-8">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg sm:text-xl font-bold text-gray-900 tracking-tight flex items-center gap-2">
                <span class="w-1.5 h-5 rounded-full bg-[#ed7100] inline-block"></span>
                Popular Categories
            </h3>
            <span class="text-xs text-gray-500 font-medium">Select a category to explore specifications</span>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 xl:grid-cols-8 2xl:grid-cols-10 gap-3 sm:gap-4">
            @foreach ($categories->take(12) as $cat)
                <a
                    href="{{ route('home', ['category' => $cat->slug]) }}#catalog"
                    class="group relative block aspect-square rounded-xl overflow-hidden bg-white shadow-2xs hover:shadow-xl transition-all duration-300 border border-gray-200/80 hover:-translate-y-0.5 {{ ($currentCategory ?? '') === $cat->slug ? 'ring-2 ring-[#ed7100]' : '' }}"
                >
                    @php
                        $tileBanner = $categoryTiles->firstWhere('title', $cat->name);
                        $sampleTileImages = [
                            'lanyards' => 'https://sinovationpromotions.com/api/media/banner/Lanyards_2025.jpg',
                            'headwear' => 'https://sinovationpromotions.com/api/media/banner/Bucket%20Hat.jpg',
                            'sports' => 'https://sinovationpromotions.com/api/media/banner/Sports_2024.jpg',
                            'leisure' => 'https://sinovationpromotions.com/api/media/product/5cm%20Mini%20Magic%20Cube_1.jpg',
                            'drinkware' => 'https://sinovationpromotions.com/api/media/banner/Drinkware_2024.jpg',
                            'fans' => 'https://sinovationpromotions.com/api/media/banner/Leisure_2024.jpg',
                            'bags' => 'https://sinovationpromotions.com/api/media/banner/Bags_2025.jpg',
                            'tools' => 'https://sinovationpromotions.com/api/media/banner/Car%20Air%20Freshener_2025.jpg',
                            'technology' => 'https://sinovationpromotions.com/api/media/banner/Technology_2024.jpg',
                            'umbrellas' => 'https://sinovationpromotions.com/api/media/banner/Umbrellas_2024.jpg',
                            'pets' => 'https://sinovationpromotions.com/api/media/banner/Pets_2024.jpg',
                            'office' => 'https://sinovationpromotions.com/api/media/banner/Car%20Air%20Freshener_2025.jpg',
                            'id' => 'https://sinovationpromotions.com/api/media/banner/Lanyards_2025.jpg',
                        ];
                        $imgSrc = $cat->icon_url ?: ($tileBanner?->image_url ?? ($sampleTileImages[strtolower($cat->slug)] ?? null));
                    @endphp

                    @if ($imgSrc)
                        <img src="{{ $imgSrc }}" alt="{{ $cat->name }}" class="size-full object-cover group-hover:scale-108 transition-transform duration-500" />
                    @else
                        <div class="size-full bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center p-4">
                            <span class="text-3xl font-extrabold text-gray-400">{{ substr($cat->name, 0, 2) }}</span>
                        </div>
                    @endif

                    <!-- Dark Overlay with Category Title -->
                    <div class="absolute inset-0 bg-gradient-to-t from-gray-950/80 via-transparent to-transparent flex items-end p-3">
                        <div class="w-full">
                            <span class="block text-xs sm:text-sm font-bold text-white group-hover:text-orange-400 transition-colors drop-shadow">
                                {{ $cat->name }}
                            </span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </section>

    <!-- NEW ARRIVALS SECTION (Sinovation Style - Full Width) -->
    @if ($newProducts->isNotEmpty() && blank($currentCategory) && ($currentFilter === 'all' || $currentFilter === 'new'))
        <section class="w-full px-4 sm:px-6 lg:px-8 xl:px-10 py-6">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-2">
                    <span class="px-2 py-0.5 rounded text-[10px] font-extrabold bg-red-600 text-white uppercase tracking-wider shadow-xs">NEW</span>
                    <h3 class="text-xl sm:text-2xl font-extrabold text-gray-900 tracking-tight">
                        New Releases &amp; Latest Novelties
                    </h3>
                </div>
                <a href="{{ route('home', ['filter' => 'new']) }}" class="text-xs font-bold text-[#ed7100] hover:underline flex items-center gap-1">
                    <span>View All New</span>
                    <svg class="size-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                    </svg>
                </a>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 2xl:grid-cols-7 gap-4">
                @foreach ($newProducts as $np)
                    @include('partials.product-card', ['product' => $np, 'badge' => 'new'])
                @endforeach
            </div>
        </section>
    @endif

    <!-- HOT SELLERS SECTION (Full Width) -->
    @if ($hotProducts->isNotEmpty() && blank($currentCategory) && ($currentFilter === 'all' || $currentFilter === 'hot'))
        <section class="w-full px-4 sm:px-6 lg:px-8 xl:px-10 py-6">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-2">
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-extrabold bg-amber-500 text-white uppercase tracking-wider shadow-xs">
                        HOT
                    </span>
                    <h3 class="text-xl sm:text-2xl font-extrabold text-gray-900 tracking-tight">
                        Most Popular &amp; Best Selling Giveaways
                    </h3>
                </div>
                <a href="{{ route('home', ['filter' => 'hot']) }}" class="text-xs font-bold text-[#ed7100] hover:underline flex items-center gap-1">
                    <span>View All Hot</span>
                    <svg class="size-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                    </svg>
                </a>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 2xl:grid-cols-7 gap-4">
                @foreach ($hotProducts as $hp)
                    @include('partials.product-card', ['product' => $hp, 'badge' => 'hot'])
                @endforeach
            </div>
        </section>
    @endif

    <!-- MAIN CATALOG LISTING (Full Width) -->
    <section id="catalog" class="w-full px-4 sm:px-6 lg:px-8 xl:px-10 py-8">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6 pb-3 border-b border-gray-200">
            <div>
                <h3 class="text-xl sm:text-2xl font-extrabold text-gray-900 tracking-tight">
                    @if ($searchQuery)
                        Search Results for "{{ $searchQuery }}"
                    @elseif ($currentCategory)
                        @php
                            $selectedCatModel = $categories->firstWhere('slug', $currentCategory);
                        @endphp
                        {{ $selectedCatModel ? $selectedCatModel->name : 'Category Products' }}
                    @elseif ($currentFilter === 'eco')
                        Eco-Friendly &amp; Sustainable Merchandise
                    @elseif ($currentFilter === 'new')
                        New Arrivals
                    @elseif ($currentFilter === 'hot')
                        Hot Sellers
                    @else
                        Full Product Catalog
                    @endif
                </h3>
                <p class="text-xs text-gray-500 mt-0.5">
                    Showing {{ $catalogProducts->count() }} items available for bulk custom branding
                </p>
            </div>

            <!-- Reset Filters if active -->
            @if ($currentCategory || $searchQuery || ($currentFilter && $currentFilter !== 'all'))
                <a href="{{ route('home') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-gray-200 hover:bg-gray-300 text-gray-700 text-xs font-semibold self-start sm:self-auto transition-colors">
                    <svg class="size-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                    <span>Clear All Filters</span>
                </a>
            @endif
        </div>

        <!-- Product Cards Grid (Each card shows ONLY image and title, clicking redirects to details page) -->
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 2xl:grid-cols-7 gap-4">
            @forelse ($catalogProducts as $prod)
                @include('partials.product-card', ['product' => $prod])
            @empty
                <div class="col-span-full py-16 text-center bg-white rounded-2xl border border-gray-200 p-8">
                    <div class="size-16 rounded-full bg-orange-50 text-orange-500 flex items-center justify-center mx-auto mb-3">
                        <svg class="size-8" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                        </svg>
                    </div>
                    <h4 class="text-base font-bold text-gray-900">No products matched your criteria</h4>
                    <p class="text-xs text-gray-500 mt-1 max-w-sm mx-auto">
                        Try searching for another keyword or browse our categories from the navigation bar above.
                    </p>
                    <div class="mt-4">
                        <a href="{{ route('home') }}" class="inline-flex px-4 py-2 rounded-lg bg-gray-900 text-white text-xs font-bold hover:bg-[#ed7100] transition-colors">
                            View Full Collection
                        </a>
                    </div>
                </div>
            @endforelse
        </div>
    </section>

    <!-- VALUE PROPOSITION / TRUST ROW (B2B Promo Features - Full Width) -->
    <section class="w-full px-4 sm:px-6 lg:px-8 xl:px-10 py-8">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="rounded-xl bg-white p-5 border border-gray-200/80 shadow-2xs flex items-start gap-3.5">
                <div class="rounded-lg bg-orange-50 p-2.5 text-[#ed7100] shrink-0">
                    <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                </div>
                <div>
                    <h4 class="text-xs font-bold text-gray-900 uppercase tracking-wide">Factory Direct Pricing</h4>
                    <p class="text-xs text-gray-500 mt-0.5">Tiered bulk discounts scaling from 100 to 50,000+ units.</p>
                </div>
            </div>

            <div class="rounded-xl bg-white p-5 border border-gray-200/80 shadow-2xs flex items-start gap-3.5">
                <div class="rounded-lg bg-blue-50 p-2.5 text-blue-600 shrink-0">
                    <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 12 3.269 3.125A59.769 59.769 0 0 1 21.485 12 59.768 59.768 0 0 1 3.27 20.875L5.999 12Zm0 0h7.5" />
                    </svg>
                </div>
                <div>
                    <h4 class="text-xs font-bold text-gray-900 uppercase tracking-wide">Air &amp; Sea Freight</h4>
                    <p class="text-xs text-gray-500 mt-0.5">Fast 3-4 week air courier or economical sea freight for large orders.</p>
                </div>
            </div>

            <div class="rounded-xl bg-white p-5 border border-gray-200/80 shadow-2xs flex items-start gap-3.5">
                <div class="rounded-lg bg-purple-50 p-2.5 text-purple-600 shrink-0">
                    <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                    </svg>
                </div>
                <div>
                    <h4 class="text-xs font-bold text-gray-900 uppercase tracking-wide">Free Virtual Mockups</h4>
                    <p class="text-xs text-gray-500 mt-0.5">Visual 3D digital artwork proofs before production begins.</p>
                </div>
            </div>

            <div class="rounded-xl bg-white p-5 border border-gray-200/80 shadow-2xs flex items-start gap-3.5">
                <div class="rounded-lg bg-emerald-50 p-2.5 text-emerald-600 shrink-0">
                    <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z" />
                    </svg>
                </div>
                <div>
                    <h4 class="text-xs font-bold text-gray-900 uppercase tracking-wide">Certified Quality</h4>
                    <p class="text-xs text-gray-500 mt-0.5">Rigorous inspection, durable prints, and eco-certified materials.</p>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@extends('layouts.storefront')

@section('content')
<div class="py-4 sm:py-6 w-full" x-data="{ activeImage: '{{ $product->primary_image_url }}' }">
    <div class="w-full px-4 sm:px-6 lg:px-8 xl:px-10 space-y-4">
        
        <!-- Breadcrumbs Bar (Sinovation style) -->
        @php
            $primaryCat = $product->categories->first();
            $allImages = $product->images;
            if ($allImages->isEmpty() && $product->primary_image_url) {
                $allImages = collect([(object)['image_url' => $product->primary_image_url]]);
            }
        @endphp
        <div class="rounded-lg bg-gray-200/90 px-4 py-2 text-xs text-gray-600 flex items-center gap-2">
            <a href="{{ route('home') }}" class="hover:text-[#ed7100] transition-colors">
                <svg class="size-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M9.293 2.293a1 1 0 011.414 0l7 7A1 1 0 0117 11h-1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-3a1 1 0 00-1-1H9a1 1 0 00-1 1v3a1 1 0 01-1 1H5a1 1 0 01-1-1v-6H3a1 1 0 01-.707-1.707l7-7z" clip-rule="evenodd" />
                </svg>
            </a>
            <span>/</span>
            <a href="{{ route('home') }}" class="hover:text-[#ed7100] transition-colors font-medium">All</a>
            @if ($primaryCat)
                <span>/</span>
                <a href="{{ route('home', ['category' => $primaryCat->slug]) . '#catalog' }}" class="hover:text-[#ed7100] transition-colors font-medium">
                    {{ $primaryCat->name }}
                </a>
            @endif
            <span>/</span>
            <span class="font-bold text-gray-900">{{ $product->product_code }}</span>
        </div>

        <!-- Product Details Main White Card (Sinovation 2-Column Exact Layout) -->
        <div class="bg-white rounded-xl border border-gray-200 p-6 sm:p-8 shadow-xs">
            <div class="flex flex-col lg:flex-row gap-8 lg:gap-12 items-start">
                
                <!-- Left Column: Gallery & Thumbnails (42% width) -->
                <div class="w-full lg:w-5/12 xl:w-5/12 shrink-0 space-y-4">
                    <!-- Main Display Image Box -->
                    <div class="w-full h-80 sm:h-96 lg:h-[450px] rounded-lg border border-gray-200 p-6 flex items-center justify-center bg-white relative overflow-hidden group">
                        <!-- Floating Badges -->
                        <div class="absolute top-3 left-3 z-10 flex flex-col gap-1 items-start">
                            @if ($product->is_new)
                                <span class="px-2 py-0.5 rounded text-[10px] font-extrabold bg-red-600 text-white uppercase tracking-wider shadow-xs">
                                    NEW
                                </span>
                            @endif
                            @if ($product->is_hot)
                                <span class="px-2 py-0.5 rounded text-[10px] font-extrabold bg-amber-500 text-white uppercase tracking-wider shadow-xs">
                                    HOT
                                </span>
                            @endif
                            @if ($product->is_eco)
                                <span class="px-2 py-0.5 rounded text-[10px] font-extrabold bg-emerald-600 text-white uppercase tracking-wider shadow-xs flex items-center gap-1">
                                    <svg class="size-2.5 fill-current" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4.25 2A2.25 2.25 0 002 4.25v2c0 .245.04.48.114.7A10.01 10.01 0 0010 18a10.01 10.01 0 007.886-11.05A2.25 2.25 0 0018 6.25v-2A2.25 2.25 0 0015.75 2h-11.5zM3.5 4.25a.75.75 0 01.75-.75h11.5a.75.75 0 01.75.75v1.836a8.51 8.51 0 01-6.19 8.211A8.51 8.51 0 013.5 6.086V4.25z" clip-rule="evenodd" />
                                    </svg>
                                    ECO
                                </span>
                            @endif
                        </div>

                        <!-- Product Code Badge -->
                        <span class="absolute top-3 right-3 z-10 px-2 py-0.5 rounded bg-gray-900 text-white text-xs font-mono font-bold tracking-tight">
                            #{{ $product->product_code }}
                        </span>

                        <!-- Active Image with dynamic Alpine binding & fallback -->
                        <img
                            :src="activeImage"
                            src="{{ $product->primary_image_url }}"
                            alt="{{ $product->name }}"
                            class="max-h-full max-w-full object-contain object-center transform transition-transform duration-300 group-hover:scale-105"
                        />
                    </div>

                    <!-- Thumbnail Strip (Sinovation 4-Box Preview Strip) -->
                    @if ($allImages->count() > 1)
                        <div class="flex items-center gap-3 overflow-x-auto no-scrollbar py-1">
                            @foreach ($allImages as $idx => $img)
                                <button
                                    type="button"
                                    @click="activeImage = '{{ $img->image_url }}'"
                                    class="size-16 sm:size-20 rounded-md border-2 p-1 bg-white shrink-0 overflow-hidden transition-all cursor-pointer"
                                    :class="activeImage === '{{ $img->image_url }}' ? 'border-[#ed7100] ring-2 ring-orange-500/20 shadow-xs' : 'border-gray-200 hover:border-gray-400'"
                                >
                                    <img src="{{ $img->image_url }}" alt="Thumbnail {{ $idx + 1 }}" class="size-full object-contain" />
                                </button>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Right Column: Product Title, Specs, Pricing & Terms (58% width) -->
                <div class="w-full lg:w-7/12 space-y-5">
                    
                    <!-- Title & Item Code -->
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 tracking-tight leading-tight">
                            {{ $product->name }} <span class="font-normal text-gray-500 text-xl sm:text-2xl ml-1">{{ $product->product_code }}</span>
                        </h1>
                    </div>

                    <!-- Description & Bullet Points -->
                    <div class="text-sm text-gray-700 leading-relaxed space-y-2">
                        @if ($product->short_desc)
                            <p>{{ $product->short_desc }}</p>
                        @endif

                        @if ($product->description)
                            <div class="text-sm text-gray-700 whitespace-pre-line leading-relaxed">
                                {{ $product->description }}
                            </div>
                        @endif
                    </div>

                    <!-- Air Freight & Sea Freight Lead Time Notice (Sinovation signature callout) -->
                    <div class="p-3.5 rounded-lg bg-orange-50/70 border border-orange-100 text-xs text-gray-800 leading-relaxed">
                        A delivery time of <strong class="text-[#ed7100] font-bold">{{ $product->lead_time ?: '4 - 5 weeks' }}</strong> applies to this product by air freight. If you have more time, the goods can be sea freighted on request to reduce the freight cost!
                    </div>

                    <!-- QUANTITY TIER PRICING TABLE (Sinovation exact signature table with ORANGE HEADER) -->
                    @if ($product->prices->isNotEmpty())
                        <div class="rounded-lg overflow-hidden border border-gray-200 shadow-2xs">
                            <!-- Signature Orange Header -->
                            <div style="background-color: #ed7100;" class="px-4 py-2 text-center text-xs sm:text-sm font-bold text-white tracking-wide">
                                Price includes {{ $product->price_includes ?: 'full colour printed on all sides' }}
                            </div>

                            <!-- Tier Matrix -->
                            <div class="overflow-x-auto">
                                <table class="w-full text-xs text-center border-collapse">
                                    <thead>
                                        <tr class="bg-gray-50 border-b border-gray-200">
                                            <th class="py-2.5 px-3 font-semibold text-gray-600 border-r border-gray-200 w-24">Qty</th>
                                            @foreach ($product->prices as $priceTier)
                                                <th class="py-2.5 px-3 font-bold text-gray-800 border-r border-gray-200 last:border-r-0">
                                                    {{ number_format($priceTier->min_qty) }}@if ($priceTier->max_qty)+@endif
                                                </th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="bg-white">
                                            <td class="py-2.5 px-3 font-semibold text-gray-700 border-r border-gray-200">Unit Price</td>
                                            @foreach ($product->prices as $priceTier)
                                                <td class="py-2.5 px-3 font-extrabold text-gray-900 border-r border-gray-200 last:border-r-0">
                                                    {{ $priceTier->currency }} ${{ number_format($priceTier->unit_price, 2) }}
                                                </td>
                                            @endforeach
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif

                    <!-- INFORMATION / TERMS BOX (Sinovation exact signature box with ORANGE HEADER) -->
                    <div class="rounded-lg overflow-hidden border border-gray-200 shadow-2xs">
                        <div style="background-color: #ed7100;" class="px-4 py-2 text-center text-xs sm:text-sm font-bold text-white tracking-wide">
                            Information
                        </div>
                        <div class="p-4 bg-white text-xs text-gray-700 space-y-1.5 leading-relaxed">
                            <p>1. The above price is for Australia, Canada, EU, New Zealand, UK &amp; USA. For other countries, please contact us.</p>
                            <p>2. The above price has included set up cost &amp; delivery cost by air freight. For remote places, the extra cost is 35USD/shipment.</p>
                            <p>3. The above price is valid in 2026.</p>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row items-center gap-3 pt-2">
                        <button
                            type="button"
                            @click="openQuoteModal({
                                id: {{ $product->id }},
                                code: '{{ addslashes($product->product_code) }}',
                                name: '{{ addslashes($product->name) }}',
                                moq: {{ (int) ($product->moq ?? 250) }},
                                image: '{{ addslashes($product->primary_image_url ?? '') }}'
                            })"
                            style="background-color: #ed7100;"
                            class="w-full sm:w-auto px-6 py-3 rounded-lg text-white text-xs sm:text-sm font-bold shadow-md hover:opacity-90 transition-all flex items-center justify-center gap-2 cursor-pointer"
                        >
                            <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                            </svg>
                            <span>Request Quote for this Product</span>
                        </button>

                        <a
                            href="mailto:sales@basicwearpromotion.com?subject=Inquiry for Item {{ $product->product_code }} - {{ urlencode($product->name) }}"
                            class="w-full sm:w-auto px-5 py-3 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-800 text-xs sm:text-sm font-bold transition-colors text-center"
                        >
                            Email Question
                        </a>
                    </div>

                    <!-- PRODUCT INFO SPECIFICATIONS TABLE (Sinovation exact style) -->
                    <div class="pt-4 border-t border-gray-100">
                        <h3 class="text-base font-bold text-gray-900 mb-3">Product Info</h3>

                        <div class="rounded-lg bg-gray-50/70 p-4 border border-gray-200">
                            <dl class="divide-y divide-gray-200 text-xs">
                                <div class="py-2.5 grid grid-cols-3 gap-2">
                                    <dt class="font-semibold text-gray-600">Product Size</dt>
                                    <dd class="col-span-2 text-gray-900 font-medium">{{ $product->size ?: '50mm x 50mm x 50mm' }}</dd>
                                </div>
                                <div class="py-2.5 grid grid-cols-3 gap-2">
                                    <dt class="font-semibold text-gray-600">Material</dt>
                                    <dd class="col-span-2 text-gray-900 font-medium">{{ $product->material ?: 'PS plastic, PP sticker panels' }}</dd>
                                </div>
                                <div class="py-2.5 grid grid-cols-3 gap-2">
                                    <dt class="font-semibold text-gray-600">Colour</dt>
                                    <dd class="col-span-2 text-gray-900 font-medium">
                                        @if ($product->variants->isNotEmpty())
                                            <div class="flex items-center gap-1.5 flex-wrap">
                                                @foreach ($product->variants as $var)
                                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded bg-white border border-gray-200 text-[11px]">
                                                        <span class="size-2 rounded-full border border-gray-300" style="background-color: {{ $var->color_code ?: '#94a3b8' }};"></span>
                                                        {{ $var->variant_name }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        @else
                                            White / Custom Pantone Matched
                                        @endif
                                    </dd>
                                </div>
                                <div class="py-2.5 grid grid-cols-3 gap-2">
                                    <dt class="font-semibold text-gray-600">Logo</dt>
                                    <dd class="col-span-2 text-gray-900 font-medium">{{ $product->print_method ?: 'Digital Print' }}</dd>
                                </div>
                                <div class="py-2.5 grid grid-cols-3 gap-2">
                                    <dt class="font-semibold text-gray-600">MOQ</dt>
                                    <dd class="col-span-2 text-gray-900 font-bold">{{ number_format($product->moq ?? 250) }} pcs</dd>
                                </div>
                                <div class="py-2.5 grid grid-cols-3 gap-2">
                                    <dt class="font-semibold text-gray-600">Packing</dt>
                                    <dd class="col-span-2 text-gray-900 font-medium">{{ $product->packing_info ?: '250pcs/ctn; Carton Size: 55x30x30cm; NW/GW: 13/14KG' }}</dd>
                                </div>
                                @if ($product->short_desc)
                                    <div class="py-2.5 grid grid-cols-3 gap-2">
                                        <dt class="font-semibold text-gray-600">Remark</dt>
                                        <dd class="col-span-2 text-gray-900 font-medium">{{ $product->short_desc }}</dd>
                                    </div>
                                @endif
                            </dl>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- RELATED PRODUCTS SECTION -->
        @if ($relatedProducts->isNotEmpty())
            <div class="pt-8">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                        <span class="w-1.5 h-4 rounded-full bg-[#ed7100] inline-block"></span>
                        Related Products
                    </h3>
                    @if ($primaryCat)
                        <a href="{{ route('home', ['category' => $primaryCat->slug]) . '#catalog' }}" class="text-xs font-bold text-[#ed7100] hover:underline">
                            View all in {{ $primaryCat->name }} &rarr;
                        </a>
                    @endif
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 2xl:grid-cols-7 gap-4">
                    @foreach ($relatedProducts as $rel)
                        @include('partials.product-card', ['product' => $rel])
                    @endforeach
                </div>
            </div>
        @endif

    </div>
</div>
@endsection

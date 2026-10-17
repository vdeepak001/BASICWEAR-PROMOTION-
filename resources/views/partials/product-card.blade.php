@php
    $badgeType = $badge ?? ($product->is_new ? 'new' : ($product->is_hot ? 'hot' : ($product->is_eco ? 'eco' : null)));
    $imageUrl = $product->primary_image_url;
@endphp

<a
    href="{{ route('product.detail', $product->slug) }}"
    class="group block bg-white rounded-xl border border-gray-200/90 hover:border-orange-500/60 shadow-2xs hover:shadow-xl transition-all duration-300 hover:-translate-y-1 overflow-hidden"
>
    <!-- Square Product Image Container -->
    <div class="relative aspect-square w-full bg-linear-to-b from-gray-50 to-gray-100/60 p-3 overflow-hidden flex items-center justify-center">
        <!-- Floating Badges (Sinovation style) -->
        <div class="absolute top-2.5 left-2.5 z-10 flex flex-col gap-1 items-start">
            @if ($badgeType === 'new')
                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-extrabold bg-red-600 text-white uppercase tracking-wider shadow-xs">
                    NEW
                </span>
            @elseif ($badgeType === 'hot')
                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-extrabold bg-amber-500 text-white uppercase tracking-wider shadow-xs">
                    <svg class="size-2.5 fill-current" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M12.395 2.553a1 1 0 00-1.45-.385c-.345.23-.614.558-.822.88-.316.492-.533 1.035-.688 1.587a15.748 15.748 0 00-.472 2.656 7.607 7.607 0 00-.776-.32 1 1 0 00-1.127.306c-.307.41-.572.848-.79 1.309a10.875 10.875 0 00-.913 2.658c-.12.64-.176 1.28-.176 1.916 0 .524.04 1.042.12 1.551a8.04 8.04 0 00.413 1.512c.382 1.002.97 1.895 1.734 2.624a8.04 8.04 0 005.656 2.348c1.077 0 2.12-.211 3.08-.62a8.04 8.04 0 002.502-1.688 8.04 8.04 0 001.688-2.502c.409-.96.62-2.003.62-3.08 0-1.528-.42-2.99-1.19-4.24a10.74 10.74 0 00-2.454-2.673c-.636-.484-1.32-.888-2.036-1.206a14.774 14.774 0 00-1.442-.544 1 1 0 00-1.076.326c-.23.31-.418.647-.56 1.004-.21.524-.316 1.077-.316 1.637 0 .393.056.78.167 1.151.108.361.264.704.464 1.02a1 1 0 001.65-.95 6.04 6.04 0 01-.22-.72 4.04 4.04 0 01-.06-.5 3.04 3.04 0 01.12-.76c.09-.28.23-.53.4-.75.76.35 1.48.78 2.15 1.28.93.7 1.73 1.55 2.36 2.51.62.96.95 2.05.95 3.2 0 .84-.16 1.65-.48 2.39a6.04 6.04 0 01-1.28 1.92 6.04 6.04 0 01-1.92 1.28 6.04 6.04 0 01-2.39.48 6.04 6.04 0 01-4.25-1.76 6.04 6.04 0 01-1.3-1.97 6.04 6.04 0 01-.31-1.15 6.04 6.04 0 01-.09-1.17c0-.52.05-1.03.14-1.54.16-.9.47-1.77.92-2.58.42-.77.94-1.48 1.56-2.12.18-.18.36-.36.56-.53.22.42.48.81.78 1.17a1 1 0 001.67-.97 13.75 13.75 0 01.38-2.2c.13-.48.3-.95.5-1.4.15-.33.32-.65.51-.95.22-.35.47-.68.74-.98z" clip-rule="evenodd" />
                    </svg>
                    HOT
                </span>
            @endif

            @if ($product->is_eco)
                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-600 text-white uppercase tracking-wider shadow-xs">
                    <svg class="size-2.5 fill-current" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.25 2A2.25 2.25 0 002 4.25v2c0 .245.04.48.114.7A10.01 10.01 0 0010 18a10.01 10.01 0 007.886-11.05A2.25 2.25 0 0018 6.25v-2A2.25 2.25 0 0015.75 2h-11.5zM3.5 4.25a.75.75 0 01.75-.75h11.5a.75.75 0 01.75.75v1.836a8.51 8.51 0 01-6.19 8.211A8.51 8.51 0 013.5 6.086V4.25z" clip-rule="evenodd" />
                    </svg>
                    ECO
                </span>
            @endif
        </div>

        <!-- Product Code Tag -->
        <span class="absolute top-2.5 right-2.5 z-10 px-1.5 py-0.5 rounded bg-gray-900/80 backdrop-blur-xs text-white text-[10px] font-mono font-bold tracking-tight shadow-xs">
            #{{ $product->product_code }}
        </span>

        <!-- Product Image -->
        @if ($imageUrl)
            <img
                src="{{ $imageUrl }}"
                alt="{{ $product->name }}"
                loading="lazy"
                class="size-full object-contain object-center transform group-hover:scale-108 transition-transform duration-500"
            />
        @else
            <div class="size-full flex flex-col items-center justify-center text-gray-300 group-hover:text-orange-400 transition-colors">
                <svg class="size-12 stroke-[1.2]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                </svg>
                <span class="text-[10px] font-semibold text-gray-400 mt-1">Item {{ $product->product_code }}</span>
            </div>
        @endif
    </div>

    <!-- Product Title Only (Sinovation exact style) -->
    <div class="p-3 bg-white">
        <h4 class="text-xs sm:text-sm font-semibold text-gray-900 group-hover:text-orange-600 transition-colors line-clamp-2 leading-snug" title="{{ $product->name }}">
            {{ $product->name }}
        </h4>
        <span class="text-[10px] text-gray-400 font-mono mt-0.5 block">
            Item #{{ $product->product_code }}
        </span>
    </div>
</a>

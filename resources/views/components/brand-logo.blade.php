@props([
    'size' => 'default', // 'small', 'default', 'large'
    'showTagline' => true,
    'dark' => false,
])

@php
    $iconSize = match($size) {
        'small' => 'size-8',
        'large' => 'size-12',
        default => 'size-10',
    };

    $textSize = match($size) {
        'small' => 'text-lg',
        'large' => 'text-2xl',
        default => 'text-xl sm:text-[22px]',
    };

    $taglineSize = match($size) {
        'small' => 'text-[7.5px]',
        'large' => 'text-[10px]',
        default => 'text-[8.5px]',
    };
@endphp

<div {{ $attributes->merge(['class' => 'flex items-center gap-2.5 shrink-0 group select-none notranslate']) }}>
    <!-- Modern Premium Monogram Emblem -->
    <div class="relative {{ $iconSize }} shrink-0 transform group-hover:scale-105 transition-all duration-300">
        <svg class="size-full drop-shadow-md" viewBox="0 0 44 44" fill="none" xmlns="http://www.w3.org/2000/svg">
            <defs>
                <!-- Outer Badge Vibrant Sunset Gradient -->
                <linearGradient id="bwBadgeGrad" x1="0" y1="0" x2="44" y2="44" gradientUnits="userSpaceOnUse">
                    <stop offset="0%" stop-color="#FF8A00"/>
                    <stop offset="55%" stop-color="#ED7100"/>
                    <stop offset="100%" stop-color="#C2410C"/>
                </linearGradient>
                <!-- Top Edge Glass Sheen -->
                <linearGradient id="bwGloss" x1="0" y1="0" x2="0" y2="20" gradientUnits="userSpaceOnUse">
                    <stop offset="0%" stop-color="#FFFFFF" stop-opacity="0.35"/>
                    <stop offset="100%" stop-color="#FFFFFF" stop-opacity="0"/>
                </linearGradient>
                <!-- Upper Fold Silk Shading -->
                <linearGradient id="bwUpperFold" x1="14" y1="10" x2="30" y2="22" gradientUnits="userSpaceOnUse">
                    <stop offset="0%" stop-color="#FFFFFF"/>
                    <stop offset="100%" stop-color="#FED7AA"/>
                </linearGradient>
                <!-- Soft Glow Shadow -->
                <filter id="bwGlow" x="-20%" y="-20%" width="140%" height="140%">
                    <feDropShadow dx="0" dy="2" stdDeviation="2" flood-color="#ED7100" flood-opacity="0.45"/>
                </filter>
            </defs>

            <!-- Outer Rounded Squircle Base with Glow -->
            <rect x="2" y="2" width="40" height="40" rx="11" fill="url(#bwBadgeGrad)" filter="url(#bwGlow)"/>
            <!-- Top Sheen Gloss Overlay -->
            <rect x="2" y="2" width="40" height="20" rx="11" fill="url(#bwGloss)"/>
            <!-- Inner Crisp Border -->
            <rect x="2.5" y="2.5" width="39" height="39" rx="10.5" stroke="white" stroke-opacity="0.3" stroke-width="1"/>

            <!-- Interlocking "B" Monogram (Apparel Ribbon + Gift Bow Geometry) -->
            <!-- Vertical Spine / Stem -->
            <path d="M12.5 12C12.5 10.9 13.4 10 14.5 10H18C19.1 10 20 10.9 20 12V32C20 33.1 19.1 34 18 34H14.5C13.4 34 12.5 33.1 12.5 32V12Z" fill="white"/>

            <!-- Upper Loop (Stylized Shirt Collar Fold) -->
            <path d="M18 10H23.5C27.64 10 31 12.69 31 16C31 19.31 27.64 22 23.5 22H18V10Z" fill="url(#bwUpperFold)"/>
            <path d="M18 13.5H23C25.21 13.5 27 14.62 27 16C27 17.38 25.21 18.5 23 18.5H18V13.5Z" fill="url(#bwBadgeGrad)"/>

            <!-- Lower Loop (Dynamic Apparel / Lanyard Ribbon Curve) -->
            <path d="M18 20.5H24.5C28.92 20.5 32.5 23.41 32.5 27C32.5 30.59 28.92 33.5 24.5 33.5H18V20.5Z" fill="white"/>
            <path d="M18 24H24C26.21 24 28 25.34 28 27C28 28.66 26.21 30 24 30H18V24Z" fill="url(#bwBadgeGrad)"/>

            <!-- Premium Diamond Sparkle on Top Right -->
            <path d="M33.5 6.5L34.3 8.7L36.5 9.5L34.3 10.3L33.5 12.5L32.7 10.3L30.5 9.5L32.7 8.7L33.5 6.5Z" fill="#FFF7ED"/>
        </svg>
    </div>

    <!-- Brand Typography & Tagline -->
    <div class="flex flex-col">
        <div class="flex items-center tracking-tight leading-none">
            <span class="{{ $textSize }} font-black tracking-tight {{ $dark ? 'text-white' : 'text-gray-900' }} group-hover:text-black transition-colors">
                BASICWEAR
            </span>
            <span class="{{ $textSize }} font-black bg-gradient-to-r from-[#ed7100] via-[#ea580c] to-[#f97316] bg-clip-text text-transparent ml-1">
                PROMO
            </span>
        </div>
        @if ($showTagline)
            <span class="block {{ $taglineSize }} font-extrabold tracking-[0.2em] {{ $dark ? 'text-gray-400' : 'text-gray-400' }} group-hover:text-[#ed7100] transition-colors uppercase mt-0.5 whitespace-nowrap">
                Custom Merchandise &bull; Direct Factory
            </span>
        @endif
    </div>
</div>


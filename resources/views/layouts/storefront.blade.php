<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? (isset($product) ? $product->name . ' #' . $product->product_code . ' - ' : '') }}Basicwear Promotions - Promotional Products &amp; Corporate Gifts</title>
    <meta name="description" content="{{ $description ?? 'Direct factory supplier for promotional merchandise, corporate gifts, lanyards, drinkware, and eco-friendly products.' }}">

    <link rel="icon" type="image/png" href="/favicon.ico">

    <!-- Tailwind CSS (CDN for guaranteed instant styling across all environments) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        sinovation: {
                            DEFAULT: '#ed7100',
                            dark: '#c2410c',
                            light: '#fff7ed',
                        }
                    }
                }
            }
        }
    </script>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body {
            font-family: Arial, "Helvetica Neue", Helvetica, sans-serif !important;
            background-color: #f3f4f6;
            color: #111827;
        }
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        [x-cloak] {
            display: none !important;
        }
        /* Clean Google Translate Overrides & UI Polish */
        .goog-te-banner-frame,
        .goog-te-banner-frame.skiptranslate,
        iframe.goog-te-banner-frame,
        .VIpgJd-ZVi9od-ORHb-OEVmcd,
        .VIpgJd-ZVi9od-ORHb-OEVmcd.skiptranslate,
        iframe.VIpgJd-ZVi9od-ORHb-OEVmcd,
        iframe.skiptranslate,
        iframe[id*=":1.container"],
        iframe[id*=":2.container"],
        iframe[id*="container"],
        iframe[class*="goog-te-banner"],
        iframe[class*="VIpgJd"] {
            display: none !important;
            visibility: hidden !important;
            height: 0 !important;
            width: 0 !important;
            opacity: 0 !important;
            pointer-events: none !important;
            position: absolute !important;
            top: -9999px !important;
            left: -9999px !important;
            border: 0 !important;
        }
        html {
            top: 0px !important;
        }
        body {
            top: 0px !important;
            position: static !important;
            margin-top: 0px !important;
        }
        #google_translate_element,
        .goog-te-gadget,
        #goog-gt-tt,
        .goog-te-balloon-frame,
        .VIpgJd-ZVi9od-aZ2wEe-wOHMyf {
            display: none !important;
            visibility: hidden !important;
        }
        .goog-text-highlight {
            background-color: transparent !important;
            box-shadow: none !important;
        }
        .notranslate {
            translate: no;
        }
    </style>
    @stack('styles')
</head>
<body class="min-h-full flex flex-col antialiased" x-data="storefrontApp()">

    <!-- MAIN HEADER WITH CATEGORIES ON NAV BAR (Sinovation exact layout) -->
    <header class="w-full bg-white shadow-xs sticky top-0 z-40 border-b border-gray-200">
        <!-- Row 1: Brand & Top Category Bar (Full Width) -->
        <div class="w-full px-4 sm:px-6 lg:px-8 xl:px-10 pt-3 pb-2">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-3">
                <!-- Logo -->
                <div class="flex items-center justify-between">
                    <a href="{{ route('home') }}" class="inline-flex items-center shrink-0">
                        <x-brand-logo />
                    </a>

                    <!-- Mobile Menu & Language Toggle Buttons -->
                    <div class="flex items-center gap-2 lg:hidden notranslate">
                        <!-- Quick Mobile Language Switch Button -->
                        <button
                            type="button"
                            @click="setLanguage(currentLang === 'en' ? 'de' : 'en')"
                            class="inline-flex items-center gap-1 px-2 py-1 rounded border border-gray-300 bg-white hover:bg-gray-50 text-gray-800 text-[11px] font-bold shadow-2xs transition-colors cursor-pointer"
                            :title="currentLang === 'en' ? 'Translate to German / Auf Deutsch' : 'Translate to English / Auf Englisch'"
                            aria-label="Toggle language"
                        >
                            <span x-show="currentLang === 'en'" class="flex items-center gap-1">
                                <svg class="size-3.5 shrink-0 rounded-xs shadow-2xs" viewBox="0 0 640 480">
                                    <path fill="#012169" d="M0 0h640v480H0z"/>
                                    <path fill="#FFF" d="m75 0 244 181L562 0h78v62L400 240l240 178v62h-78L320 301 76 480H0v-62l240-178L0 62V0h75z"/>
                                    <path fill="#C8102E" d="m424 288 216 153v39h-24L392 312l32-24zM640 39 424 192l-32-24L592 0h48v39zM192 192 0 39V0h48l200 144-56 48zm0 96L0 441v39h48l200-144-56-48z"/>
                                    <path fill="#FFF" d="M240 0h160v480H240zM0 160h640v160H0z"/>
                                    <path fill="#C8102E" d="M267 0h106v480H267zM0 187h640v106H0z"/>
                                </svg>
                                <span>EN</span>
                            </span>
                            <span x-show="currentLang === 'de'" class="flex items-center gap-1" x-cloak>
                                <svg class="size-3.5 shrink-0 rounded-xs shadow-2xs" viewBox="0 0 640 480">
                                    <path fill="#000" d="M0 0h640v160H0z"/>
                                    <path fill="#D00" d="M0 160h640v160H0z"/>
                                    <path fill="#FFCE00" d="M0 320h640v160H0z"/>
                                </svg>
                                <span>DE</span>
                            </span>
                        </button>

                        <!-- Mobile Menu Toggle Button -->
                        <button
                            type="button"
                            @click="mobileNavOpen = !mobileNavOpen"
                            class="p-2 rounded-lg text-gray-700 hover:bg-gray-100 focus:outline-none"
                            aria-label="Toggle navigation menu"
                        >
                            <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Desktop Category Nav Bar (Horizontal list on navbar like Sinovation) -->
                @if (isset($categories) && $categories->isNotEmpty())
                    <nav class="hidden lg:flex items-center gap-1 overflow-x-auto no-scrollbar py-1">
                        @foreach ($categories as $cat)
                            @php
                                $isEco = strtolower($cat->slug) === 'eco' || strtolower($cat->name) === 'eco';
                                $isActive = (isset($currentCategory) && ($currentCategory === $cat->slug || $currentCategory == $cat->id))
                                    || (isset($product) && $product->categories->contains('id', $cat->id));
                            @endphp
                            <a
                                href="{{ route('home', ['category' => $cat->slug]) }}#catalog"
                                class="px-2.5 py-1.5 rounded-md text-xs font-semibold whitespace-nowrap transition-all flex items-center gap-1 {{ $isActive ? 'text-[#ed7100] font-bold bg-orange-50' : ($isEco ? 'text-emerald-700 bg-emerald-50 hover:bg-emerald-100' : 'text-gray-700 hover:bg-gray-100 hover:text-[#ed7100]') }}"
                            >
                                @if ($isEco)
                                    <svg class="size-3.5 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4.25 2A2.25 2.25 0 002 4.25v2c0 .245.04.48.114.7A10.01 10.01 0 0010 18a10.01 10.01 0 007.886-11.05A2.25 2.25 0 0018 6.25v-2A2.25 2.25 0 0015.75 2h-11.5zM3.5 4.25a.75.75 0 01.75-.75h11.5a.75.75 0 01.75.75v1.836a8.51 8.51 0 01-6.19 8.211A8.51 8.51 0 013.5 6.086V4.25z" clip-rule="evenodd" />
                                    </svg>
                                @endif
                                <span>{{ $cat->name }}</span>
                            </a>
                        @endforeach
                    </nav>
                @endif
            </div>
        </div>

        <!-- Row 2: Search Input and Filter Pills (Full Width) -->
        <div class="border-t border-gray-100 bg-gray-50/70 py-2 px-4 sm:px-6 lg:px-8 xl:px-10">
            <div class="w-full flex flex-col sm:flex-row items-center justify-between gap-3">
                <!-- Search bar -->
                <form action="{{ route('home') }}" method="GET" class="w-full sm:max-w-md relative">
                    @if (request('category'))
                        <input type="hidden" name="category" value="{{ request('category') }}">
                    @endif
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                            <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                            </svg>
                        </span>
                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Search for something..."
                            class="w-full pl-9 pr-12 py-1.5 bg-white rounded-md border border-gray-300 text-xs text-gray-900 placeholder-gray-400 focus:ring-2 focus:ring-[#ed7100] focus:border-[#ed7100]"
                        />
                        <button type="submit" class="absolute inset-y-1 right-1 px-2.5 bg-gray-900 hover:bg-[#ed7100] text-white rounded text-[11px] font-bold transition-colors cursor-pointer">
                            Search
                        </button>
                    </div>
                </form>

                <!-- Filter Pills: [ All ] [ New ] [ Hot ] + Instant Quote & Admin -->
                <div class="flex items-center gap-1.5 self-start sm:self-auto">
                    <a href="{{ route('home') }}" class="px-3 py-1 rounded {{ !request('filter') || request('filter') === 'all' ? 'bg-[#ed7100] text-white' : 'bg-gray-900 hover:bg-gray-800 text-white' }} text-xs font-bold transition-colors">
                        All
                    </a>
                    <a href="{{ route('home', ['filter' => 'new']) }}" class="px-3 py-1 rounded {{ request('filter') === 'new' ? 'bg-red-600 text-white' : 'bg-gray-900 hover:bg-red-600 text-white' }} text-xs font-bold transition-colors">
                        New
                    </a>
                    <a href="{{ route('home', ['filter' => 'hot']) }}" class="px-3 py-1 rounded {{ request('filter') === 'hot' ? 'bg-amber-600 text-white' : 'bg-gray-900 hover:bg-amber-600 text-white' }} text-xs font-bold transition-colors">
                        Hot
                    </a>
                    <button
                        type="button"
                        @click="openQuoteModal(null)"
                        class="ml-2 inline-flex items-center gap-1 px-3 py-1 rounded bg-[#ed7100] hover:bg-orange-600 text-white text-xs font-bold shadow-xs transition-colors cursor-pointer"
                    >
                        <span>Request Quote</span>
                    </button>
                    @auth
                        <a href="{{ route('dashboard') }}" class="ml-1 inline-flex items-center px-2.5 py-1 rounded text-xs font-semibold text-gray-600 hover:text-[#ed7100] hover:bg-white transition-colors">
                            Admin
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="ml-1 inline-flex items-center px-2.5 py-1 text-xs font-medium text-gray-500 hover:text-gray-900 transition-colors">
                            Login
                        </a>
                    @endauth

                    <div class="h-4 w-px bg-gray-300 mx-1 hidden sm:block"></div>

                    <!-- DESKTOP LANGUAGE TRANSLATE DROPDOWN BUTTON -->
                    <div class="relative notranslate" @click.outside="langDropdownOpen = false">
                        <button
                            type="button"
                            @click="langDropdownOpen = !langDropdownOpen"
                            class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded border border-gray-300 bg-white hover:bg-gray-50 text-gray-800 text-xs font-semibold transition-all shadow-2xs hover:border-gray-400 cursor-pointer"
                            title="Translate Language / Sprache wechseln"
                            aria-haspopup="true"
                            :aria-expanded="langDropdownOpen"
                        >
                            <!-- Flag & Label for English -->
                            <span x-show="currentLang === 'en'" class="flex items-center gap-1.5">
                                <svg class="size-4 shrink-0 rounded-xs shadow-2xs" viewBox="0 0 640 480">
                                    <path fill="#012169" d="M0 0h640v480H0z"/>
                                    <path fill="#FFF" d="m75 0 244 181L562 0h78v62L400 240l240 178v62h-78L320 301 76 480H0v-62l240-178L0 62V0h75z"/>
                                    <path fill="#C8102E" d="m424 288 216 153v39h-24L392 312l32-24zM640 39 424 192l-32-24L592 0h48v39zM192 192 0 39V0h48l200 144-56 48zm0 96L0 441v39h48l200-144-56-48z"/>
                                    <path fill="#FFF" d="M240 0h160v480H240zM0 160h640v160H0z"/>
                                    <path fill="#C8102E" d="M267 0h106v480H267zM0 187h640v106H0z"/>
                                </svg>
                                <span class="font-bold">English</span>
                            </span>
                            <!-- Flag & Label for German -->
                            <span x-show="currentLang === 'de'" class="flex items-center gap-1.5" x-cloak>
                                <svg class="size-4 shrink-0 rounded-xs shadow-2xs" viewBox="0 0 640 480">
                                    <path fill="#000" d="M0 0h640v160H0z"/>
                                    <path fill="#D00" d="M0 160h640v160H0z"/>
                                    <path fill="#FFCE00" d="M0 320h640v160H0z"/>
                                </svg>
                                <span class="font-bold">Deutsch</span>
                            </span>
                            <svg class="size-3 text-gray-500 transition-transform duration-200" :class="{ 'rotate-180': langDropdownOpen }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <!-- Dropdown Menu -->
                        <div
                            x-show="langDropdownOpen"
                            x-transition:enter="transition ease-out duration-150"
                            x-transition:enter-start="transform opacity-0 scale-95"
                            x-transition:enter-end="transform opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-100"
                            x-transition:leave-start="transform opacity-100 scale-100"
                            x-transition:leave-end="transform opacity-0 scale-95"
                            class="absolute right-0 mt-1.5 w-44 bg-white rounded-lg shadow-lg border border-gray-200 py-1 z-50 text-xs"
                            x-cloak
                        >
                            <div class="px-3 py-1.5 text-[10px] font-bold text-gray-400 uppercase tracking-wider border-b border-gray-100">
                                Translate / Sprache
                            </div>
                            <button
                                type="button"
                                @click="setLanguage('en')"
                                class="w-full flex items-center justify-between px-3 py-2 text-left hover:bg-orange-50 hover:text-[#ed7100] transition-colors cursor-pointer"
                                :class="{ 'bg-orange-50/70 text-[#ed7100] font-bold': currentLang === 'en', 'text-gray-700': currentLang !== 'en' }"
                            >
                                <span class="flex items-center gap-2">
                                    <svg class="size-4 shrink-0 rounded-xs shadow-2xs" viewBox="0 0 640 480">
                                        <path fill="#012169" d="M0 0h640v480H0z"/>
                                        <path fill="#FFF" d="m75 0 244 181L562 0h78v62L400 240l240 178v62h-78L320 301 76 480H0v-62l240-178L0 62V0h75z"/>
                                        <path fill="#C8102E" d="m424 288 216 153v39h-24L392 312l32-24zM640 39 424 192l-32-24L592 0h48v39zM192 192 0 39V0h48l200 144-56 48zm0 96L0 441v39h48l200-144-56-48z"/>
                                        <path fill="#FFF" d="M240 0h160v480H240zM0 160h640v160H0z"/>
                                        <path fill="#C8102E" d="M267 0h106v480H267zM0 187h640v106H0z"/>
                                    </svg>
                                    <span>English</span>
                                </span>
                                <svg x-show="currentLang === 'en'" class="size-4 text-[#ed7100]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                                </svg>
                            </button>
                            <button
                                type="button"
                                @click="setLanguage('de')"
                                class="w-full flex items-center justify-between px-3 py-2 text-left hover:bg-orange-50 hover:text-[#ed7100] transition-colors cursor-pointer"
                                :class="{ 'bg-orange-50/70 text-[#ed7100] font-bold': currentLang === 'de', 'text-gray-700': currentLang !== 'de' }"
                            >
                                <span class="flex items-center gap-2">
                                    <svg class="size-4 shrink-0 rounded-xs shadow-2xs" viewBox="0 0 640 480">
                                        <path fill="#000" d="M0 0h640v160H0z"/>
                                        <path fill="#D00" d="M0 160h640v160H0z"/>
                                        <path fill="#FFCE00" d="M0 320h640v160H0z"/>
                                    </svg>
                                    <span>Deutsch (German)</span>
                                </span>
                                <svg x-show="currentLang === 'de'" class="size-4 text-[#ed7100]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mobile Drawer Menu -->
        <div x-show="mobileNavOpen" x-collapse class="lg:hidden border-t border-gray-200 bg-white px-4 py-3" x-cloak>
            <!-- Language Selector in Mobile Drawer -->
            <div class="mb-3 pb-3 border-b border-gray-100 notranslate">
                <div class="text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-2">Translate Language / Sprache</div>
                <div class="grid grid-cols-2 gap-2">
                    <button
                        type="button"
                        @click="setLanguage('en'); mobileNavOpen = false"
                        class="px-3 py-2 rounded-lg text-xs font-semibold flex items-center justify-center gap-2 border transition-all cursor-pointer"
                        :class="currentLang === 'en' ? 'border-[#ed7100] bg-orange-50 text-[#ed7100] font-bold shadow-2xs' : 'border-gray-200 text-gray-700 hover:bg-gray-50'"
                    >
                        <svg class="size-4 shrink-0 rounded-xs shadow-2xs" viewBox="0 0 640 480">
                            <path fill="#012169" d="M0 0h640v480H0z"/>
                            <path fill="#FFF" d="m75 0 244 181L562 0h78v62L400 240l240 178v62h-78L320 301 76 480H0v-62l240-178L0 62V0h75z"/>
                            <path fill="#C8102E" d="m424 288 216 153v39h-24L392 312l32-24zM640 39 424 192l-32-24L592 0h48v39zM192 192 0 39V0h48l200 144-56 48zm0 96L0 441v39h48l200-144-56-48z"/>
                            <path fill="#FFF" d="M240 0h160v480H240zM0 160h640v160H0z"/>
                            <path fill="#C8102E" d="M267 0h106v480H267zM0 187h640v106H0z"/>
                        </svg>
                        <span>English</span>
                    </button>
                    <button
                        type="button"
                        @click="setLanguage('de'); mobileNavOpen = false"
                        class="px-3 py-2 rounded-lg text-xs font-semibold flex items-center justify-center gap-2 border transition-all cursor-pointer"
                        :class="currentLang === 'de' ? 'border-[#ed7100] bg-orange-50 text-[#ed7100] font-bold shadow-2xs' : 'border-gray-200 text-gray-700 hover:bg-gray-50'"
                    >
                        <svg class="size-4 shrink-0 rounded-xs shadow-2xs" viewBox="0 0 640 480">
                            <path fill="#000" d="M0 0h640v160H0z"/>
                            <path fill="#D00" d="M0 160h640v160H0z"/>
                            <path fill="#FFCE00" d="M0 320h640v160H0z"/>
                        </svg>
                        <span>Deutsch</span>
                    </button>
                </div>
            </div>
            <div class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Categories</div>
            @if (isset($categories))
                <div class="grid grid-cols-2 gap-2">
                    @foreach ($categories as $cat)
                        <a
                            href="{{ route('home', ['category' => $cat->slug]) }}#catalog"
                            class="px-3 py-2 rounded-lg text-xs font-medium text-gray-700 hover:bg-orange-50 hover:text-[#ed7100] flex items-center gap-2 border border-gray-100"
                        >
                            <span>{{ $cat->name }}</span>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </header>

    <!-- Success Notification after submitting a quote -->
    @if (session('quote_success'))
        <div id="quote-success" class="w-full px-4 sm:px-6 lg:px-8 xl:px-10 mt-4">
            <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-4 shadow-sm flex items-start justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="rounded-full bg-emerald-500 text-white p-2 shrink-0">
                        <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-emerald-900">Thank you, {{ session('customer_name') }}! Quote Request Received</h4>
                        <p class="text-xs text-emerald-700 mt-0.5">
                            Your quote reference number is <strong class="font-mono bg-emerald-100 px-1.5 py-0.5 rounded text-emerald-900">{{ session('quote_number') }}</strong>. Our promotional branding team will calculate tiered air/sea freight options and email you within 2-4 business hours.
                        </p>
                    </div>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700">
                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    @endif

    <!-- MAIN PAGE CONTENT (Yielded or Slot) -->
    <main class="flex-1 w-full">
        @yield('content')
        {{ $slot ?? '' }}
    </main>

    <!-- FOOTER (Matching Sinovation copyright) -->
    <footer class="bg-gray-900 text-gray-400 py-8 text-xs text-center border-t border-gray-800 mt-12 w-full">
        <div class="w-full px-4 sm:px-6 lg:px-8 xl:px-10 space-y-3 flex flex-col items-center">
            <a href="{{ route('home') }}" class="inline-flex items-center">
                <x-brand-logo size="small" :dark="true" />
            </a>
            <p>&copy; 2026 Basicwear Promotions. All Right Reserved.</p>
            <div class="flex items-center justify-center gap-4 text-[11px] text-gray-500">
                <a href="{{ route('home') }}" class="hover:text-gray-300">Home</a>
                <span>•</span>
                <a href="{{ route('home', ['filter' => 'new']) }}" class="hover:text-gray-300">New Arrivals</a>
                <span>•</span>
                <a href="{{ route('home', ['filter' => 'hot']) }}" class="hover:text-gray-300">Hot Sellers</a>
                <span>•</span>
                <a href="{{ route('home', ['filter' => 'eco']) }}" class="hover:text-gray-300">Eco Friendly</a>
                <span>•</span>
                <a href="{{ route('dashboard') }}" class="hover:text-gray-300">Admin Area</a>
            </div>
        </div>
    </footer>

    <!-- UNIVERSAL B2B QUOTE REQUEST MODAL -->
    <div
        x-show="quoteModalOpen"
        x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs overflow-y-auto"
        @keydown.escape.window="closeQuoteModal()"
    >
        <div
            x-show="quoteModalOpen"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="w-full max-w-xl bg-white rounded-2xl shadow-2xl border border-gray-200 overflow-hidden my-8"
            @click.outside="closeQuoteModal()"
        >
            <!-- Modal Header -->
            <div class="bg-gradient-to-r from-gray-900 to-gray-800 text-white p-5 flex items-center justify-between">
                <div>
                    <h3 class="text-base font-bold">Request Official Wholesale Quote</h3>
                    <p class="text-xs text-gray-300 mt-0.5">Factory direct quotation with tiered air &amp; sea freight</p>
                </div>
                <button type="button" @click="closeQuoteModal()" class="text-gray-400 hover:text-white p-1">
                    <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Modal Form -->
            <form action="{{ route('quotes.submit') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
                @csrf
                <input type="hidden" name="product_id" :value="quoteProduct?.id || '{{ isset($product) ? $product->id : '' }}'">

                <!-- Selected Product Preview -->
                <template x-if="quoteProduct || '{{ isset($product) ? $product->id : '' }}'">
                    <div class="rounded-xl bg-orange-50/70 border border-orange-200/80 p-3 flex items-center justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <div class="size-12 rounded-lg bg-white border border-orange-200 p-1 shrink-0 flex items-center justify-center">
                                <img
                                    :src="quoteProduct?.image || '{{ isset($product) ? $product->primary_image_url : '' }}'"
                                    :alt="quoteProduct?.name || '{{ isset($product) ? $product->name : '' }}'"
                                    class="size-full object-contain"
                                />
                            </div>
                            <div>
                                <span class="text-xs font-mono font-bold text-orange-700" x-text="quoteProduct?.code ? ('Item #' + quoteProduct.code) : 'Item #{{ isset($product) ? $product->product_code : '' }}'"></span>
                                <h4 class="text-xs font-bold text-gray-900" x-text="quoteProduct?.name || '{{ isset($product) ? addslashes($product->name) : '' }}'"></h4>
                                <span class="text-[10px] text-gray-500" x-text="'MOQ: ' + (quoteProduct?.moq || '{{ isset($product) ? ($product->moq ?? 100) : 100 }}') + ' pcs'"></span>
                            </div>
                        </div>
                    </div>
                </template>

                <!-- Quantity & Needed Date -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Estimated Quantity *</label>
                        <input
                            type="number"
                            name="quantity"
                            required
                            min="1"
                            :value="quoteProduct?.moq || '{{ isset($product) ? ($product->moq ?? 250) : 250 }}'"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-xs focus:ring-2 focus:ring-[#ed7100] focus:border-[#ed7100]"
                        />
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Required In-Hand Date</label>
                        <input
                            type="date"
                            name="needed_by"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-xs focus:ring-2 focus:ring-[#ed7100] focus:border-[#ed7100]"
                        />
                    </div>
                </div>

                <!-- Contact Info -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Your Full Name *</label>
                        <input
                            type="text"
                            name="name"
                            required
                            value="{{ auth()->user()?->name }}"
                            placeholder="John Smith"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-xs focus:ring-2 focus:ring-[#ed7100] focus:border-[#ed7100]"
                        />
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Business Email *</label>
                        <input
                            type="email"
                            name="email"
                            required
                            value="{{ auth()->user()?->email }}"
                            placeholder="john@company.com"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-xs focus:ring-2 focus:ring-[#ed7100] focus:border-[#ed7100]"
                        />
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Company / Organization</label>
                        <input
                            type="text"
                            name="company"
                            placeholder="Acme Corp"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-xs focus:ring-2 focus:ring-[#ed7100] focus:border-[#ed7100]"
                        />
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Phone Number</label>
                        <input
                            type="text"
                            name="phone"
                            placeholder="+61 412 345 678"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-xs focus:ring-2 focus:ring-[#ed7100] focus:border-[#ed7100]"
                        />
                    </div>
                </div>

                <!-- Destination -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Delivery Destination (Suburb / Postcode / Country)</label>
                    <input
                        type="text"
                        name="destination"
                        placeholder="e.g. Sydney NSW 2000, Australia"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-xs focus:ring-2 focus:ring-[#ed7100] focus:border-[#ed7100]"
                    />
                </div>

                <!-- Logo / Artwork Upload -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Upload Logo / Artwork Mockup (Optional)</label>
                    <input
                        type="file"
                        name="artwork"
                        accept=".pdf,.ai,.eps,.png,.jpg,.jpeg"
                        class="block w-full text-xs text-gray-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-gray-900 file:text-white hover:file:bg-[#ed7100] file:cursor-pointer"
                    />
                    <p class="text-[10px] text-gray-400 mt-1">Vector PDF, AI, EPS, or high-res PNG/JPG up to 10MB</p>
                </div>

                <!-- Notes / Specifications -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Branding Details &amp; Customization Notes</label>
                    <textarea
                        name="message"
                        rows="3"
                        placeholder="Specify logo position, colors to print, packaging requests, or freight preference..."
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-xs focus:ring-2 focus:ring-[#ed7100] focus:border-[#ed7100]"
                    ></textarea>
                </div>

                <!-- Footer Buttons -->
                <div class="pt-2 flex items-center justify-end gap-3 border-t border-gray-200">
                    <button
                        type="button"
                        @click="closeQuoteModal()"
                        class="px-4 py-2 rounded-lg text-xs font-semibold text-gray-600 hover:bg-gray-100 cursor-pointer"
                    >
                        Cancel
                    </button>
                    <button
                        type="submit"
                        style="background-color: #ed7100;"
                        class="px-5 py-2.5 rounded-lg text-white text-xs font-bold shadow-md hover:opacity-90 transition-all cursor-pointer"
                    >
                        Submit Quote Request
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Global Storefront Alpine Logic -->
    <script>
        function storefrontApp() {
            return {
                mobileNavOpen: false,
                quoteModalOpen: false,
                quoteProduct: null,
                langDropdownOpen: false,
                currentLang: 'en',

                init() {
                    const saved = localStorage.getItem('site_lang');
                    const cookieMatch = document.cookie.match(/googtrans=\/en\/(de|en)/);
                    if (saved === 'de' || (cookieMatch && cookieMatch[1] === 'de')) {
                        this.currentLang = 'de';
                    } else {
                        this.currentLang = 'en';
                    }
                },

                setLanguage(lang) {
                    if (this.currentLang === lang) {
                        this.langDropdownOpen = false;
                        return;
                    }

                    this.currentLang = lang;
                    this.langDropdownOpen = false;

                    if (lang === 'de') {
                        localStorage.setItem('site_lang', 'de');
                        setGoogtransCookie('/en/de');

                        const combo = document.querySelector('.goog-te-combo');
                        if (combo) {
                            combo.value = 'de';
                            combo.dispatchEvent(new Event('change'));
                        } else {
                            window.location.reload();
                        }
                    } else {
                        localStorage.setItem('site_lang', 'en');
                        clearGoogtransCookie();

                        const combo = document.querySelector('.goog-te-combo');
                        if (combo) {
                            combo.value = 'en';
                            combo.dispatchEvent(new Event('change'));
                        }
                        window.location.reload();
                    }
                },

                openQuoteModal(product = null) {
                    this.quoteProduct = product;
                    this.quoteModalOpen = true;
                },

                closeQuoteModal() {
                    this.quoteModalOpen = false;
                }
            }
        }
    </script>

    <!-- Hidden Google Translate Element Container -->
    <div id="google_translate_element" style="display:none !important;" class="hidden" aria-hidden="true"></div>

    <script type="text/javascript">
        function setGoogtransCookie(val) {
            document.cookie = 'googtrans=' + val + '; path=/; max-age=' + (60 * 60 * 24 * 30);
            const host = window.location.hostname;
            if (host) {
                document.cookie = 'googtrans=' + val + '; path=/; domain=' + host + '; max-age=' + (60 * 60 * 24 * 30);
                const parts = host.split('.');
                if (parts.length > 1) {
                    document.cookie = 'googtrans=' + val + '; path=/; domain=.' + parts.slice(-2).join('.') + '; max-age=' + (60 * 60 * 24 * 30);
                }
            }
        }

        function clearGoogtransCookie() {
            const expired = '=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;';
            document.cookie = 'googtrans' + expired;
            const host = window.location.hostname;
            if (host) {
                document.cookie = 'googtrans' + expired + ' domain=' + host + ';';
                const parts = host.split('.');
                if (parts.length > 1) {
                    document.cookie = 'googtrans' + expired + ' domain=.' + parts.slice(-2).join('.') + ';';
                }
            }
            document.cookie = 'googtrans=/en/en; path=/; max-age=' + (60 * 60 * 24 * 30);
        }

        function googleTranslateElementInit() {
            new google.translate.TranslateElement({
                pageLanguage: 'en',
                includedLanguages: 'en,de',
                autoDisplay: false
            }, 'google_translate_element');

            // If user previously selected German, ensure Google combo triggers translation
            const saved = localStorage.getItem('site_lang');
            if (saved === 'de') {
                const interval = setInterval(function () {
                    const combo = document.querySelector('.goog-te-combo');
                    if (combo) {
                        clearInterval(interval);
                        if (combo.value !== 'de') {
                            combo.value = 'de';
                            combo.dispatchEvent(new Event('change'));
                        }
                    }
                }, 100);
                setTimeout(function () { clearInterval(interval); }, 4000);
            }
        }

        // Intercept and eliminate Google Translate top banner strip and body shift
        function suppressGoogleTranslateBanner() {
            if (document.body && document.body.style.top && document.body.style.top !== '0px') {
                document.body.style.setProperty('top', '0px', 'important');
            }
            if (document.documentElement && document.documentElement.style.top && document.documentElement.style.top !== '0px') {
                document.documentElement.style.setProperty('top', '0px', 'important');
            }
            const banners = document.querySelectorAll('iframe.skiptranslate, iframe[id*=":1.container"], iframe[class*="VIpgJd"], .goog-te-banner-frame, .VIpgJd-ZVi9od-ORHb-OEVmcd');
            banners.forEach(function (b) {
                b.style.setProperty('display', 'none', 'important');
                b.style.setProperty('visibility', 'hidden', 'important');
                b.style.setProperty('height', '0px', 'important');
                b.style.setProperty('width', '0px', 'important');
                b.style.setProperty('position', 'absolute', 'important');
                b.style.setProperty('top', '-9999px', 'important');
            });
        }
        window.addEventListener('load', suppressGoogleTranslateBanner);
        const gtObserver = new MutationObserver(suppressGoogleTranslateBanner);
        gtObserver.observe(document.documentElement, { attributes: true, childList: true, subtree: true });
    </script>
    <script type="text/javascript" src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
    @stack('scripts')
</body>
</html>

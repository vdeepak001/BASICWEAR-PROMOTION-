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
                    <a href="{{ route('home') }}" class="flex items-center gap-2 shrink-0 group">
                        <div class="size-9 rounded-lg bg-[#ed7100] flex items-center justify-center text-white shadow-xs group-hover:scale-105 transition-transform">
                            <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                                <path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/>
                                <path d="m3.3 7 8.7 5 8.7-5"/>
                                <path d="M12 22V12"/>
                            </svg>
                        </div>
                        <div>
                            <span class="text-xl font-extrabold tracking-tight text-gray-900 group-hover:text-[#ed7100] transition-colors">
                                BASICWEAR<span class="text-[#ed7100]">PROMO</span>
                            </span>
                        </div>
                    </a>

                    <!-- Mobile Menu Toggle Button -->
                    <button
                        type="button"
                        @click="mobileNavOpen = !mobileNavOpen"
                        class="lg:hidden p-2 rounded-lg text-gray-700 hover:bg-gray-100 focus:outline-none"
                    >
                        <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        </svg>
                    </button>
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
                </div>
            </div>
        </div>

        <!-- Mobile Drawer Menu -->
        <div x-show="mobileNavOpen" x-collapse class="lg:hidden border-t border-gray-200 bg-white px-4 py-3" x-cloak>
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
    <footer class="bg-gray-900 text-gray-400 py-6 text-xs text-center border-t border-gray-800 mt-12 w-full">
        <div class="w-full px-4 sm:px-6 lg:px-8 xl:px-10 space-y-2">
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
    @stack('scripts')
</body>
</html>

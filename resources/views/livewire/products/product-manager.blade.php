<div class="space-y-6">
    <!-- Header & Action -->
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-semibold tracking-tight text-zinc-900 dark:text-zinc-100">
                {{ __('Products') }}
            </h1>
            <p class="text-sm text-zinc-500 dark:text-zinc-400">
                {{ __('Manage your promotional catalog, codes, tier pricing, gallery, and flags.') }}
            </p>
        </div>

        <div class="flex items-center gap-3">
            <flux:button
                variant="primary"
                icon="plus"
                wire:click="openCreateModal"
                class="shadow-sm"
            >
                {{ __('New Product') }}
            </flux:button>
        </div>
    </div>

    <!-- Quick Stats Cards -->
    <div class="grid grid-cols-2 gap-4 sm:grid-cols-5">
        <div class="rounded-xl border border-zinc-200/80 bg-white p-4 shadow-sm dark:border-zinc-700/80 dark:bg-zinc-900">
            <div class="flex items-center justify-between">
                <span class="text-xs font-medium uppercase tracking-wider text-zinc-500 dark:text-zinc-400">{{ __('Total') }}</span>
                <span class="rounded-md bg-blue-50 p-1.5 text-blue-600 dark:bg-blue-950/60 dark:text-blue-400">
                    <flux:icon name="cube" class="size-4" />
                </span>
            </div>
            <div class="mt-2 text-2xl font-bold text-zinc-900 dark:text-zinc-100">{{ $stats['total'] }}</div>
        </div>

        <div class="rounded-xl border border-zinc-200/80 bg-white p-4 shadow-sm dark:border-zinc-700/80 dark:bg-zinc-900">
            <div class="flex items-center justify-between">
                <span class="text-xs font-medium uppercase tracking-wider text-zinc-500 dark:text-zinc-400">{{ __('Active') }}</span>
                <span class="rounded-md bg-emerald-50 p-1.5 text-emerald-600 dark:bg-emerald-950/60 dark:text-emerald-400">
                    <flux:icon name="check-circle" class="size-4" />
                </span>
            </div>
            <div class="mt-2 text-2xl font-bold text-emerald-600 dark:text-emerald-400">{{ $stats['active'] }}</div>
        </div>

        <div class="rounded-xl border border-zinc-200/80 bg-white p-4 shadow-sm dark:border-zinc-700/80 dark:bg-zinc-900">
            <div class="flex items-center justify-between">
                <span class="text-xs font-medium uppercase tracking-wider text-zinc-500 dark:text-zinc-400">{{ __('New') }}</span>
                <span class="rounded-md bg-rose-50 p-1.5 text-rose-600 dark:bg-rose-950/60 dark:text-rose-400">
                    <flux:icon name="sparkles" class="size-4" />
                </span>
            </div>
            <div class="mt-2 text-2xl font-bold text-rose-600 dark:text-rose-400">{{ $stats['new'] }}</div>
        </div>

        <div class="rounded-xl border border-zinc-200/80 bg-white p-4 shadow-sm dark:border-zinc-700/80 dark:bg-zinc-900">
            <div class="flex items-center justify-between">
                <span class="text-xs font-medium uppercase tracking-wider text-zinc-500 dark:text-zinc-400">{{ __('Hot') }}</span>
                <span class="rounded-md bg-amber-50 p-1.5 text-amber-600 dark:bg-amber-950/60 dark:text-amber-400">
                    <flux:icon name="fire" class="size-4" />
                </span>
            </div>
            <div class="mt-2 text-2xl font-bold text-amber-600 dark:text-amber-400">{{ $stats['hot'] }}</div>
        </div>

        <div class="rounded-xl border border-zinc-200/80 bg-white p-4 shadow-sm dark:border-zinc-700/80 dark:bg-zinc-900">
            <div class="flex items-center justify-between">
                <span class="text-xs font-medium uppercase tracking-wider text-zinc-500 dark:text-zinc-400">{{ __('Eco') }}</span>
                <span class="rounded-md bg-green-50 p-1.5 text-green-600 dark:bg-green-950/60 dark:text-green-400">
                    <flux:icon name="globe-alt" class="size-4" />
                </span>
            </div>
            <div class="mt-2 text-2xl font-bold text-green-600 dark:text-green-400">{{ $stats['eco'] }}</div>
        </div>
    </div>

    <!-- Filters & Search Toolbar -->
    <div class="rounded-xl border border-zinc-200/80 bg-white p-4 shadow-sm dark:border-zinc-700/80 dark:bg-zinc-900">
        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-5">
            <!-- Search -->
            <div class="lg:col-span-2">
                <flux:input
                    wire:model.live.debounce.300ms="search"
                    icon="magnifying-glass"
                    placeholder="{{ __('Search product code, name, material...') }}"
                    clearable
                />
            </div>

            <!-- Category Filter -->
            <div>
                <flux:select wire:model.live="categoryFilter" placeholder="{{ __('All Categories') }}">
                    <option value="">{{ __('All Categories') }}</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </flux:select>
            </div>

            <!-- Status Filter -->
            <div>
                <flux:select wire:model.live="statusFilter">
                    <option value="">{{ __('All Statuses') }}</option>
                    <option value="active">{{ __('Active Only') }}</option>
                    <option value="inactive">{{ __('Inactive Only') }}</option>
                </flux:select>
            </div>

            <!-- Flag Filter -->
            <div>
                <flux:select wire:model.live="flagFilter">
                    <option value="">{{ __('All Flags') }}</option>
                    <option value="new">{{ __('New Arrivals') }}</option>
                    <option value="hot">{{ __('Hot Sellers') }}</option>
                    <option value="eco">{{ __('Eco-Friendly') }}</option>
                </flux:select>
            </div>
        </div>
    </div>

    <!-- Products Table -->
    <div class="overflow-hidden rounded-xl border border-zinc-200/80 bg-white shadow-sm dark:border-zinc-700/80 dark:bg-zinc-900">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="border-b border-zinc-200 bg-zinc-50 text-xs font-semibold uppercase tracking-wider text-zinc-500 dark:border-zinc-700 dark:bg-zinc-800/50 dark:text-zinc-400">
                    <tr>
                        <th class="px-4 py-3.5">{{ __('Product') }}</th>
                        <th class="px-4 py-3.5 cursor-pointer select-none hover:text-zinc-900 dark:hover:text-zinc-100" wire:click="sortBy('product_code')">
                            <span class="inline-flex items-center gap-1">
                                {{ __('Code') }}
                                @if ($sortField === 'product_code')
                                    <flux:icon name="{{ $sortDirection === 'asc' ? 'chevron-up' : 'chevron-down' }}" class="size-3" />
                                @endif
                            </span>
                        </th>
                        <th class="px-4 py-3.5">{{ __('Categories') }}</th>
                        <th class="px-4 py-3.5 cursor-pointer select-none hover:text-zinc-900 dark:hover:text-zinc-100" wire:click="sortBy('moq')">
                            <span class="inline-flex items-center gap-1">
                                {{ __('MOQ & Price Range') }}
                                @if ($sortField === 'moq')
                                    <flux:icon name="{{ $sortDirection === 'asc' ? 'chevron-up' : 'chevron-down' }}" class="size-3" />
                                @endif
                            </span>
                        </th>
                        <th class="px-4 py-3.5 text-center">{{ __('Flags') }}</th>
                        <th class="px-4 py-3.5 text-center">{{ __('Status') }}</th>
                        <th class="px-4 py-3.5 text-right">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700/60">
                    @forelse ($products as $product)
                        <tr class="transition-colors hover:bg-zinc-50/70 dark:hover:bg-zinc-800/40">
                            <!-- Product Image & Title -->
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    <div class="relative size-12 shrink-0 overflow-hidden rounded-lg border border-zinc-200 bg-zinc-100 dark:border-zinc-700 dark:bg-zinc-800">
                                        @if ($product->primary_image_url)
                                            <img src="{{ $product->primary_image_url }}" alt="{{ $product->name }}" class="size-full object-cover" />
                                        @else
                                            <div class="flex size-full items-center justify-center text-zinc-400">
                                                <flux:icon name="photo" class="size-5" />
                                            </div>
                                        @endif
                                        @if ($product->images->count() > 1)
                                            <span class="absolute bottom-0 right-0 rounded-tl bg-zinc-900/80 px-1 text-[10px] font-bold text-white">
                                                {{ $product->images->count() }}
                                            </span>
                                        @endif
                                    </div>
                                    <div class="min-w-0">
                                        <div class="font-medium text-zinc-900 dark:text-zinc-100 truncate max-w-xs" title="{{ $product->name }}">
                                            {{ $product->name }}
                                        </div>
                                        <div class="text-xs text-zinc-400 dark:text-zinc-500 truncate max-w-xs">
                                            {{ $product->slug }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- Code -->
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center rounded-md bg-zinc-100 px-2 py-0.5 text-xs font-mono font-semibold text-zinc-800 dark:bg-zinc-800 dark:text-zinc-200">
                                    {{ $product->product_code }}
                                </span>
                            </td>

                            <!-- Categories -->
                            <td class="px-4 py-3">
                                <div class="flex flex-wrap gap-1 max-w-xs">
                                    @forelse ($product->categories as $c)
                                        <span class="rounded bg-zinc-100 px-1.5 py-0.5 text-[11px] font-medium text-zinc-700 dark:bg-zinc-800 dark:text-zinc-300">
                                            {{ $c->name }}
                                        </span>
                                    @empty
                                        <span class="text-xs text-zinc-400 italic">{{ __('Unassigned') }}</span>
                                    @endforelse
                                </div>
                            </td>

                            <!-- MOQ & Price -->
                            <td class="px-4 py-3">
                                <div class="text-xs text-zinc-900 dark:text-zinc-100 font-semibold">
                                    {{ $product->price_range }}
                                </div>
                                <div class="text-[11px] text-zinc-500 dark:text-zinc-400">
                                    {{ __('MOQ:') }} {{ number_format($product->moq) }} pcs
                                </div>
                            </td>

                            <!-- Flag Toggles -->
                            <td class="px-4 py-3 text-center">
                                <div class="inline-flex items-center gap-1.5">
                                    <!-- New -->
                                    <button
                                        type="button"
                                        wire:click="toggleFlag({{ $product->id }}, 'is_new')"
                                        title="{{ __('Toggle New Arrival') }}"
                                        class="rounded p-1 text-xs transition-colors {{ $product->is_new ? 'bg-rose-100 text-rose-700 dark:bg-rose-950/60 dark:text-rose-400 font-bold' : 'text-zinc-300 hover:text-zinc-500 dark:text-zinc-600' }}"
                                    >
                                        <flux:icon name="sparkles" class="size-4" />
                                    </button>

                                    <!-- Hot -->
                                    <button
                                        type="button"
                                        wire:click="toggleFlag({{ $product->id }}, 'is_hot')"
                                        title="{{ __('Toggle Hot Seller') }}"
                                        class="rounded p-1 text-xs transition-colors {{ $product->is_hot ? 'bg-amber-100 text-amber-700 dark:bg-amber-950/60 dark:text-amber-400 font-bold' : 'text-zinc-300 hover:text-zinc-500 dark:text-zinc-600' }}"
                                    >
                                        <flux:icon name="fire" class="size-4" />
                                    </button>

                                    <!-- Eco -->
                                    <button
                                        type="button"
                                        wire:click="toggleFlag({{ $product->id }}, 'is_eco')"
                                        title="{{ __('Toggle Eco-Friendly') }}"
                                        class="rounded p-1 text-xs transition-colors {{ $product->is_eco ? 'bg-green-100 text-green-700 dark:bg-green-950/60 dark:text-green-400 font-bold' : 'text-zinc-300 hover:text-zinc-500 dark:text-zinc-600' }}"
                                    >
                                        <flux:icon name="globe-alt" class="size-4" />
                                    </button>
                                </div>
                            </td>

                            <!-- Active Status -->
                            <td class="px-4 py-3 text-center">
                                <button
                                    type="button"
                                    wire:click="toggleStatus({{ $product->id }})"
                                    class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-medium transition-colors {{ $product->is_active ? 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100 dark:bg-emerald-950/60 dark:text-emerald-400' : 'bg-zinc-100 text-zinc-600 hover:bg-zinc-200 dark:bg-zinc-800 dark:text-zinc-400' }}"
                                >
                                    <span class="size-1.5 rounded-full {{ $product->is_active ? 'bg-emerald-600 dark:bg-emerald-400' : 'bg-zinc-400' }}"></span>
                                    {{ $product->is_active ? __('Active') : __('Inactive') }}
                                </button>
                            </td>

                            <!-- Actions -->
                            <td class="px-4 py-3 text-right">
                                <div class="inline-flex items-center gap-1">
                                    <flux:button
                                        size="sm"
                                        variant="ghost"
                                        icon="pencil-square"
                                        wire:click="openEditModal({{ $product->id }})"
                                        title="{{ __('Edit Product') }}"
                                    />
                                    <flux:button
                                        size="sm"
                                        variant="ghost"
                                        icon="trash"
                                        wire:click="openDeleteModal({{ $product->id }})"
                                        class="text-rose-600 hover:text-rose-700 dark:text-rose-400"
                                        title="{{ __('Delete Product') }}"
                                    />
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center">
                                <flux:icon name="cube" class="mx-auto size-10 text-zinc-300 dark:text-zinc-600" />
                                <h3 class="mt-2 text-sm font-medium text-zinc-900 dark:text-zinc-100">{{ __('No products found') }}</h3>
                                <p class="mt-1 text-xs text-zinc-500 dark:text-zinc-400">
                                    {{ $search ? __('Try adjusting your search criteria or clearing filters.') : __('Get started by creating your first promotional product.') }}
                                </p>
                                <div class="mt-4">
                                    <flux:button variant="primary" icon="plus" wire:click="openCreateModal" size="sm">
                                        {{ __('Create Product') }}
                                    </flux:button>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($products->hasPages())
            <div class="border-t border-zinc-200 p-4 dark:border-zinc-700">
                {{ $products->links() }}
            </div>
        @endif
    </div>

    <!-- Create / Edit Product Modal -->
    <flux:modal
        name="product-modal"
        wire:model="showModal"
        @close="closeModal"
        class="w-full max-w-4xl"
    >
        <form wire:submit.prevent="save" class="space-y-6">
            <!-- Modal Header -->
            <div class="flex items-start justify-between border-b border-zinc-200 pb-4 dark:border-zinc-700">
                <div>
                    <flux:heading size="lg">
                        {{ $productId ? __('Edit Product: ') . $name : __('Create New Product') }}
                    </flux:heading>
                    <flux:subheading>
                        {{ __('Configure promo code, pricing tiers, specifications, variants, and gallery.') }}
                    </flux:subheading>
                </div>
            </div>

            <!-- Tab Navigation -->
            <div class="flex flex-wrap gap-2 border-b border-zinc-200 pb-2 dark:border-zinc-700">
                <button
                    type="button"
                    wire:click="$set('activeTab', 'general')"
                    class="rounded-lg px-3 py-1.5 text-xs font-medium transition-colors {{ $activeTab === 'general' ? 'bg-zinc-900 text-white dark:bg-zinc-100 dark:text-zinc-900' : 'text-zinc-600 hover:bg-zinc-100 dark:text-zinc-400 dark:hover:bg-zinc-800' }}"
                >
                    {{ __('General & Content') }}
                </button>
                <button
                    type="button"
                    wire:click="$set('activeTab', 'specs')"
                    class="rounded-lg px-3 py-1.5 text-xs font-medium transition-colors {{ $activeTab === 'specs' ? 'bg-zinc-900 text-white dark:bg-zinc-100 dark:text-zinc-900' : 'text-zinc-600 hover:bg-zinc-100 dark:text-zinc-400 dark:hover:bg-zinc-800' }}"
                >
                    {{ __('Specifications & MOQ') }}
                </button>
                <button
                    type="button"
                    wire:click="$set('activeTab', 'pricing')"
                    class="rounded-lg px-3 py-1.5 text-xs font-medium transition-colors {{ $activeTab === 'pricing' ? 'bg-zinc-900 text-white dark:bg-zinc-100 dark:text-zinc-900' : 'text-zinc-600 hover:bg-zinc-100 dark:text-zinc-400 dark:hover:bg-zinc-800' }}"
                >
                    {{ __('Tiered Pricing') }} ({{ count($prices) }})
                </button>
                <button
                    type="button"
                    wire:click="$set('activeTab', 'variants')"
                    class="rounded-lg px-3 py-1.5 text-xs font-medium transition-colors {{ $activeTab === 'variants' ? 'bg-zinc-900 text-white dark:bg-zinc-100 dark:text-zinc-900' : 'text-zinc-600 hover:bg-zinc-100 dark:text-zinc-400 dark:hover:bg-zinc-800' }}"
                >
                    {{ __('Variants & Colors') }} ({{ count($variants) }})
                </button>
                <button
                    type="button"
                    wire:click="$set('activeTab', 'gallery')"
                    class="rounded-lg px-3 py-1.5 text-xs font-medium transition-colors {{ $activeTab === 'gallery' ? 'bg-zinc-900 text-white dark:bg-zinc-100 dark:text-zinc-900' : 'text-zinc-600 hover:bg-zinc-100 dark:text-zinc-400 dark:hover:bg-zinc-800' }}"
                >
                    {{ __('Images & Gallery') }} ({{ $existingImages->count() + count($newImages) }})
                </button>
                <button
                    type="button"
                    wire:click="$set('activeTab', 'categories')"
                    class="rounded-lg px-3 py-1.5 text-xs font-medium transition-colors {{ $activeTab === 'categories' ? 'bg-zinc-900 text-white dark:bg-zinc-100 dark:text-zinc-900' : 'text-zinc-600 hover:bg-zinc-100 dark:text-zinc-400 dark:hover:bg-zinc-800' }}"
                >
                    {{ __('Categories & Flags') }}
                </button>
            </div>

            <!-- TAB 1: General & Content -->
            <div x-show="$wire.activeTab === 'general'" class="space-y-4">
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                    <flux:input
                        wire:model.live.debounce.250ms="product_code"
                        :label="__('Product Code')"
                        placeholder="e.g. 817 or LL817"
                        required
                    />

                    <div class="sm:col-span-2">
                        <flux:input
                            wire:model.live.debounce.250ms="name"
                            :label="__('Product Title')"
                            placeholder="e.g. Mini Magic Cube - 5CM"
                            required
                        />
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-4">
                    <div class="sm:col-span-3">
                        <flux:input
                            wire:model.live="slug"
                            :label="__('Product Slug')"
                            :description="__('URL: /product/detail/{slug}')"
                            placeholder="e.g. mini-magic-cube-5cm-817"
                            required
                        />
                    </div>
                    <div class="flex items-end pb-1">
                        <flux:checkbox
                            wire:model.live="autoSlug"
                            :label="__('Auto-generate Slug')"
                        />
                    </div>
                </div>

                <flux:textarea
                    wire:model="short_desc"
                    :label="__('Short Teaser / Summary (Optional)')"
                    rows="2"
                    placeholder="Brief 1-2 sentence overview for cards and meta descriptions..."
                />

                <flux:textarea
                    wire:model="description"
                    :label="__('Full Product Description & Storytelling')"
                    rows="5"
                    placeholder="Provide full description, sales pitch, air/sea freight notes..."
                />
            </div>

            <!-- TAB 2: Specifications & MOQ -->
            <div x-show="$wire.activeTab === 'specs'" class="space-y-4">
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                    <flux:input
                        wire:model="size"
                        :label="__('Product Size / Dimensions')"
                        placeholder="e.g. 50mm x 50mm x 50mm"
                    />

                    <flux:input
                        wire:model="material"
                        :label="__('Material')"
                        placeholder="e.g. PS plastic, PP sticker panels"
                    />

                    <flux:input
                        wire:model="print_method"
                        :label="__('Print Method / Logo')"
                        placeholder="e.g. Digital Print, Screen Print"
                    />
                </div>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                    <flux:input
                        wire:model="moq"
                        type="number"
                        min="1"
                        :label="__('Minimum Order Quantity (MOQ)')"
                        placeholder="e.g. 100 or 250"
                        required
                    />

                    <flux:input
                        wire:model="lead_time"
                        :label="__('Lead Time / Production Window')"
                        placeholder="e.g. 4 - 5 weeks (air freight)"
                    />

                    <flux:input
                        wire:model="price_includes"
                        :label="__('Price Inclusions')"
                        placeholder="e.g. Includes full colour printed on all sides"
                    />
                </div>

                <flux:input
                    wire:model="packing_info"
                    :label="__('Packing & Carton Information')"
                    placeholder="e.g. 250pcs/ctn; Carton Size:55x30x30cm; NW/GW:13/14KG"
                />
            </div>

            <!-- TAB 3: Tiered Pricing -->
            <div x-show="$wire.activeTab === 'pricing'" class="space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h4 class="text-sm font-semibold text-zinc-900 dark:text-zinc-100">{{ __('Quantity Price Breaks') }}</h4>
                        <p class="text-xs text-zinc-500">{{ __('Define bulk discounts like on Sinovation (e.g. 100+, 250+, 500+, 1000+ pcs).') }}</p>
                    </div>
                    <flux:button type="button" size="sm" variant="filled" icon="plus" wire:click="addPriceTier">
                        {{ __('Add Tier') }}
                    </flux:button>
                </div>

                <div class="overflow-x-auto rounded-lg border border-zinc-200 dark:border-zinc-700">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-zinc-50 font-semibold text-zinc-600 dark:bg-zinc-800 dark:text-zinc-300">
                            <tr>
                                <th class="px-3 py-2">{{ __('Min Qty') }}</th>
                                <th class="px-3 py-2">{{ __('Max Qty (Empty for +)') }}</th>
                                <th class="px-3 py-2">{{ __('Unit Price') }}</th>
                                <th class="px-3 py-2">{{ __('Currency') }}</th>
                                <th class="px-3 py-2 text-right">{{ __('Remove') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                            @forelse ($prices as $idx => $p)
                                <tr>
                                    <td class="p-2">
                                        <flux:input type="number" min="1" wire:model="prices.{{ $idx }}.min_qty" size="sm" required />
                                    </td>
                                    <td class="p-2">
                                        <flux:input type="number" min="1" wire:model="prices.{{ $idx }}.max_qty" placeholder="{{ __('No limit') }}" size="sm" />
                                    </td>
                                    <td class="p-2">
                                        <flux:input type="number" step="0.001" min="0" wire:model="prices.{{ $idx }}.unit_price" placeholder="3.50" size="sm" required />
                                    </td>
                                    <td class="p-2">
                                        <flux:input wire:model="prices.{{ $idx }}.currency" size="sm" class="uppercase" maxlength="3" required />
                                    </td>
                                    <td class="p-2 text-right">
                                        <flux:button type="button" size="sm" variant="ghost" icon="trash" wire:click="removePriceTier({{ $idx }})" class="text-rose-600" />
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="p-4 text-center text-zinc-400 italic">
                                        {{ __('No pricing tiers configured. Click "Add Tier" above.') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- TAB 4: Variants & Colors -->
            <div x-show="$wire.activeTab === 'variants'" class="space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h4 class="text-sm font-semibold text-zinc-900 dark:text-zinc-100">{{ __('Product Variants & Color Swatches') }}</h4>
                        <p class="text-xs text-zinc-500">{{ __('List available colors or sizes with hex swatches for client selection.') }}</p>
                    </div>
                    <flux:button type="button" size="sm" variant="filled" icon="plus" wire:click="addVariant">
                        {{ __('Add Variant') }}
                    </flux:button>
                </div>

                <div class="overflow-x-auto rounded-lg border border-zinc-200 dark:border-zinc-700">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-zinc-50 font-semibold text-zinc-600 dark:bg-zinc-800 dark:text-zinc-300">
                            <tr>
                                <th class="px-3 py-2">{{ __('Variant / Color Name') }}</th>
                                <th class="px-3 py-2">{{ __('Color Swatch (Hex)') }}</th>
                                <th class="px-3 py-2">{{ __('Variant SKU (Optional)') }}</th>
                                <th class="px-3 py-2 text-right">{{ __('Remove') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                            @forelse ($variants as $vIdx => $v)
                                <tr>
                                    <td class="p-2">
                                        <flux:input wire:model="variants.{{ $vIdx }}.variant_name" placeholder="e.g. Navy Blue, Matte Black, XL" size="sm" required />
                                    </td>
                                    <td class="p-2">
                                        <div class="flex items-center gap-2">
                                            <input type="color" wire:model="variants.{{ $vIdx }}.color_code" class="size-7 rounded cursor-pointer border border-zinc-300 dark:border-zinc-700" />
                                            <flux:input wire:model="variants.{{ $vIdx }}.color_code" placeholder="#000000" size="sm" class="w-24 font-mono" />
                                        </div>
                                    </td>
                                    <td class="p-2">
                                        <flux:input wire:model="variants.{{ $vIdx }}.sku" placeholder="SKU-817-BLU" size="sm" />
                                    </td>
                                    <td class="p-2 text-right">
                                        <flux:button type="button" size="sm" variant="ghost" icon="trash" wire:click="removeVariant({{ $vIdx }})" class="text-rose-600" />
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="p-4 text-center text-zinc-400 italic">
                                        {{ __('No variants defined. Single-spec product.') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- TAB 5: Images & Gallery -->
            <div x-show="$wire.activeTab === 'gallery'" class="space-y-4">
                <div>
                    <h4 class="text-sm font-semibold text-zinc-900 dark:text-zinc-100">{{ __('Product Gallery Images') }}</h4>
                    <p class="text-xs text-zinc-500">{{ __('Upload high-res product photos. The primary image is used as the catalog thumbnail.') }}</p>
                </div>

                <!-- Existing Images -->
                @if ($existingImages->isNotEmpty())
                    <div>
                        <div class="mb-2 text-xs font-semibold text-zinc-700 dark:text-zinc-300">{{ __('Current Images') }}</div>
                        <div class="grid grid-cols-2 gap-3 sm:grid-cols-4 md:grid-cols-6">
                            @foreach ($existingImages as $img)
                                <div class="group relative aspect-square overflow-hidden rounded-lg border {{ $img->is_primary ? 'border-primary-500 ring-2 ring-primary-500' : 'border-zinc-200 dark:border-zinc-700' }} bg-zinc-100 dark:bg-zinc-800">
                                    <img src="{{ $img->image_url }}" class="size-full object-cover" />
                                    
                                    @if ($img->is_primary)
                                        <span class="absolute left-1 top-1 rounded bg-primary-600 px-1.5 py-0.5 text-[9px] font-bold text-white uppercase tracking-wider">
                                            {{ __('Primary') }}
                                        </span>
                                    @endif

                                    <div class="absolute inset-0 flex items-center justify-center gap-1.5 bg-black/60 opacity-0 transition-opacity group-hover:opacity-100">
                                        @if (! $img->is_primary)
                                            <flux:button type="button" size="xs" variant="primary" wire:click="setPrimaryImage({{ $img->id }})" title="{{ __('Set as Primary') }}">
                                                {{ __('Star') }}
                                            </flux:button>
                                        @endif
                                        <flux:button type="button" size="xs" variant="danger" wire:click="deleteExistingImage({{ $img->id }})" title="{{ __('Delete') }}">
                                            <flux:icon name="trash" class="size-3" />
                                        </flux:button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Upload New Images -->
                <div class="rounded-xl border-2 border-dashed border-zinc-300 p-6 text-center dark:border-zinc-700">
                    <flux:icon name="arrow-up-tray" class="mx-auto size-8 text-zinc-400" />
                    <div class="mt-2 text-sm text-zinc-600 dark:text-zinc-400">
                        <label class="cursor-pointer font-semibold text-primary-600 hover:text-primary-500">
                            <span>{{ __('Click to select multiple images') }}</span>
                            <input type="file" wire:model="newImages" multiple accept="image/*" class="sr-only" />
                        </label>
                    </div>
                    <p class="text-xs text-zinc-400 mt-1">PNG, JPG, WebP up to 5MB each</p>

                    <div wire:loading wire:target="newImages" class="mt-2 text-xs text-blue-500 font-medium">
                        {{ __('Uploading and processing images...') }}
                    </div>
                </div>

                <!-- New Images Preview -->
                @if (! empty($newImages))
                    <div>
                        <div class="mb-2 text-xs font-semibold text-zinc-700 dark:text-zinc-300">{{ __('New Images to Upload') }}</div>
                        <div class="grid grid-cols-2 gap-3 sm:grid-cols-4 md:grid-cols-6">
                            @foreach ($newImages as $nIdx => $nImg)
                                <div class="relative aspect-square overflow-hidden rounded-lg border border-zinc-200 bg-zinc-100 dark:border-zinc-700 dark:bg-zinc-800">
                                    @if ($nImg->isPreviewable())
                                        <img src="{{ $nImg->temporaryUrl() }}" class="size-full object-cover" />
                                    @endif
                                    <button
                                        type="button"
                                        wire:click="removeNewImage({{ $nIdx }})"
                                        class="absolute right-1 top-1 rounded-full bg-rose-600 p-1 text-white shadow hover:bg-rose-700"
                                    >
                                        <flux:icon name="x-mark" class="size-3" />
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- TAB 6: Categories & Flags -->
            <div x-show="$wire.activeTab === 'categories'" class="space-y-6">
                <!-- Categories Selection -->
                <div>
                    <h4 class="text-sm font-semibold text-zinc-900 dark:text-zinc-100 mb-1">{{ __('Assign Categories') }}</h4>
                    <p class="text-xs text-zinc-500 mb-3">{{ __('A promotional product can be shown in multiple categories (e.g. Leisure + Toys).') }}</p>

                    <div class="grid grid-cols-2 gap-2 sm:grid-cols-3 md:grid-cols-4 rounded-xl border border-zinc-200 p-3 max-h-60 overflow-y-auto dark:border-zinc-700">
                        @foreach ($categories as $cat)
                            <label class="flex items-center gap-2 rounded-lg p-2 text-xs hover:bg-zinc-50 dark:hover:bg-zinc-800 cursor-pointer">
                                <input
                                    type="checkbox"
                                    wire:model="selectedCategories"
                                    value="{{ $cat->id }}"
                                    class="size-4 rounded border-zinc-300 text-primary-600 focus:ring-primary-500 dark:border-zinc-700 dark:bg-zinc-800"
                                />
                                <span class="font-medium text-zinc-700 dark:text-zinc-200">{{ $cat->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- Product Status & Flags -->
                <div class="rounded-xl border border-zinc-200 bg-zinc-50/50 p-4 dark:border-zinc-700 dark:bg-zinc-800/40">
                    <h4 class="text-sm font-semibold text-zinc-900 dark:text-zinc-100 mb-3">{{ __('Homepage Highlights & Badges') }}</h4>
                    <div class="grid grid-cols-2 gap-4 sm:grid-cols-4">
                        <flux:checkbox
                            wire:model="is_active"
                            :label="__('Is Active (Visible in Store)')"
                        />
                        <flux:checkbox
                            wire:model="is_new"
                            :label="__('New Section Badge')"
                        />
                        <flux:checkbox
                            wire:model="is_hot"
                            :label="__('Hot Seller Section')"
                        />
                        <flux:checkbox
                            wire:model="is_eco"
                            :label="__('Eco-Friendly Badge')"
                        />
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="flex items-center justify-end gap-3 border-t border-zinc-200 pt-4 dark:border-zinc-700">
                <flux:button type="button" variant="ghost" wire:click="closeModal">
                    {{ __('Cancel') }}
                </flux:button>
                <flux:button type="submit" variant="primary" icon="check">
                    {{ $productId ? __('Update Product') : __('Create Product') }}
                </flux:button>
            </div>
        </form>
    </flux:modal>

    <!-- Delete Confirmation Modal -->
    <flux:modal
        name="delete-product-modal"
        wire:model="showDeleteModal"
        @close="closeDeleteModal"
        class="w-full max-w-md"
    >
        <div class="space-y-4">
            <div class="flex items-center gap-3">
                <div class="flex size-10 items-center justify-center rounded-full bg-rose-100 text-rose-600 dark:bg-rose-950/60 dark:text-rose-400">
                    <flux:icon name="exclamation-triangle" class="size-5" />
                </div>
                <div>
                    <flux:heading size="lg">{{ __('Delete Product?') }}</flux:heading>
                    <flux:subheading>{{ __('This action cannot be undone.') }}</flux:subheading>
                </div>
            </div>

            <p class="text-sm text-zinc-600 dark:text-zinc-300">
                {{ __('Are you sure you want to permanently delete') }}
                <strong class="text-zinc-900 dark:text-zinc-100">{{ $deleteName }}</strong>?
                {{ __('All associated pricing tiers, gallery images, and variants will be deleted.') }}
            </p>

            <div class="flex items-center justify-end gap-3 pt-2">
                <flux:button variant="ghost" wire:click="closeDeleteModal">
                    {{ __('Cancel') }}
                </flux:button>
                <flux:button variant="danger" icon="trash" wire:click="delete">
                    {{ __('Confirm Delete') }}
                </flux:button>
            </div>
        </div>
    </flux:modal>
</div>

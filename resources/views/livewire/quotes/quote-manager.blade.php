<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-semibold tracking-tight text-zinc-900 dark:text-zinc-100">
                {{ __('Quote Requests') }}
            </h1>
            <p class="text-sm text-zinc-500 dark:text-zinc-400">
                {{ __('Manage customer leads, artwork submissions, line items, and pricing status.') }}
            </p>
        </div>
    </div>

    <!-- Quick Stats Cards -->
    <div class="grid grid-cols-2 gap-4 sm:grid-cols-5">
        <div class="rounded-xl border border-zinc-200/80 bg-white p-4 shadow-sm dark:border-zinc-700/80 dark:bg-zinc-900">
            <div class="flex items-center justify-between">
                <span class="text-xs font-medium uppercase tracking-wider text-zinc-500">{{ __('Total Requests') }}</span>
                <span class="rounded-md bg-zinc-100 p-1.5 text-zinc-700 dark:bg-zinc-800 dark:text-zinc-300">
                    <flux:icon name="document-text" class="size-4" />
                </span>
            </div>
            <div class="mt-2 text-2xl font-bold text-zinc-900 dark:text-zinc-100">{{ $stats['total'] }}</div>
        </div>

        <div class="rounded-xl border border-zinc-200/80 bg-white p-4 shadow-sm dark:border-zinc-700/80 dark:bg-zinc-900">
            <div class="flex items-center justify-between">
                <span class="text-xs font-medium uppercase tracking-wider text-amber-600 dark:text-amber-400">{{ __('New / Unread') }}</span>
                <span class="rounded-md bg-amber-50 p-1.5 text-amber-600 dark:bg-amber-950/60 dark:text-amber-400">
                    <flux:icon name="bell-alert" class="size-4" />
                </span>
            </div>
            <div class="mt-2 text-2xl font-bold text-amber-600 dark:text-amber-400">{{ $stats['new'] }}</div>
        </div>

        <div class="rounded-xl border border-zinc-200/80 bg-white p-4 shadow-sm dark:border-zinc-700/80 dark:bg-zinc-900">
            <div class="flex items-center justify-between">
                <span class="text-xs font-medium uppercase tracking-wider text-blue-600 dark:text-blue-400">{{ __('Contacted') }}</span>
                <span class="rounded-md bg-blue-50 p-1.5 text-blue-600 dark:bg-blue-950/60 dark:text-blue-400">
                    <flux:icon name="chat-bubble-left-ellipsis" class="size-4" />
                </span>
            </div>
            <div class="mt-2 text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $stats['contacted'] }}</div>
        </div>

        <div class="rounded-xl border border-zinc-200/80 bg-white p-4 shadow-sm dark:border-zinc-700/80 dark:bg-zinc-900">
            <div class="flex items-center justify-between">
                <span class="text-xs font-medium uppercase tracking-wider text-indigo-600 dark:text-indigo-400">{{ __('Quoted') }}</span>
                <span class="rounded-md bg-indigo-50 p-1.5 text-indigo-600 dark:bg-indigo-950/60 dark:text-indigo-400">
                    <flux:icon name="paper-airplane" class="size-4" />
                </span>
            </div>
            <div class="mt-2 text-2xl font-bold text-indigo-600 dark:text-indigo-400">{{ $stats['quoted'] }}</div>
        </div>

        <div class="rounded-xl border border-zinc-200/80 bg-white p-4 shadow-sm dark:border-zinc-700/80 dark:bg-zinc-900">
            <div class="flex items-center justify-between">
                <span class="text-xs font-medium uppercase tracking-wider text-emerald-600 dark:text-emerald-400">{{ __('Won / Closed') }}</span>
                <span class="rounded-md bg-emerald-50 p-1.5 text-emerald-600 dark:bg-emerald-950/60 dark:text-emerald-400">
                    <flux:icon name="trophy" class="size-4" />
                </span>
            </div>
            <div class="mt-2 text-2xl font-bold text-emerald-600 dark:text-emerald-400">{{ $stats['won'] }}</div>
        </div>
    </div>

    <!-- Filters Toolbar -->
    <div class="rounded-xl border border-zinc-200/80 bg-white p-4 shadow-sm dark:border-zinc-700/80 dark:bg-zinc-900">
        <div class="flex flex-wrap items-center gap-4">
            <div class="flex-1 min-w-[240px]">
                <flux:input
                    wire:model.live.debounce.300ms="search"
                    icon="magnifying-glass"
                    placeholder="{{ __('Search customer name, email, company, quote #...') }}"
                    clearable
                />
            </div>

            <div class="w-48">
                <flux:select wire:model.live="statusFilter">
                    <option value="">{{ __('All Statuses') }}</option>
                    <option value="new">{{ __('New') }}</option>
                    <option value="contacted">{{ __('Contacted') }}</option>
                    <option value="quoted">{{ __('Quoted') }}</option>
                    <option value="won">{{ __('Won') }}</option>
                    <option value="lost">{{ __('Lost') }}</option>
                </flux:select>
            </div>
        </div>
    </div>

    <!-- Quotes Table -->
    <div class="overflow-hidden rounded-xl border border-zinc-200/80 bg-white shadow-sm dark:border-zinc-700/80 dark:bg-zinc-900">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="border-b border-zinc-200 bg-zinc-50 text-xs font-semibold uppercase tracking-wider text-zinc-500 dark:border-zinc-700 dark:bg-zinc-800/50 dark:text-zinc-400">
                    <tr>
                        <th class="px-4 py-3.5">{{ __('Reference / Customer') }}</th>
                        <th class="px-4 py-3.5">{{ __('Company') }}</th>
                        <th class="px-4 py-3.5">{{ __('Items Requested') }}</th>
                        <th class="px-4 py-3.5">{{ __('Needed By') }}</th>
                        <th class="px-4 py-3.5">{{ __('Status') }}</th>
                        <th class="px-4 py-3.5">{{ __('Date') }}</th>
                        <th class="px-4 py-3.5 text-right">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700/60">
                    @forelse ($quotes as $quote)
                        <tr class="transition-colors hover:bg-zinc-50/70 dark:hover:bg-zinc-800/40">
                            <!-- Ref & Customer -->
                            <td class="px-4 py-3">
                                <div class="font-semibold text-zinc-900 dark:text-zinc-100">
                                    {{ $quote->name }}
                                </div>
                                <div class="text-xs text-zinc-500 dark:text-zinc-400">
                                    {{ $quote->email }} {{ $quote->phone ? '• ' . $quote->phone : '' }}
                                </div>
                                @if ($quote->quote_number)
                                    <span class="mt-0.5 inline-block font-mono text-[10px] text-zinc-400">
                                        {{ $quote->quote_number }}
                                    </span>
                                @endif
                            </td>

                            <!-- Company -->
                            <td class="px-4 py-3 text-xs text-zinc-700 dark:text-zinc-300">
                                {{ $quote->company ?: '—' }}
                            </td>

                            <!-- Items summary -->
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center rounded-md bg-zinc-100 px-2 py-1 text-xs font-medium text-zinc-800 dark:bg-zinc-800 dark:text-zinc-200">
                                    {{ $quote->items->count() }} {{ Str::plural('item', $quote->items->count()) }}
                                    ({{ number_format($quote->items->sum('quantity')) }} pcs)
                                </span>
                            </td>

                            <!-- Needed By -->
                            <td class="px-4 py-3 text-xs text-zinc-600 dark:text-zinc-400">
                                {{ $quote->needed_by ? $quote->needed_by->format('d M Y') : 'Flexible' }}
                            </td>

                            <!-- Status Dropdown -->
                            <td class="px-4 py-3">
                                <select
                                    wire:change="updateStatus({{ $quote->id }}, $event.target.value)"
                                    class="rounded-md border border-zinc-200 bg-white px-2 py-1 text-xs font-semibold dark:border-zinc-700 dark:bg-zinc-800 {{ match($quote->status) {
                                        'new' => 'text-amber-600 dark:text-amber-400',
                                        'contacted' => 'text-blue-600 dark:text-blue-400',
                                        'quoted' => 'text-indigo-600 dark:text-indigo-400',
                                        'won' => 'text-emerald-600 dark:text-emerald-400',
                                        'lost' => 'text-rose-600 dark:text-rose-400',
                                        default => '',
                                    } }}"
                                >
                                    <option value="new" {{ $quote->status === 'new' ? 'selected' : '' }}>New</option>
                                    <option value="contacted" {{ $quote->status === 'contacted' ? 'selected' : '' }}>Contacted</option>
                                    <option value="quoted" {{ $quote->status === 'quoted' ? 'selected' : '' }}>Quoted</option>
                                    <option value="won" {{ $quote->status === 'won' ? 'selected' : '' }}>Won</option>
                                    <option value="lost" {{ $quote->status === 'lost' ? 'selected' : '' }}>Lost</option>
                                </select>
                            </td>

                            <!-- Date -->
                            <td class="px-4 py-3 text-xs text-zinc-400 dark:text-zinc-500 whitespace-nowrap">
                                {{ $quote->created_at->diffForHumans() }}
                            </td>

                            <!-- Actions -->
                            <td class="px-4 py-3 text-right">
                                <div class="inline-flex items-center gap-1">
                                    <flux:button
                                        size="sm"
                                        variant="filled"
                                        icon="eye"
                                        wire:click="viewQuote({{ $quote->id }})"
                                    >
                                        {{ __('View Details') }}
                                    </flux:button>
                                    <flux:button
                                        size="sm"
                                        variant="ghost"
                                        icon="trash"
                                        wire:click="openDeleteModal({{ $quote->id }})"
                                        class="text-rose-600"
                                    />
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center">
                                <flux:icon name="document-text" class="mx-auto size-10 text-zinc-400" />
                                <h3 class="mt-2 text-sm font-medium text-zinc-900 dark:text-zinc-100">{{ __('No quote requests found') }}</h3>
                                <p class="mt-1 text-xs text-zinc-500">{{ __('Incoming quote inquiries from clients will show up here.') }}</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($quotes->hasPages())
            <div class="border-t border-zinc-200 p-4 dark:border-zinc-700">
                {{ $quotes->links() }}
            </div>
        @endif
    </div>

    <!-- Quote Details Modal -->
    <flux:modal
        name="quote-details-modal"
        wire:model="showDetailsModal"
        @close="closeDetailsModal"
        class="w-full max-w-3xl"
    >
        @if ($selectedQuote)
            <div class="space-y-6">
                <!-- Header -->
                <div class="flex items-start justify-between border-b border-zinc-200 pb-4 dark:border-zinc-700">
                    <div>
                        <div class="flex items-center gap-2">
                            <flux:heading size="lg">{{ __('Quote Request') }}</flux:heading>
                            @if ($selectedQuote->quote_number)
                                <span class="rounded bg-zinc-100 px-2 py-0.5 font-mono text-xs font-semibold text-zinc-800 dark:bg-zinc-800 dark:text-zinc-200">
                                    {{ $selectedQuote->quote_number }}
                                </span>
                            @endif
                        </div>
                        <flux:subheading>
                            {{ __('Submitted on :date (:ago)', ['date' => $selectedQuote->created_at->format('d M Y, h:i A'), 'ago' => $selectedQuote->created_at->diffForHumans()]) }}
                        </flux:subheading>
                    </div>

                    <div>
                        <select
                            wire:change="updateStatus({{ $selectedQuote->id }}, $event.target.value)"
                            class="rounded-lg border border-zinc-200 bg-white px-3 py-1.5 text-xs font-semibold dark:border-zinc-700 dark:bg-zinc-800"
                        >
                            <option value="new" {{ $selectedQuote->status === 'new' ? 'selected' : '' }}>Status: New</option>
                            <option value="contacted" {{ $selectedQuote->status === 'contacted' ? 'selected' : '' }}>Status: Contacted</option>
                            <option value="quoted" {{ $selectedQuote->status === 'quoted' ? 'selected' : '' }}>Status: Quoted</option>
                            <option value="won" {{ $selectedQuote->status === 'won' ? 'selected' : '' }}>Status: Won</option>
                            <option value="lost" {{ $selectedQuote->status === 'lost' ? 'selected' : '' }}>Status: Lost</option>
                        </select>
                    </div>
                </div>

                <!-- Customer Details Grid -->
                <div class="grid grid-cols-2 gap-4 rounded-xl border border-zinc-200 bg-zinc-50/50 p-4 sm:grid-cols-4 dark:border-zinc-700 dark:bg-zinc-800/40">
                    <div>
                        <span class="text-xs text-zinc-400">{{ __('Customer Name') }}</span>
                        <p class="text-sm font-semibold text-zinc-900 dark:text-zinc-100">{{ $selectedQuote->name }}</p>
                    </div>
                    <div>
                        <span class="text-xs text-zinc-400">{{ __('Company') }}</span>
                        <p class="text-sm font-semibold text-zinc-900 dark:text-zinc-100">{{ $selectedQuote->company ?: 'N/A' }}</p>
                    </div>
                    <div>
                        <span class="text-xs text-zinc-400">{{ __('Email') }}</span>
                        <p class="text-sm font-semibold text-zinc-900 dark:text-zinc-100">{{ $selectedQuote->email }}</p>
                    </div>
                    <div>
                        <span class="text-xs text-zinc-400">{{ __('Phone') }}</span>
                        <p class="text-sm font-semibold text-zinc-900 dark:text-zinc-100">{{ $selectedQuote->phone ?: 'N/A' }}</p>
                    </div>
                    <div>
                        <span class="text-xs text-zinc-400">{{ __('Delivery Destination') }}</span>
                        <p class="text-sm font-semibold text-zinc-900 dark:text-zinc-100">{{ $selectedQuote->destination ?: 'N/A' }}</p>
                    </div>
                    <div>
                        <span class="text-xs text-zinc-400">{{ __('Needed By Date') }}</span>
                        <p class="text-sm font-semibold text-zinc-900 dark:text-zinc-100">
                            {{ $selectedQuote->needed_by ? $selectedQuote->needed_by->format('d M Y') : 'Not specified' }}
                        </p>
                    </div>
                    @if ($selectedQuote->artwork_url)
                        <div class="col-span-2">
                            <span class="text-xs text-zinc-400">{{ __('Uploaded Artwork File') }}</span>
                            <div class="mt-1">
                                <a
                                    href="{{ $selectedQuote->artwork_url }}"
                                    target="_blank"
                                    class="inline-flex items-center gap-1.5 text-xs font-semibold text-primary-600 hover:text-primary-500 underline"
                                >
                                    <flux:icon name="arrow-down-tray" class="size-4" />
                                    {{ __('Download / View Artwork File') }}
                                </a>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Message -->
                @if ($selectedQuote->message)
                    <div class="rounded-xl border border-zinc-200 p-4 dark:border-zinc-700">
                        <span class="text-xs font-semibold uppercase tracking-wider text-zinc-400">{{ __('Message from Customer') }}</span>
                        <p class="mt-1 text-sm text-zinc-700 dark:text-zinc-300 whitespace-pre-line">{{ $selectedQuote->message }}</p>
                    </div>
                @endif

                <!-- Requested Items List -->
                <div>
                    <h4 class="text-sm font-semibold text-zinc-900 dark:text-zinc-100 mb-2">{{ __('Requested Products & Quantities') }}</h4>
                    <div class="overflow-x-auto rounded-xl border border-zinc-200 dark:border-zinc-700">
                        <table class="w-full text-left text-xs">
                            <thead class="bg-zinc-50 font-semibold text-zinc-600 dark:bg-zinc-800 dark:text-zinc-300">
                                <tr>
                                    <th class="px-3 py-2.5">{{ __('Product') }}</th>
                                    <th class="px-3 py-2.5">{{ __('Code') }}</th>
                                    <th class="px-3 py-2.5">{{ __('Variant / Color') }}</th>
                                    <th class="px-3 py-2.5">{{ __('Quantity') }}</th>
                                    <th class="px-3 py-2.5">{{ __('Branding Option / Notes') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                                @forelse ($selectedQuote->items as $item)
                                    <tr>
                                        <td class="p-3">
                                            <div class="font-medium text-zinc-900 dark:text-zinc-100">
                                                {{ $item->product?->name ?: __('Unknown Product') }}
                                            </div>
                                        </td>
                                        <td class="p-3 font-mono text-zinc-600 dark:text-zinc-400">
                                            {{ $item->product?->product_code ?: '—' }}
                                        </td>
                                        <td class="p-3">
                                            @if ($item->variant)
                                                <span class="inline-flex items-center gap-1.5">
                                                    @if ($item->variant->color_code)
                                                        <span class="size-3 rounded-full border border-black/10" style="background-color: {{ $item->variant->color_code }}"></span>
                                                    @endif
                                                    {{ $item->variant->variant_name }}
                                                </span>
                                            @else
                                                <span class="text-zinc-400 italic">{{ __('Standard') }}</span>
                                            @endif
                                        </td>
                                        <td class="p-3 font-bold text-zinc-900 dark:text-zinc-100">
                                            {{ number_format($item->quantity) }} pcs
                                        </td>
                                        <td class="p-3 text-zinc-500">
                                            {{ $item->print_option ?: $item->notes ?: '—' }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="p-4 text-center text-zinc-400 italic">
                                            {{ __('No items listed in this quote.') }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Footer -->
                <div class="flex items-center justify-end border-t border-zinc-200 pt-4 dark:border-zinc-700">
                    <flux:button variant="ghost" wire:click="closeDetailsModal">
                        {{ __('Close') }}
                    </flux:button>
                </div>
            </div>
        @endif
    </flux:modal>

    <!-- Delete Modal -->
    <flux:modal
        name="delete-quote-modal"
        wire:model="showDeleteModal"
        @close="closeDeleteModal"
        class="w-full max-w-md"
    >
        <div class="space-y-4">
            <div class="flex items-center gap-3">
                <div class="flex size-10 items-center justify-center rounded-full bg-rose-100 text-rose-600">
                    <flux:icon name="exclamation-triangle" class="size-5" />
                </div>
                <div>
                    <flux:heading size="lg">{{ __('Delete Quote Request?') }}</flux:heading>
                    <flux:subheading>{{ __('This action cannot be undone.') }}</flux:subheading>
                </div>
            </div>

            <p class="text-sm text-zinc-600 dark:text-zinc-300">
                {{ __('Are you sure you want to delete') }} <strong>{{ $deleteNumber }}</strong>?
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

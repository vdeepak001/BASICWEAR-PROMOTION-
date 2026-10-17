<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-semibold tracking-tight text-zinc-900 dark:text-zinc-100">
                {{ __('Banners & Tiles') }}
            </h1>
            <p class="text-sm text-zinc-500 dark:text-zinc-400">
                {{ __('Manage homepage hero slider swipers and category promotional tiles.') }}
            </p>
        </div>

        <div class="flex items-center gap-3">
            <flux:button
                variant="primary"
                icon="plus"
                wire:click="openCreateModal"
                class="shadow-sm"
            >
                {{ __('New Banner') }}
            </flux:button>
        </div>
    </div>

    <!-- Quick Stats Cards -->
    <div class="grid grid-cols-2 gap-4 sm:grid-cols-4">
        <div class="rounded-xl border border-zinc-200/80 bg-white p-4 shadow-sm dark:border-zinc-700/80 dark:bg-zinc-900">
            <div class="flex items-center justify-between">
                <span class="text-xs font-medium uppercase tracking-wider text-zinc-500 dark:text-zinc-400">{{ __('Total Banners') }}</span>
                <span class="rounded-md bg-blue-50 p-1.5 text-blue-600 dark:bg-blue-950/60 dark:text-blue-400">
                    <flux:icon name="photo" class="size-4" />
                </span>
            </div>
            <div class="mt-2 text-2xl font-bold text-zinc-900 dark:text-zinc-100">{{ $stats['total'] }}</div>
        </div>

        <div class="rounded-xl border border-zinc-200/80 bg-white p-4 shadow-sm dark:border-zinc-700/80 dark:bg-zinc-900">
            <div class="flex items-center justify-between">
                <span class="text-xs font-medium uppercase tracking-wider text-zinc-500 dark:text-zinc-400">{{ __('Hero Slider') }}</span>
                <span class="rounded-md bg-purple-50 p-1.5 text-purple-600 dark:bg-purple-950/60 dark:text-purple-400">
                    <flux:icon name="rectangle-group" class="size-4" />
                </span>
            </div>
            <div class="mt-2 text-2xl font-bold text-zinc-900 dark:text-zinc-100">{{ $stats['hero'] }}</div>
        </div>

        <div class="rounded-xl border border-zinc-200/80 bg-white p-4 shadow-sm dark:border-zinc-700/80 dark:bg-zinc-900">
            <div class="flex items-center justify-between">
                <span class="text-xs font-medium uppercase tracking-wider text-zinc-500 dark:text-zinc-400">{{ __('Category Tiles') }}</span>
                <span class="rounded-md bg-amber-50 p-1.5 text-amber-600 dark:bg-amber-950/60 dark:text-amber-400">
                    <flux:icon name="squares-2x2" class="size-4" />
                </span>
            </div>
            <div class="mt-2 text-2xl font-bold text-zinc-900 dark:text-zinc-100">{{ $stats['category_tile'] }}</div>
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
    </div>

    <!-- Filters Toolbar -->
    <div class="rounded-xl border border-zinc-200/80 bg-white p-4 shadow-sm dark:border-zinc-700/80 dark:bg-zinc-900">
        <div class="flex flex-wrap items-center gap-4">
            <div class="w-48">
                <flux:select wire:model.live="positionFilter" placeholder="{{ __('All Placements') }}">
                    <option value="">{{ __('All Placements') }}</option>
                    <option value="hero">{{ __('Hero Slider (Top Carousel)') }}</option>
                    <option value="category_tile">{{ __('Category Tiles (Grid)') }}</option>
                </flux:select>
            </div>

            <div class="w-40">
                <flux:select wire:model.live="statusFilter">
                    <option value="">{{ __('All Statuses') }}</option>
                    <option value="active">{{ __('Active') }}</option>
                    <option value="inactive">{{ __('Inactive') }}</option>
                </flux:select>
            </div>
        </div>
    </div>

    <!-- Banners Grid / Cards -->
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
        @forelse ($banners as $banner)
            <div class="overflow-hidden rounded-xl border border-zinc-200/80 bg-white shadow-sm dark:border-zinc-700/80 dark:bg-zinc-900 flex flex-col justify-between">
                <div>
                    <!-- Banner Image Preview -->
                    <div class="relative {{ $banner->position === 'hero' ? 'aspect-[2.5/1]' : 'aspect-square' }} overflow-hidden bg-zinc-100 dark:bg-zinc-800">
                        <img src="{{ $banner->image_url }}" alt="{{ $banner->title ?? 'Banner' }}" class="size-full object-cover" />
                        <div class="absolute left-2 top-2 flex items-center gap-1.5">
                            <span class="rounded-md bg-zinc-900/80 px-2 py-0.5 text-[10px] font-semibold text-white uppercase tracking-wider">
                                {{ $banner->position === 'hero' ? 'Hero Slider' : 'Category Tile' }}
                            </span>
                            <span class="rounded-md bg-zinc-900/80 px-2 py-0.5 text-[10px] font-mono text-white">
                                #{{ $banner->sort_order }}
                            </span>
                        </div>
                    </div>

                    <!-- Details -->
                    <div class="p-4 space-y-2">
                        <div class="flex items-center justify-between">
                            <h3 class="font-semibold text-zinc-900 dark:text-zinc-100 truncate">
                                {{ $banner->title ?: __('Untitled Banner') }}
                            </h3>
                            <button
                                type="button"
                                wire:click="toggleStatus({{ $banner->id }})"
                                class="inline-flex items-center gap-1 rounded-full px-2 py-0.5 text-[11px] font-medium {{ $banner->is_active ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-400' : 'bg-zinc-100 text-zinc-500 dark:bg-zinc-800' }}"
                            >
                                <span class="size-1.5 rounded-full {{ $banner->is_active ? 'bg-emerald-600' : 'bg-zinc-400' }}"></span>
                                {{ $banner->is_active ? __('Active') : __('Inactive') }}
                            </button>
                        </div>

                        @if ($banner->link_url)
                            <div class="text-xs text-zinc-400 dark:text-zinc-500 truncate" title="{{ $banner->link_url }}">
                                <flux:icon name="link" class="inline size-3 mr-1" />
                                {{ $banner->link_url }}
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Footer Actions -->
                <div class="flex items-center justify-end gap-2 border-t border-zinc-100 p-3 dark:border-zinc-800">
                    <flux:button size="sm" variant="ghost" icon="pencil-square" wire:click="openEditModal({{ $banner->id }})">
                        {{ __('Edit') }}
                    </flux:button>
                    <flux:button size="sm" variant="ghost" icon="trash" wire:click="openDeleteModal({{ $banner->id }})" class="text-rose-600">
                        {{ __('Delete') }}
                    </flux:button>
                </div>
            </div>
        @empty
            <div class="col-span-full rounded-xl border border-dashed border-zinc-300 p-12 text-center dark:border-zinc-700">
                <flux:icon name="photo" class="mx-auto size-10 text-zinc-400" />
                <h3 class="mt-2 text-sm font-medium text-zinc-900 dark:text-zinc-100">{{ __('No banners yet') }}</h3>
                <p class="mt-1 text-xs text-zinc-500">{{ __('Create hero slider images or category tile banners.') }}</p>
                <div class="mt-4">
                    <flux:button variant="primary" icon="plus" wire:click="openCreateModal" size="sm">
                        {{ __('Create Banner') }}
                    </flux:button>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if ($banners->hasPages())
        <div class="pt-4">
            {{ $banners->links() }}
        </div>
    @endif

    <!-- Create / Edit Modal -->
    <flux:modal
        name="banner-modal"
        wire:model="showModal"
        @close="closeModal"
        class="w-full max-w-lg"
    >
        <form wire:submit.prevent="save" class="space-y-4">
            <div>
                <flux:heading size="lg">
                    {{ $bannerId ? __('Edit Banner') : __('Create New Banner') }}
                </flux:heading>
                <flux:subheading>
                    {{ __('Upload banner image, target URL, position, and display order.') }}
                </flux:subheading>
            </div>

            <flux:input
                wire:model="title"
                :label="__('Banner Title (Optional)')"
                placeholder="e.g. Summer Promo Products 2026"
            />

            <div class="grid grid-cols-2 gap-4">
                <flux:select wire:model="position" :label="__('Placement Position')" required>
                    <option value="hero">{{ __('Hero Slider (Top)') }}</option>
                    <option value="category_tile">{{ __('Category Tile (Grid)') }}</option>
                </flux:select>

                <flux:input
                    wire:model="sort_order"
                    type="number"
                    min="0"
                    :label="__('Sort Order')"
                    required
                />
            </div>

            <flux:input
                wire:model="link_url"
                :label="__('Target Link / URL (Optional)')"
                placeholder="e.g. /product/lister/lanyards or https://..."
            />

            <!-- Image Upload -->
            <flux:field>
                <flux:label>{{ __('Banner Image') }}</flux:label>
                <flux:description>{{ __('Hero banner recommended ratio: 4:1 or 2:1. Category tile: 1:1 square.') }}</flux:description>

                <div class="mt-2 space-y-3">
                    @if ($imageFile)
                        <div class="relative overflow-hidden rounded-lg border border-zinc-200">
                            @if ($imageFile->isPreviewable())
                                <img src="{{ $imageFile->temporaryUrl() }}" class="w-full max-h-48 object-cover" />
                            @else
                                <div class="flex h-32 items-center justify-center bg-zinc-50 px-4 text-center text-xs text-zinc-500">
                                    {{ __('This file can be saved, but a preview is not available.') }}
                                </div>
                            @endif
                        </div>
                    @elseif ($existingImageUrl)
                        <div class="relative overflow-hidden rounded-lg border border-zinc-200">
                            <img src="{{ $existingImageUrl }}" class="w-full max-h-48 object-cover" />
                        </div>
                    @endif

                    <input
                        type="file"
                        wire:model="imageFile"
                        accept="image/*"
                        class="block w-full text-xs text-zinc-600 file:me-3 file:rounded-md file:border-0 file:bg-zinc-900 file:px-3 file:py-1.5 file:text-xs file:font-semibold file:text-white hover:file:bg-zinc-800 dark:text-zinc-300 dark:file:bg-zinc-100 dark:file:text-zinc-900"
                    />

                    <div wire:loading wire:target="imageFile" class="text-xs text-blue-500">
                        {{ __('Uploading preview...') }}
                    </div>
                </div>
            </flux:field>

            <flux:checkbox
                wire:model="is_active"
                :label="__('Banner is Active and Visible')"
            />

            <!-- Footer -->
            <div class="flex items-center justify-end gap-3 border-t border-zinc-200 pt-4 dark:border-zinc-700">
                <flux:button type="button" variant="ghost" wire:click="closeModal">
                    {{ __('Cancel') }}
                </flux:button>
                <flux:button type="submit" variant="primary" icon="check">
                    {{ $bannerId ? __('Update Banner') : __('Create Banner') }}
                </flux:button>
            </div>
        </form>
    </flux:modal>

    <!-- Delete Modal -->
    <flux:modal
        name="delete-banner-modal"
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
                    <flux:heading size="lg">{{ __('Delete Banner?') }}</flux:heading>
                    <flux:subheading>{{ __('This action cannot be undone.') }}</flux:subheading>
                </div>
            </div>

            <p class="text-sm text-zinc-600 dark:text-zinc-300">
                {{ __('Are you sure you want to delete') }} <strong>{{ $deleteTitle }}</strong>?
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

<div class="space-y-6">
    <!-- Header & Statistics -->
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-semibold tracking-tight text-zinc-900 dark:text-zinc-100">
                {{ __('Categories') }}
            </h1>
            <p class="text-sm text-zinc-500 dark:text-zinc-400">
                {{ __('Manage hierarchical store categories, slugs, sorting, and icons.') }}
            </p>
        </div>

        <div class="flex items-center gap-3">
            <flux:button
                variant="primary"
                icon="plus"
                wire:click="openCreateModal"
                class="shadow-sm"
            >
                {{ __('New Category') }}
            </flux:button>
        </div>
    </div>

    <!-- Quick Stats Cards -->
    <div class="grid grid-cols-2 gap-4 sm:grid-cols-4">
        <div class="rounded-xl border border-zinc-200/80 bg-white p-4 shadow-sm dark:border-zinc-700/80 dark:bg-zinc-900">
            <div class="flex items-center justify-between">
                <span class="text-xs font-medium uppercase tracking-wider text-zinc-500 dark:text-zinc-400">{{ __('Total') }}</span>
                <span class="rounded-md bg-blue-50 p-1.5 text-blue-600 dark:bg-blue-950/60 dark:text-blue-400">
                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z" />
                    </svg>
                </span>
            </div>
            <div class="mt-2 text-2xl font-bold text-zinc-900 dark:text-zinc-100">{{ $stats['total'] }}</div>
        </div>

        <div class="rounded-xl border border-zinc-200/80 bg-white p-4 shadow-sm dark:border-zinc-700/80 dark:bg-zinc-900">
            <div class="flex items-center justify-between">
                <span class="text-xs font-medium uppercase tracking-wider text-zinc-500 dark:text-zinc-400">{{ __('Root / Main') }}</span>
                <span class="rounded-md bg-purple-50 p-1.5 text-purple-600 dark:bg-purple-950/60 dark:text-purple-400">
                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 0 0 8.716-6.747M12 21a9.004 9.004 0 0 1-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 0 1 7.843 4.582M12 3a8.997 8.997 0 0 0-7.843 4.582m15.686 0A11.953 11.953 0 0 1 12 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0 1 21 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0 1 12 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 0 1 3 12c0-1.605.42-3.113 1.157-4.418" />
                    </svg>
                </span>
            </div>
            <div class="mt-2 text-2xl font-bold text-zinc-900 dark:text-zinc-100">{{ $stats['root'] }}</div>
        </div>

        <div class="rounded-xl border border-zinc-200/80 bg-white p-4 shadow-sm dark:border-zinc-700/80 dark:bg-zinc-900">
            <div class="flex items-center justify-between">
                <span class="text-xs font-medium uppercase tracking-wider text-zinc-500 dark:text-zinc-400">{{ __('Active') }}</span>
                <span class="rounded-md bg-emerald-50 p-1.5 text-emerald-600 dark:bg-emerald-950/60 dark:text-emerald-400">
                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                </span>
            </div>
            <div class="mt-2 text-2xl font-bold text-emerald-600 dark:text-emerald-400">{{ $stats['active'] }}</div>
        </div>

        <div class="rounded-xl border border-zinc-200/80 bg-white p-4 shadow-sm dark:border-zinc-700/80 dark:bg-zinc-900">
            <div class="flex items-center justify-between">
                <span class="text-xs font-medium uppercase tracking-wider text-zinc-500 dark:text-zinc-400">{{ __('Inactive') }}</span>
                <span class="rounded-md bg-amber-50 p-1.5 text-amber-600 dark:bg-amber-950/60 dark:text-amber-400">
                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 0 0 5.636 5.636m12.728 12.728A9 9 0 0 1 5.636 5.636m12.728 12.728L5.636 5.636" />
                    </svg>
                </span>
            </div>
            <div class="mt-2 text-2xl font-bold text-amber-600 dark:text-amber-400">{{ $stats['inactive'] }}</div>
        </div>
    </div>

    <!-- Filters & Search Toolbar -->
    <div class="flex flex-col gap-3 rounded-xl border border-zinc-200/80 bg-white p-4 shadow-sm sm:flex-row sm:items-center sm:justify-between dark:border-zinc-700/80 dark:bg-zinc-900">
        <div class="flex-1">
            <flux:input
                wire:model.live.debounce.300ms="search"
                icon="magnifying-glass"
                placeholder="{{ __('Search categories by name or slug...') }}"
                clearable
            />
        </div>

        <div class="flex flex-wrap items-center gap-2">
            <!-- Parent Filter -->
            <flux:select wire:model.live="parentFilter" class="w-auto">
                <option value="">{{ __('All Levels') }}</option>
                <option value="root">{{ __('Root Categories Only') }}</option>
                @foreach ($rootCategories as $root)
                    <option value="{{ $root->id }}">{{ __('Under: ') . $root->name }}</option>
                @endforeach
            </flux:select>

            <!-- Status Filter -->
            <flux:select wire:model.live="statusFilter" class="w-auto">
                <option value="">{{ __('All Statuses') }}</option>
                <option value="active">{{ __('Active only') }}</option>
                <option value="inactive">{{ __('Inactive only') }}</option>
            </flux:select>

            <!-- Per Page -->
            <flux:select wire:model.live="perPage" class="w-auto">
                <option value="10">10 / page</option>
                <option value="25">25 / page</option>
                <option value="50">50 / page</option>
            </flux:select>
        </div>
    </div>

    <!-- Categories Table -->
    <div class="overflow-hidden rounded-xl border border-zinc-200/80 bg-white shadow-sm dark:border-zinc-700/80 dark:bg-zinc-900">
        <div class="overflow-x-auto">
            <table class="w-full text-start text-sm">
                <thead>
                    <tr class="border-b border-zinc-200 bg-zinc-50/75 text-xs font-semibold uppercase tracking-wider text-zinc-500 dark:border-zinc-700 dark:bg-zinc-800/50 dark:text-zinc-400">
                        <th class="py-3.5 ps-4 pe-2 text-start">{{ __('Icon') }}</th>
                        <th class="px-3 py-3.5 text-start">{{ __('Name / Hierarchy') }}</th>
                        <th class="px-3 py-3.5 text-start">{{ __('Slug') }}</th>
                        <th class="px-3 py-3.5 text-start">{{ __('Parent Category') }}</th>
                        <th class="px-3 py-3.5 text-center">{{ __('Sort Order') }}</th>
                        <th class="px-3 py-3.5 text-center">{{ __('Status') }}</th>
                        <th class="py-3.5 pe-4 ps-2 text-end">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700/60">
                    @forelse ($categories as $cat)
                        <tr wire:key="category-{{ $cat->id }}" class="transition-colors hover:bg-zinc-50/60 dark:hover:bg-zinc-800/40">
                            <!-- Icon -->
                            <td class="whitespace-nowrap py-3 ps-4 pe-2">
                                <div class="flex size-10 items-center justify-center overflow-hidden rounded-lg border border-zinc-200 bg-zinc-100 text-zinc-600 dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-300">
                                    @if ($cat->icon_url)
                                        <img src="{{ $cat->icon_url }}" alt="{{ $cat->name }}" class="size-full object-cover" />
                                    @else
                                        <svg class="size-5 text-zinc-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6Z" />
                                        </svg>
                                    @endif
                                </div>
                            </td>

                            <!-- Name & Hierarchy -->
                            <td class="px-3 py-3">
                                <div class="font-medium text-zinc-900 dark:text-zinc-100">
                                    {{ $cat->name }}
                                </div>
                                @if ($cat->parent)
                                    <div class="mt-0.5 flex items-center gap-1 text-xs text-zinc-500 dark:text-zinc-400">
                                        <span class="truncate">{{ $cat->hierarchy_name }}</span>
                                    </div>
                                @else
                                    <span class="inline-flex items-center rounded-full bg-blue-50 px-1.5 py-0.5 text-[10px] font-medium text-blue-700 dark:bg-blue-950/60 dark:text-blue-300">
                                        {{ __('Root') }}
                                    </span>
                                @endif
                            </td>

                            <!-- Slug -->
                            <td class="px-3 py-3 font-mono text-xs text-zinc-600 dark:text-zinc-300">
                                <span class="rounded bg-zinc-100 px-2 py-1 text-zinc-700 dark:bg-zinc-800 dark:text-zinc-300">
                                    {{ $cat->slug }}
                                </span>
                                <div class="mt-1 text-[11px] text-zinc-400">
                                    /product/lister/{{ $cat->slug }}
                                </div>
                            </td>

                            <!-- Parent Category -->
                            <td class="px-3 py-3 text-zinc-600 dark:text-zinc-300">
                                @if ($cat->parent)
                                    <div class="inline-flex items-center gap-1 text-xs">
                                        <svg class="size-3 text-zinc-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                                        </svg>
                                        <span class="font-medium">{{ $cat->parent->name }}</span>
                                    </div>
                                @else
                                    <span class="text-xs text-zinc-400">—</span>
                                @endif
                            </td>

                            <!-- Sort Order -->
                            <td class="px-3 py-3 text-center">
                                <span class="inline-flex items-center justify-center rounded-full bg-zinc-100 px-2 py-0.5 text-xs font-semibold text-zinc-700 dark:bg-zinc-800 dark:text-zinc-300">
                                    {{ $cat->sort_order }}
                                </span>
                            </td>

                            <!-- Status Toggle -->
                            <td class="px-3 py-3 text-center">
                                <button
                                    type="button"
                                    wire:click="toggleStatus({{ $cat->id }})"
                                    class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-medium transition-colors {{ $cat->is_active ? 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100 dark:bg-emerald-950/60 dark:text-emerald-300 dark:hover:bg-emerald-900/60' : 'bg-zinc-100 text-zinc-600 hover:bg-zinc-200 dark:bg-zinc-800 dark:text-zinc-400 dark:hover:bg-zinc-700' }}"
                                    title="{{ __('Click to toggle active status') }}"
                                >
                                    <span class="size-1.5 rounded-full {{ $cat->is_active ? 'bg-emerald-500' : 'bg-zinc-400' }}"></span>
                                    {{ $cat->is_active ? __('Active') : __('Inactive') }}
                                </button>
                            </td>

                            <!-- Actions -->
                            <td class="whitespace-nowrap py-3 pe-4 ps-2 text-end">
                                <div class="inline-flex items-center justify-end gap-1">
                                    <!-- Add Subcategory Shortcut -->
                                    <flux:button
                                        size="xs"
                                        variant="ghost"
                                        icon="plus"
                                        wire:click="openCreateModal({{ $cat->id }})"
                                        title="{{ __('Add Subcategory') }}"
                                    />

                                    <!-- Edit -->
                                    <flux:button
                                        size="xs"
                                        variant="ghost"
                                        icon="pencil-square"
                                        wire:click="openEditModal({{ $cat->id }})"
                                        title="{{ __('Edit Category') }}"
                                    />

                                    <!-- Delete -->
                                    <flux:button
                                        size="xs"
                                        variant="ghost"
                                        icon="trash"
                                        wire:click="confirmDelete({{ $cat->id }})"
                                        class="text-red-500 hover:text-red-700 hover:bg-red-50 dark:hover:bg-red-950/50"
                                        title="{{ __('Delete Category') }}"
                                    />
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center">
                                <div class="mx-auto flex size-12 items-center justify-center rounded-full bg-zinc-100 text-zinc-400 dark:bg-zinc-800 dark:text-zinc-500">
                                    <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z" />
                                    </svg>
                                </div>
                                <h3 class="mt-3 text-sm font-semibold text-zinc-900 dark:text-zinc-100">
                                    {{ __('No categories found') }}
                                </h3>
                                <p class="mt-1 text-xs text-zinc-500 dark:text-zinc-400">
                                    {{ $search ? __('Try adjusting your search terms or filters.') : __('Get started by creating your first product category.') }}
                                </p>
                                <div class="mt-4">
                                    <flux:button variant="primary" icon="plus" wire:click="openCreateModal" size="sm">
                                        {{ __('Create Category') }}
                                    </flux:button>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($categories->hasPages())
            <div class="border-t border-zinc-200 p-4 dark:border-zinc-700">
                {{ $categories->links() }}
            </div>
        @endif
    </div>

    <!-- Create / Edit Modal -->
    <flux:modal
        name="category-modal"
        wire:model="showModal"
        @close="closeModal"
        class="w-full max-w-xl"
    >
        <form wire:submit.prevent="save" class="space-y-6">
            <div>
                <flux:heading size="lg">
                    {{ $categoryId ? __('Edit Category') : __('Create New Category') }}
                </flux:heading>
                <flux:subheading>
                    {{ __('Configure category name, slug, parent relationship, and custom icon.') }}
                </flux:subheading>
            </div>

            <div class="space-y-4">
                <!-- Name -->
                <flux:input
                    wire:model.live.debounce.250ms="name"
                    :label="__('Category Name')"
                    placeholder="e.g. Eco Hoodies & Sweats"
                    required
                />

                <!-- Slug -->
                <flux:input
                    wire:model.live="slug"
                    :label="__('Slug')"
                    :description="__('Used in URL: /product/lister/{slug}')"
                    placeholder="e.g. eco-hoodies-sweats"
                    required
                />

                <!-- Parent Category -->
                <flux:select
                    wire:model.live="parent_id"
                    :label="__('Parent Category (Optional)')"
                >
                    <option value="">{{ __('None (Top Level Root Category)') }}</option>
                    @foreach ($parentOptions as $parentOpt)
                        <option value="{{ $parentOpt->id }}">{{ $parentOpt->hierarchy_name }}</option>
                    @endforeach
                </flux:select>

                <!-- Icon Upload & Custom Path -->
                <flux:field>
                    <flux:label>{{ __('Category Icon') }}</flux:label>
                    <flux:description>{{ __('Upload an icon (SVG, PNG, WebP) or specify an icon path.') }}</flux:description>

                    <div class="mt-2 flex items-center gap-4 rounded-xl border border-zinc-200 bg-zinc-50/50 p-3.5 dark:border-zinc-700/80 dark:bg-zinc-800/50">
                        <!-- Preview Box -->
                        <div class="flex size-14 shrink-0 items-center justify-center overflow-hidden rounded-xl border border-zinc-200 bg-white shadow-xs dark:border-zinc-700 dark:bg-zinc-900">
                            @if ($iconFile && $iconFile->isPreviewable())
                                <img src="{{ $iconFile->temporaryUrl() }}" class="size-full object-cover" />
                            @elseif ($existingIconUrl)
                                <img src="{{ $existingIconUrl }}" class="size-full object-cover" />
                            @else
                                <flux:icon name="photo" class="size-6 text-zinc-400" />
                            @endif
                        </div>

                        <div class="flex-1 space-y-2">
                            <input
                                type="file"
                                wire:model="iconFile"
                                accept="image/*"
                                class="block w-full text-xs text-zinc-600 file:me-3 file:rounded-md file:border-0 file:bg-zinc-900 file:px-3 file:py-1.5 file:text-xs file:font-semibold file:text-white hover:file:bg-zinc-800 dark:text-zinc-300 dark:file:bg-zinc-100 dark:file:text-zinc-900"
                            />
                            <div wire:loading wire:target="iconFile" class="text-xs text-blue-500">
                                {{ __('Uploading preview...') }}
                            </div>
                            @error('iconFile')
                                <flux:error name="iconFile" />
                            @enderror

                            <flux:input
                                wire:model.live="icon_path"
                                size="sm"
                                placeholder="{{ __('Or enter path: e.g. /icons/eco.svg or eco-icon') }}"
                            />
                        </div>
                    </div>
                </flux:field>

                <!-- Sort Order & Active Checkbox -->
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 sm:items-end">
                    <flux:input
                        type="number"
                        min="0"
                        wire:model.live="sort_order"
                        :label="__('Sort Order')"
                    />

                    <div class="pb-1">
                        <flux:checkbox
                            wire:model.live="is_active"
                            :label="__('Active')"
                            :description="__('Visible on public store catalog')"
                        />
                    </div>
                </div>
            </div>

            <!-- Modal Actions -->
            <div class="flex items-center justify-end gap-3 pt-3 border-t border-zinc-200 dark:border-zinc-700">
                <flux:modal.close>
                    <flux:button variant="ghost" wire:click="closeModal">
                        {{ __('Cancel') }}
                    </flux:button>
                </flux:modal.close>

                <flux:button
                    type="submit"
                    variant="primary"
                    wire:loading.attr="disabled"
                >
                    <span wire:loading.remove wire:target="save">
                        {{ $categoryId ? __('Save Changes') : __('Create Category') }}
                    </span>
                    <span wire:loading wire:target="save">
                        {{ __('Saving...') }}
                    </span>
                </flux:button>
            </div>
        </form>
    </flux:modal>

    <!-- Delete Confirmation Modal -->
    <flux:modal
        name="delete-category-modal"
        wire:model="showDeleteModal"
        @close="closeDeleteModal"
        class="max-w-md"
    >
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">{{ __('Delete Category') }}</flux:heading>
                <flux:subheading class="mt-2">
                    {{ __('Are you sure you want to delete') }} <strong class="text-zinc-900 dark:text-zinc-100">"{{ $deleteName }}"</strong>?
                </flux:subheading>
            </div>

            @if ($deleteChildrenCount > 0)
                <div class="rounded-lg border border-amber-200 bg-amber-50 p-3 text-xs text-amber-800 dark:border-amber-900/60 dark:bg-amber-950/40 dark:text-amber-300">
                    <strong>{{ __('Notice:') }}</strong> {{ __('This category has :count subcategories. Their parent reference will be set to NULL (they will become root categories).', ['count' => $deleteChildrenCount]) }}
                </div>
            @endif

            <div class="flex items-center justify-end gap-3 pt-2">
                <flux:modal.close>
                    <flux:button variant="ghost" wire:click="closeDeleteModal">
                        {{ __('Cancel') }}
                    </flux:button>
                </flux:modal.close>

                <flux:button
                    type="button"
                    variant="danger"
                    wire:click="deleteCategory"
                >
                    {{ __('Yes, Delete') }}
                </flux:button>
            </div>
        </div>
    </flux:modal>
</div>

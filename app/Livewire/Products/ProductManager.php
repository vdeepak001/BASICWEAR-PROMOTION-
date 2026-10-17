<?php

namespace App\Livewire\Products;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductPrice;
use App\Models\ProductVariant;
use Flux\Flux;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

#[Title('Product Management')]
#[Layout('layouts.app')]
class ProductManager extends Component
{
    use WithFileUploads, WithPagination;

    // Filters and Search
    public string $search = '';
    public string $categoryFilter = '';
    public string $statusFilter = '';
    public string $flagFilter = '';
    public string $sortField = 'created_at';
    public string $sortDirection = 'desc';
    public int $perPage = 10;

    // Modal State
    public bool $showModal = false;
    public ?int $productId = null;
    public string $activeTab = 'general'; // 'general', 'specs', 'pricing', 'variants', 'gallery'

    // Form: General
    public string $product_code = '';
    public string $name = '';
    public string $slug = '';
    public bool $autoSlug = true;
    public string $short_desc = '';
    public string $description = '';

    // Form: Specifications
    public string $size = '';
    public string $material = '';
    public string $print_method = '';
    public int $moq = 100;
    public string $packing_info = '';
    public string $lead_time = '';
    public string $price_includes = '';

    // Form: Flags & Categories
    public array $selectedCategories = [];
    public bool $is_new = false;
    public bool $is_hot = false;
    public bool $is_eco = false;
    public bool $is_active = true;

    // Form: Tiered Prices
    public array $prices = [];

    // Form: Variants
    public array $variants = [];

    // Form: Images
    public array $newImages = [];
    public Collection $existingImages;

    // Delete Confirmation
    public bool $showDeleteModal = false;
    public ?int $deleteId = null;
    public string $deleteName = '';

    public function mount(): void
    {
        $this->existingImages = collect();
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingCategoryFilter(): void
    {
        $this->resetPage();
    }

    public function updatingStatusFilter(): void
    {
        $this->resetPage();
    }

    public function updatingFlagFilter(): void
    {
        $this->resetPage();
    }

    public function sortBy(string $field): void
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function updatedName(string $value): void
    {
        if ($this->autoSlug) {
            $code = $this->product_code ? "-{$this->product_code}" : '';
            $this->slug = Str::slug($value . $code);
        }
    }

    public function updatedProductCode(string $value): void
    {
        if ($this->autoSlug && $this->name) {
            $code = $value ? "-{$value}" : '';
            $this->slug = Str::slug($this->name . $code);
        }
    }

    public function openCreateModal(): void
    {
        $this->resetErrorBag();
        $this->resetValidation();

        $this->productId = null;
        $this->activeTab = 'general';

        $this->product_code = '';
        $this->name = '';
        $this->slug = '';
        $this->autoSlug = true;
        $this->short_desc = '';
        $this->description = '';

        $this->size = '';
        $this->material = '';
        $this->print_method = '';
        $this->moq = 100;
        $this->packing_info = '';
        $this->lead_time = '3 - 4 weeks';
        $this->price_includes = 'Price includes full colour print';

        $this->selectedCategories = [];
        $this->is_new = true;
        $this->is_hot = false;
        $this->is_eco = false;
        $this->is_active = true;

        // Default initial price tiers
        $this->prices = [
            ['min_qty' => 100, 'max_qty' => 249, 'unit_price' => '3.50', 'currency' => 'AUD'],
            ['min_qty' => 250, 'max_qty' => 499, 'unit_price' => '3.00', 'currency' => 'AUD'],
            ['min_qty' => 500, 'max_qty' => 999, 'unit_price' => '2.50', 'currency' => 'AUD'],
            ['min_qty' => 1000, 'max_qty' => null, 'unit_price' => '2.00', 'currency' => 'AUD'],
        ];

        $this->variants = [];
        $this->newImages = [];
        $this->existingImages = collect();

        $this->showModal = true;
    }

    public function openEditModal(int $id): void
    {
        $this->resetErrorBag();
        $this->resetValidation();

        $product = Product::with(['categories', 'images', 'prices', 'variants'])->findOrFail($id);

        $this->productId = $product->id;
        $this->activeTab = 'general';

        $this->product_code = $product->product_code;
        $this->name = $product->name;
        $this->slug = $product->slug;
        $this->autoSlug = false;
        $this->short_desc = $product->short_desc ?? '';
        $this->description = $product->description ?? '';

        $this->size = $product->size ?? '';
        $this->material = $product->material ?? '';
        $this->print_method = $product->print_method ?? '';
        $this->moq = $product->moq ?? 100;
        $this->packing_info = $product->packing_info ?? '';
        $this->lead_time = $product->lead_time ?? '';
        $this->price_includes = $product->price_includes ?? '';

        $this->selectedCategories = $product->categories->pluck('id')->map(fn ($id) => (int) $id)->toArray();
        $this->is_new = (bool) $product->is_new;
        $this->is_hot = (bool) $product->is_hot;
        $this->is_eco = (bool) $product->is_eco;
        $this->is_active = (bool) $product->is_active;

        $this->prices = $product->prices->map(fn ($p) => [
            'id' => $p->id,
            'min_qty' => (int) $p->min_qty,
            'max_qty' => $p->max_qty ? (int) $p->max_qty : null,
            'unit_price' => (string) $p->unit_price,
            'currency' => $p->currency,
        ])->toArray();

        $this->variants = $product->variants->map(fn ($v) => [
            'id' => $v->id,
            'variant_name' => $v->variant_name,
            'color_code' => $v->color_code ?? '#000000',
            'sku' => $v->sku ?? '',
        ])->toArray();

        $this->existingImages = $product->images;
        $this->newImages = [];

        $this->showModal = true;
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->newImages = [];
        $this->existingImages = collect();
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function addPriceTier(): void
    {
        $lastTier = end($this->prices);
        $nextMin = $lastTier ? ($lastTier['max_qty'] ? $lastTier['max_qty'] + 1 : $lastTier['min_qty'] * 2) : 100;

        $this->prices[] = [
            'min_qty' => $nextMin,
            'max_qty' => null,
            'unit_price' => '1.00',
            'currency' => 'AUD',
        ];
    }

    public function removePriceTier(int $index): void
    {
        unset($this->prices[$index]);
        $this->prices = array_values($this->prices);
    }

    public function addVariant(): void
    {
        $this->variants[] = [
            'variant_name' => '',
            'color_code' => '#000000',
            'sku' => '',
        ];
    }

    public function removeVariant(int $index): void
    {
        unset($this->variants[$index]);
        $this->variants = array_values($this->variants);
    }

    public function setPrimaryImage(int $imageId): void
    {
        if (! $this->productId) {
            return;
        }

        ProductImage::where('product_id', $this->productId)->update(['is_primary' => false]);
        ProductImage::where('id', $imageId)->update(['is_primary' => true]);

        $this->existingImages = ProductImage::where('product_id', $this->productId)->orderBy('sort_order')->get();
        Flux::toast(variant: 'success', text: __('Primary image updated.'));
    }

    public function deleteExistingImage(int $imageId): void
    {
        $image = ProductImage::findOrFail($imageId);

        if (Storage::disk('public')->exists($image->image_path)) {
            Storage::disk('public')->delete($image->image_path);
        }

        $wasPrimary = $image->is_primary;
        $productId = $image->product_id;
        $image->delete();

        if ($wasPrimary) {
            $nextFirst = ProductImage::where('product_id', $productId)->first();
            if ($nextFirst) {
                $nextFirst->update(['is_primary' => true]);
            }
        }

        $this->existingImages = ProductImage::where('product_id', $productId)->orderBy('sort_order')->get();
        Flux::toast(variant: 'success', text: __('Image removed.'));
    }

    public function removeNewImage(int $index): void
    {
        unset($this->newImages[$index]);
        $this->newImages = array_values($this->newImages);
    }

    public function save(): void
    {
        $validated = $this->validate([
            'product_code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('products', 'product_code')->ignore($this->productId),
            ],
            'name' => ['required', 'string', 'max:200'],
            'slug' => [
                'required',
                'string',
                'max:255',
                Rule::unique('products', 'slug')->ignore($this->productId),
            ],
            'short_desc' => ['nullable', 'string', 'max:500'],
            'description' => ['nullable', 'string'],
            'size' => ['nullable', 'string', 'max:150'],
            'material' => ['nullable', 'string', 'max:150'],
            'print_method' => ['nullable', 'string', 'max:150'],
            'moq' => ['required', 'integer', 'min:1'],
            'packing_info' => ['nullable', 'string', 'max:255'],
            'lead_time' => ['nullable', 'string', 'max:100'],
            'price_includes' => ['nullable', 'string', 'max:255'],
            'is_new' => ['boolean'],
            'is_hot' => ['boolean'],
            'is_eco' => ['boolean'],
            'is_active' => ['boolean'],
            'selectedCategories' => ['nullable', 'array'],
            'selectedCategories.*' => ['integer', 'exists:categories,id'],
            'prices' => ['nullable', 'array'],
            'prices.*.min_qty' => ['required', 'integer', 'min:1'],
            'prices.*.max_qty' => ['nullable', 'integer', 'min:1'],
            'prices.*.unit_price' => ['required', 'numeric', 'min:0'],
            'prices.*.currency' => ['required', 'string', 'size:3'],
            'variants' => ['nullable', 'array'],
            'variants.*.variant_name' => ['required', 'string', 'max:100'],
            'variants.*.color_code' => ['nullable', 'string', 'max:30'],
            'variants.*.sku' => ['nullable', 'string', 'max:60'],
            'newImages.*' => ['image', 'max:5120'], // Max 5MB per image
        ]);

        DB::transaction(function () use ($validated) {
            $productData = [
                'product_code' => $validated['product_code'],
                'name' => $validated['name'],
                'slug' => $validated['slug'],
                'short_desc' => $validated['short_desc'] ?? null,
                'description' => $validated['description'] ?? null,
                'size' => $validated['size'] ?? null,
                'material' => $validated['material'] ?? null,
                'print_method' => $validated['print_method'] ?? null,
                'moq' => $validated['moq'],
                'packing_info' => $validated['packing_info'] ?? null,
                'lead_time' => $validated['lead_time'] ?? null,
                'price_includes' => $validated['price_includes'] ?? null,
                'is_new' => $validated['is_new'] ?? false,
                'is_hot' => $validated['is_hot'] ?? false,
                'is_eco' => $validated['is_eco'] ?? false,
                'is_active' => $validated['is_active'] ?? true,
            ];

            if ($this->productId) {
                $product = Product::findOrFail($this->productId);
                $product->update($productData);
            } else {
                $product = Product::create($productData);
                $this->productId = $product->id;
            }

            // Sync Categories
            $product->categories()->sync($this->selectedCategories ?? []);

            // Sync Tiered Prices (replace)
            $product->prices()->delete();
            if (! empty($this->prices)) {
                foreach ($this->prices as $p) {
                    ProductPrice::create([
                        'product_id' => $product->id,
                        'min_qty' => (int) $p['min_qty'],
                        'max_qty' => ! empty($p['max_qty']) ? (int) $p['max_qty'] : null,
                        'unit_price' => (float) $p['unit_price'],
                        'currency' => strtoupper($p['currency'] ?? 'AUD'),
                    ]);
                }
            }

            // Sync Variants (replace)
            $product->variants()->delete();
            if (! empty($this->variants)) {
                foreach ($this->variants as $v) {
                    ProductVariant::create([
                        'product_id' => $product->id,
                        'variant_name' => $v['variant_name'],
                        'color_code' => $v['color_code'] ?? null,
                        'sku' => ! empty($v['sku']) ? $v['sku'] : null,
                    ]);
                }
            }

            // Upload new images
            if (! empty($this->newImages)) {
                $hasExisting = ProductImage::where('product_id', $product->id)->exists();
                $sort = ProductImage::where('product_id', $product->id)->max('sort_order') ?? 0;

                foreach ($this->newImages as $index => $uploadedFile) {
                    $sort++;
                    $path = $uploadedFile->store('products', 'public');

                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => $path,
                        'alt_text' => $product->name,
                        'is_primary' => (! $hasExisting && $index === 0),
                        'sort_order' => $sort,
                    ]);
                }
            }
        });

        $this->showModal = false;
        $this->newImages = [];
        $this->existingImages = collect();

        Flux::toast(variant: 'success', text: __('Product saved successfully.'));
    }

    public function toggleStatus(int $id): void
    {
        $product = Product::findOrFail($id);
        $product->update(['is_active' => ! $product->is_active]);

        Flux::toast(
            variant: 'info',
            text: __(':name is now :status.', [
                'name' => $product->name,
                'status' => $product->is_active ? 'Active' : 'Inactive',
            ])
        );
    }

    public function toggleFlag(int $id, string $flag): void
    {
        if (! in_array($flag, ['is_new', 'is_hot', 'is_eco'])) {
            return;
        }

        $product = Product::findOrFail($id);
        $product->update([$flag => ! $product->{$flag}]);

        $label = match ($flag) {
            'is_new' => 'New Arrival',
            'is_hot' => 'Hot Seller',
            'is_eco' => 'Eco-Friendly',
        };

        Flux::toast(
            variant: 'info',
            text: __(':label status toggled for :name.', [
                'label' => $label,
                'name' => $product->name,
            ])
        );
    }

    public function openDeleteModal(int $id): void
    {
        $product = Product::findOrFail($id);
        $this->deleteId = $product->id;
        $this->deleteName = $product->name . " ({$product->product_code})";
        $this->showDeleteModal = true;
    }

    public function closeDeleteModal(): void
    {
        $this->showDeleteModal = false;
        $this->deleteId = null;
        $this->deleteName = '';
    }

    public function delete(): void
    {
        if (! $this->deleteId) {
            return;
        }

        $product = Product::with('images')->findOrFail($this->deleteId);

        // Clean up storage images
        foreach ($product->images as $img) {
            if (Storage::disk('public')->exists($img->image_path)) {
                Storage::disk('public')->delete($img->image_path);
            }
        }

        $name = $product->name;
        $product->delete();

        $this->showDeleteModal = false;
        $this->deleteId = null;
        $this->deleteName = '';

        Flux::toast(variant: 'danger', text: __(':name deleted successfully.', ['name' => $name]));
    }

    public function render(): View
    {
        $query = Product::with(['categories', 'images', 'prices']);

        // Search
        if ($this->search) {
            $query->search($this->search);
        }

        // Category Filter
        if ($this->categoryFilter) {
            $query->whereHas('categories', function (Builder $q) {
                $q->where('categories.id', $this->categoryFilter);
            });
        }

        // Status Filter
        if ($this->statusFilter === 'active') {
            $query->where('is_active', true);
        } elseif ($this->statusFilter === 'inactive') {
            $query->where('is_active', false);
        }

        // Flag Filter
        if ($this->flagFilter === 'new') {
            $query->where('is_new', true);
        } elseif ($this->flagFilter === 'hot') {
            $query->where('is_hot', true);
        } elseif ($this->flagFilter === 'eco') {
            $query->where('is_eco', true);
        }

        // Sorting
        $products = $query->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        // Stats
        $stats = [
            'total' => Product::count(),
            'active' => Product::where('is_active', true)->count(),
            'new' => Product::where('is_new', true)->count(),
            'hot' => Product::where('is_hot', true)->count(),
            'eco' => Product::where('is_eco', true)->count(),
        ];

        $categories = Category::active()->orderBy('name')->get();

        return view('livewire.products.product-manager', [
            'products' => $products,
            'stats' => $stats,
            'categories' => $categories,
        ]);
    }
}

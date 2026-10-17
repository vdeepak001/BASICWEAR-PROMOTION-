<?php

namespace App\Livewire\Categories;

use App\Models\Category;
use Flux\Flux;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

#[Title('Category Management')]
#[Layout('layouts.app')]
class CategoryManager extends Component
{
    use WithFileUploads, WithPagination;

    // Filter and search properties
    public string $search = '';
    public string $statusFilter = '';
    public string $parentFilter = '';
    public int $perPage = 10;

    // Modal state for Create / Edit
    public bool $showModal = false;
    public ?int $categoryId = null;
    public ?int $parent_id = null;
    public string $name = '';
    public string $slug = '';
    public string $icon_path = '';
    public $iconFile = null;
    public ?string $existingIconUrl = null;
    public int $sort_order = 0;
    public bool $is_active = true;
    public bool $autoSlug = true;

    // Delete confirmation modal state
    public bool $showDeleteModal = false;
    public ?int $deleteId = null;
    public string $deleteName = '';
    public int $deleteChildrenCount = 0;

    /**
     * Reset pagination when searching.
     */
    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    /**
     * Reset pagination when changing filters.
     */
    public function updatingStatusFilter(): void
    {
        $this->resetPage();
    }

    public function updatingParentFilter(): void
    {
        $this->resetPage();
    }

    /**
     * Automatically update slug when name changes if autoSlug is true.
     */
    public function updatedName(string $value): void
    {
        if ($this->autoSlug) {
            $this->slug = Str::slug($value);
        }
    }

    /**
     * Open modal to create a new category.
     */
    public function openCreateModal(?int $parentId = null): void
    {
        $this->resetErrorBag();
        $this->resetValidation();

        $this->categoryId = null;
        $this->parent_id = $parentId;
        $this->name = '';
        $this->slug = '';
        $this->icon_path = '';
        $this->iconFile = null;
        $this->existingIconUrl = null;
        $this->sort_order = Category::where('parent_id', $parentId)->max('sort_order') + 1 ?? 0;
        $this->is_active = true;
        $this->autoSlug = true;

        $this->showModal = true;
    }

    /**
     * Open modal to edit an existing category.
     */
    public function openEditModal(int $id): void
    {
        $this->resetErrorBag();
        $this->resetValidation();

        $category = Category::findOrFail($id);

        $this->categoryId = $category->id;
        $this->parent_id = $category->parent_id;
        $this->name = $category->name;
        $this->slug = $category->slug;
        $this->icon_path = $category->icon_path ?? '';
        $this->iconFile = null;
        $this->existingIconUrl = $category->icon_url;
        $this->sort_order = $category->sort_order;
        $this->is_active = (bool) $category->is_active;
        $this->autoSlug = false;

        $this->showModal = true;
    }

    /**
     * Close create/edit modal.
     */
    public function closeModal(): void
    {
        $this->showModal = false;
        $this->reset(['iconFile', 'existingIconUrl']);
        $this->resetErrorBag();
        $this->resetValidation();
    }

    /**
     * Close delete confirmation modal.
     */
    public function closeDeleteModal(): void
    {
        $this->showDeleteModal = false;
        $this->deleteId = null;
        $this->deleteName = '';
    }

    /**
     * Save category (create or update).
     */
    public function save(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:100'],
            'slug' => [
                'required',
                'string',
                'max:120',
                Rule::unique('categories', 'slug')->ignore($this->categoryId),
            ],
            'parent_id' => [
                'nullable',
                'integer',
                'exists:categories,id',
                function ($attribute, $value, $fail) {
                    if ($this->categoryId && $value == $this->categoryId) {
                        $fail(__('A category cannot be its own parent.'));
                    }
                },
            ],
            'icon_path' => ['nullable', 'string', 'max:255'],
            'iconFile' => ['nullable', 'image', 'max:2048'],
            'sort_order' => ['required', 'integer', 'min:0'],
            'is_active' => ['boolean'],
        ]);

        // Process file upload if provided
        if ($this->iconFile) {
            $path = $this->iconFile->store('categories/icons', 'public');
            $validated['icon_path'] = $path;
        }

        unset($validated['iconFile']);

        if ($this->categoryId) {
            $category = Category::findOrFail($this->categoryId);
            $category->update($validated);
            Flux::toast(variant: 'success', text: __('Category updated successfully.'));
        } else {
            Category::create($validated);
            Flux::toast(variant: 'success', text: __('Category created successfully.'));
        }

        $this->showModal = false;
        $this->reset(['iconFile', 'existingIconUrl']);
    }

    /**
     * Quick-toggle active status.
     */
    public function toggleStatus(int $id): void
    {
        $category = Category::findOrFail($id);
        $category->is_active = ! $category->is_active;
        $category->save();

        $statusText = $category->is_active ? __('activated') : __('deactivated');
        Flux::toast(variant: 'success', text: __("Category \":name\" :status.", ['name' => $category->name, 'status' => $statusText]));
    }

    /**
     * Confirm category deletion.
     */
    public function confirmDelete(int $id): void
    {
        $category = Category::findOrFail($id);
        $this->deleteId = $category->id;
        $this->deleteName = $category->name;
        $this->deleteChildrenCount = $category->children()->count();
        $this->showDeleteModal = true;
    }

    /**
     * Perform category deletion.
     */
    public function deleteCategory(): void
    {
        if ($this->deleteId) {
            $category = Category::findOrFail($this->deleteId);
            $category->delete();

            Flux::toast(variant: 'success', text: __('Category deleted successfully.'));
            $this->showDeleteModal = false;
            $this->deleteId = null;
            $this->deleteName = '';
        }
    }

    /**
     * Get parent options, excluding current category and its children when editing.
     *
     * @return Collection<int, Category>
     */
    public function getParentOptionsProperty(): Collection
    {
        $query = Category::orderBy('name');

        if ($this->categoryId) {
            $query->where('id', '!=', $this->categoryId);
        }

        return $query->get();
    }

    /**
     * Render the component view.
     */
    public function render(): View
    {
        $query = Category::query()
            ->with(['parent', 'children'])
            ->when($this->search !== '', function (Builder $q) {
                $q->where(function (Builder $sub) {
                    $sub->where('name', 'like', '%'.$this->search.'%')
                        ->orWhere('slug', 'like', '%'.$this->search.'%');
                });
            })
            ->when($this->statusFilter !== '', function (Builder $q) {
                $q->where('is_active', $this->statusFilter === 'active');
            })
            ->when($this->parentFilter !== '', function (Builder $q) {
                if ($this->parentFilter === 'root') {
                    $q->whereNull('parent_id');
                } else {
                    $q->where('parent_id', $this->parentFilter);
                }
            })
            ->orderBy('sort_order')
            ->orderBy('name');

        $categories = $query->paginate($this->perPage);

        // Statistics
        $stats = [
            'total' => Category::count(),
            'root' => Category::whereNull('parent_id')->count(),
            'active' => Category::where('is_active', true)->count(),
            'inactive' => Category::where('is_active', false)->count(),
        ];

        return view('livewire.categories.category-manager', [
            'categories' => $categories,
            'stats' => $stats,
            'parentOptions' => $this->parentOptions,
            'rootCategories' => Category::whereNull('parent_id')->orderBy('name')->get(),
        ]);
    }
}

<?php

namespace App\Livewire\Banners;

use App\Models\Banner;
use Flux\Flux;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

#[Title('Banner Management')]
#[Layout('layouts.app')]
class BannerManager extends Component
{
    use WithFileUploads, WithPagination;

    public string $positionFilter = '';
    public string $statusFilter = '';

    // Create / Edit Modal State
    public bool $showModal = false;
    public ?int $bannerId = null;
    public string $title = '';
    public string $position = 'hero'; // 'hero', 'category_tile'
    public string $link_url = '';
    public int $sort_order = 0;
    public bool $is_active = true;
    public $imageFile = null;
    public ?string $existingImageUrl = null;

    // Delete Modal State
    public bool $showDeleteModal = false;
    public ?int $deleteId = null;
    public string $deleteTitle = '';

    public function openCreateModal(): void
    {
        $this->resetErrorBag();
        $this->resetValidation();

        $this->bannerId = null;
        $this->title = '';
        $this->position = 'hero';
        $this->link_url = '';
        $this->sort_order = Banner::where('position', 'hero')->max('sort_order') + 1 ?? 0;
        $this->is_active = true;
        $this->imageFile = null;
        $this->existingImageUrl = null;

        $this->showModal = true;
    }

    public function openEditModal(int $id): void
    {
        $this->resetErrorBag();
        $this->resetValidation();

        $banner = Banner::findOrFail($id);

        $this->bannerId = $banner->id;
        $this->title = $banner->title ?? '';
        $this->position = $banner->position;
        $this->link_url = $banner->link_url ?? '';
        $this->sort_order = $banner->sort_order;
        $this->is_active = (bool) $banner->is_active;
        $this->imageFile = null;
        $this->existingImageUrl = $banner->image_url;

        $this->showModal = true;
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->reset(['imageFile', 'existingImageUrl']);
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function save(): void
    {
        $rules = [
            'title' => ['nullable', 'string', 'max:150'],
            'position' => ['required', 'string', 'in:hero,category_tile,popup'],
            'link_url' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['required', 'integer', 'min:0'],
            'is_active' => ['boolean'],
            'imageFile' => [$this->bannerId ? 'nullable' : 'required', 'image', 'max:5120'],
        ];

        $validated = $this->validate($rules);

        $data = [
            'title' => $validated['title'] ?? null,
            'position' => $validated['position'],
            'link_url' => $validated['link_url'] ?? null,
            'sort_order' => $validated['sort_order'],
            'is_active' => $validated['is_active'],
        ];

        if ($this->imageFile) {
            $path = $this->imageFile->store('banners', 'public');
            $data['image_path'] = $path;
        }

        if ($this->bannerId) {
            $banner = Banner::findOrFail($this->bannerId);

            // Clean up old image if new one uploaded
            if ($this->imageFile && $banner->image_path && Storage::disk('public')->exists($banner->image_path)) {
                Storage::disk('public')->delete($banner->image_path);
            }

            $banner->update($data);
            Flux::toast(variant: 'success', text: __('Banner updated successfully.'));
        } else {
            Banner::create($data);
            Flux::toast(variant: 'success', text: __('Banner created successfully.'));
        }

        $this->showModal = false;
        $this->reset(['imageFile', 'existingImageUrl']);
    }

    public function toggleStatus(int $id): void
    {
        $banner = Banner::findOrFail($id);
        $banner->update(['is_active' => ! $banner->is_active]);

        Flux::toast(variant: 'info', text: __('Banner status updated.'));
    }

    public function openDeleteModal(int $id): void
    {
        $banner = Banner::findOrFail($id);
        $this->deleteId = $banner->id;
        $this->deleteTitle = $banner->title ?: __('Banner #:id', ['id' => $banner->id]);
        $this->showDeleteModal = true;
    }

    public function closeDeleteModal(): void
    {
        $this->showDeleteModal = false;
        $this->deleteId = null;
        $this->deleteTitle = '';
    }

    public function delete(): void
    {
        if (! $this->deleteId) {
            return;
        }

        $banner = Banner::findOrFail($this->deleteId);

        if (Storage::disk('public')->exists($banner->image_path)) {
            Storage::disk('public')->delete($banner->image_path);
        }

        $banner->delete();

        $this->showDeleteModal = false;
        $this->deleteId = null;
        $this->deleteTitle = '';

        Flux::toast(variant: 'danger', text: __('Banner deleted successfully.'));
    }

    public function render(): View
    {
        $query = Banner::query();

        if ($this->positionFilter) {
            $query->where('position', $this->positionFilter);
        }

        if ($this->statusFilter === 'active') {
            $query->where('is_active', true);
        } elseif ($this->statusFilter === 'inactive') {
            $query->where('is_active', false);
        }

        $banners = $query->orderBy('position')->orderBy('sort_order')->paginate(12);

        $stats = [
            'total' => Banner::count(),
            'hero' => Banner::where('position', 'hero')->count(),
            'category_tile' => Banner::where('position', 'category_tile')->count(),
            'active' => Banner::where('is_active', true)->count(),
        ];

        return view('livewire.banners.banner-manager', [
            'banners' => $banners,
            'stats' => $stats,
        ]);
    }
}

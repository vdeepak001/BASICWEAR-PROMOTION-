<?php

namespace App\Livewire\Quotes;

use App\Models\QuoteRequest;
use Flux\Flux;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Quote Requests')]
#[Layout('layouts.app')]
class QuoteManager extends Component
{
    use WithPagination;

    public string $search = '';
    public string $statusFilter = '';
    public int $perPage = 10;

    // View Details Modal State
    public bool $showDetailsModal = false;
    public ?QuoteRequest $selectedQuote = null;

    // Delete Modal State
    public bool $showDeleteModal = false;
    public ?int $deleteId = null;
    public string $deleteNumber = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatusFilter(): void
    {
        $this->resetPage();
    }

    public function viewQuote(int $id): void
    {
        $this->selectedQuote = QuoteRequest::with(['items.product.primaryImage', 'items.variant', 'user'])->findOrFail($id);
        $this->showDetailsModal = true;
    }

    public function closeDetailsModal(): void
    {
        $this->showDetailsModal = false;
        $this->selectedQuote = null;
    }

    public function updateStatus(int $id, string $status): void
    {
        if (! in_array($status, ['new', 'contacted', 'quoted', 'won', 'lost'])) {
            return;
        }

        $quote = QuoteRequest::findOrFail($id);
        $quote->update(['status' => $status]);

        if ($this->selectedQuote && $this->selectedQuote->id === $id) {
            $this->selectedQuote->refresh();
        }

        Flux::toast(variant: 'success', text: __('Quote status updated to :status.', ['status' => ucfirst($status)]));
    }

    public function openDeleteModal(int $id): void
    {
        $quote = QuoteRequest::findOrFail($id);
        $this->deleteId = $quote->id;
        $this->deleteNumber = $quote->quote_number ?: __('Quote #:id', ['id' => $quote->id]);
        $this->showDeleteModal = true;
    }

    public function closeDeleteModal(): void
    {
        $this->showDeleteModal = false;
        $this->deleteId = null;
        $this->deleteNumber = '';
    }

    public function delete(): void
    {
        if (! $this->deleteId) {
            return;
        }

        $quote = QuoteRequest::findOrFail($this->deleteId);

        if ($quote->artwork_path && Storage::disk('public')->exists($quote->artwork_path)) {
            Storage::disk('public')->delete($quote->artwork_path);
        }

        $num = $this->deleteNumber;
        $quote->delete();

        $this->showDeleteModal = false;
        $this->deleteId = null;
        $this->deleteNumber = '';

        if ($this->selectedQuote && $this->selectedQuote->id === $quote->id) {
            $this->showDetailsModal = false;
            $this->selectedQuote = null;
        }

        Flux::toast(variant: 'danger', text: __(':number deleted.', ['number' => $num]));
    }

    public function render(): View
    {
        $query = QuoteRequest::with(['items.product', 'user']);

        if ($this->search) {
            $term = $this->search;
            $query->where(function (Builder $q) use ($term) {
                $q->where('name', 'like', "%{$term}%")
                    ->orWhere('email', 'like', "%{$term}%")
                    ->orWhere('company', 'like', "%{$term}%")
                    ->orWhere('quote_number', 'like', "%{$term}%")
                    ->orWhere('phone', 'like', "%{$term}%");
            });
        }

        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        $quotes = $query->latest()->paginate($this->perPage);

        $stats = [
            'total' => QuoteRequest::count(),
            'new' => QuoteRequest::where('status', 'new')->count(),
            'contacted' => QuoteRequest::where('status', 'contacted')->count(),
            'quoted' => QuoteRequest::where('status', 'quoted')->count(),
            'won' => QuoteRequest::where('status', 'won')->count(),
        ];

        return view('livewire.quotes.quote-manager', [
            'quotes' => $quotes,
            'stats' => $stats,
        ]);
    }
}

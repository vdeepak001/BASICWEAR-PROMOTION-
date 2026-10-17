<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class QuoteRequest extends Model
{
    use HasFactory;

    protected $table = 'quote_requests';

    protected $fillable = [
        'user_id',
        'quote_number',
        'name',
        'company',
        'email',
        'phone',
        'destination',
        'message',
        'artwork_path',
        'needed_by',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'needed_by' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(QuoteItem::class, 'quote_id');
    }

    public function scopeStatus(Builder $query, ?string $status): Builder
    {
        if (blank($status)) {
            return $query;
        }

        return $query->where('status', $status);
    }

    public function getArtworkUrlAttribute(): ?string
    {
        if (! $this->artwork_path) {
            return null;
        }

        if (Str::startsWith($this->artwork_path, ['http://', 'https://'])) {
            return $this->artwork_path;
        }

        if (Storage::disk('public')->exists($this->artwork_path)) {
            return Storage::disk('public')->url($this->artwork_path);
        }

        return asset($this->artwork_path);
    }

    public function getStatusBadgeVariantAttribute(): string
    {
        return match ($this->status) {
            'new' => 'warning',
            'contacted' => 'info',
            'quoted' => 'primary',
            'won' => 'success',
            'lost' => 'danger',
            default => 'outline',
        };
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Product extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'products';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'product_code',
        'name',
        'slug',
        'short_desc',
        'description',
        'size',
        'material',
        'print_method',
        'moq',
        'packing_info',
        'lead_time',
        'price_includes',
        'template_pdf_path',
        'is_new',
        'is_hot',
        'is_eco',
        'is_active',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'moq' => 'integer',
            'is_new' => 'boolean',
            'is_hot' => 'boolean',
            'is_eco' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Categories this product belongs to.
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'product_categories', 'product_id', 'category_id');
    }

    /**
     * Gallery images.
     */
    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order')->orderBy('id');
    }

    /**
     * Primary hero image.
     */
    public function primaryImage(): HasOne
    {
        return $this->hasOne(ProductImage::class)->where('is_primary', true);
    }

    /**
     * Product variants (colors, sizes, etc.).
     */
    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    /**
     * Quantity tiered price breaks.
     */
    public function prices(): HasMany
    {
        return $this->hasMany(ProductPrice::class)->orderBy('min_qty');
    }

    /**
     * Quote request items referencing this product.
     */
    public function quoteItems(): HasMany
    {
        return $this->hasMany(QuoteItem::class);
    }

    /**
     * Scope: Only active products.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: New arrivals.
     */
    public function scopeNew(Builder $query): Builder
    {
        return $query->where('is_new', true);
    }

    /**
     * Scope: Hot / Popular products.
     */
    public function scopeHot(Builder $query): Builder
    {
        return $query->where('is_hot', true);
    }

    /**
     * Scope: Eco-friendly products.
     */
    public function scopeEco(Builder $query): Builder
    {
        return $query->where('is_eco', true);
    }

    /**
     * Scope: Keyword search across product_code, name, and short_desc.
     */
    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        if (blank($term)) {
            return $query;
        }

        return $query->where(function ($q) use ($term) {
            $q->where('product_code', 'like', "%{$term}%")
                ->orWhere('name', 'like', "%{$term}%")
                ->orWhere('short_desc', 'like', "%{$term}%")
                ->orWhere('material', 'like', "%{$term}%");
        });
    }

    /**
     * Get the primary image URL or fallback to first image.
     */
    public function getPrimaryImageUrlAttribute(): ?string
    {
        $image = $this->primaryImage ?: $this->images->first();

        return $image?->image_url;
    }

    /**
     * Formatted lowest tier price or price range string (e.g. "$2.00 - $3.50").
     */
    public function getPriceRangeAttribute(): string
    {
        $prices = $this->prices;

        if ($prices->isEmpty()) {
            return 'Quote on Request';
        }

        $min = $prices->min('unit_price');
        $max = $prices->max('unit_price');
        $currency = $prices->first()->currency ?? 'AUD';

        if ($min == $max) {
            return sprintf('%s $%0.2f', $currency, $min);
        }

        return sprintf('%s $%0.2f - $%0.2f', $currency, $min, $max);
    }
}

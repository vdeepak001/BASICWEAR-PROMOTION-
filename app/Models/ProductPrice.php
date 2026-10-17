<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductPrice extends Model
{
    use HasFactory;

    protected $table = 'product_prices';

    protected $fillable = [
        'product_id',
        'min_qty',
        'max_qty',
        'unit_price',
        'currency',
    ];

    protected function casts(): array
    {
        return [
            'min_qty' => 'integer',
            'max_qty' => 'integer',
            'unit_price' => 'decimal:3',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function getFormattedPriceAttribute(): string
    {
        return sprintf('%s $%0.2f', $this->currency, $this->unit_price);
    }
}

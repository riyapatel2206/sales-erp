<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\SalesItems;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;


class Products extends Model
{
    protected $fillable = [
        'name',
        'sku',
        'price',
        'quantity',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
        ];
    }

    public function salesItems(): HasMany
    {
        return $this->hasMany(SalesItems::class);
    }

    public function isLowStock($threshold = 10): bool
    {
        return $this->quantity <= $threshold;
    }

    public function reduceStock(int $quantity): bool
    {
        if ($this->quantity >= $quantity) {
            $this->quantity -= $quantity;
            return $this->save();
        }
        return false;
    }
}

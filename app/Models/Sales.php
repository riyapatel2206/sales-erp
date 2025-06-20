<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\SalesItems;
use App\Models\Products;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
class Sales extends Model
{
    protected $fillable = [
        'order_number',
        'user_id',
        'total_amount',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'total_amount' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(SalesItems::class, 'sales_id');
    }

    public function confirm(): bool
    {
        if ($this->status === 'pending') {
            foreach ($this->items as $item) {
                if (!$item->product->reduceStock($item->quantity)) {
                    return false;
                }
            }
            $this->status = 'confirmed';
            return $this->save();
        }
        return false;
    }

    public static function generateOrderNumber(): string
    {
        return 'SO-' . date('Y') . '-' . str_pad(static::count() + 1, 6, '0', STR_PAD_LEFT);
    }
}

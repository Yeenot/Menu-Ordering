<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    /**
     * Columns that are guarded
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The order that belong to the item.
     */
    public function items()
    {
        return $this->belongsToMany(Item::class)
            ->withPivot([
                'name',
                'price',
                'tax',
                'quantity',
                'subtotal',
                'created_at',
                'updated_at'
            ]);
    }

    /**
     * Get the coupon for an item
     */
    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }
}

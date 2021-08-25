<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    /**
     * Columns that are guarded
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Get the category for an item
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * The item that belong to the order.
     */
    public function orders()
    {
        return $this->belongsToMany(Order::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    /**
     * Columns that are guarded
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Get the items for a category
     */
    public function items()
    {
        return $this->hasMany(Item::class);
    }
}

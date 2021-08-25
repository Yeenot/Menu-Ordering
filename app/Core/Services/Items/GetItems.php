<?php

namespace App\Core\Services\Items;

use App\Models\Item;
use App\Core\QueryBuilder;
use App\Core\Filters\FilterExact;

class GetItems
{
    public function execute()
    {
        return QueryBuilder::for(Item::class)
            ->addFilters([
                new FilterExact('category', 'category.name')
            ])
            ->addSearch(['name', 'price', 'tax', 'category.name'])
            ->get();
    }
}
<?php

namespace App\Core\Services\Items;

use App\Models\Category;
use App\Core\QueryBuilder;
use App\Core\Filters\FilterExact;

class GetCategories
{
    public function execute()
    {
        return QueryBuilder::for(Category::class)
            ->addFilters([
                new FilterExact('name')
            ])
            ->addSearch(['name'])
            ->get();
    }
}
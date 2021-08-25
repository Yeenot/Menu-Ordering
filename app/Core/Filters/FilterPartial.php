<?php

namespace App\Core\Filters;

use App\Core\Filters\BaseFilter;

class FilterPartial extends BaseFilter
{
    public function __invoke($query, $value)
    {
        $query->where($this->column, '%LIKE%', $value);
    }
}
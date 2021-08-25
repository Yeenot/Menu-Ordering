<?php

namespace App\Core\Filters;

use App\Core\Filters\BaseFilter;

class FilterExact extends BaseFilter
{
    public function __invoke($query, $value)
    {
        $query->where($this->column, $value);
    }
}
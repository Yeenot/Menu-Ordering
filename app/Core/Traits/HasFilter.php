<?php

namespace App\Core\Traits;

use Illuminate\Support\Arr;

Trait HasFilter
{

    public function addFilters($filters)
    {
        $filters = $this->filterFilters($filters);
        $this->attachFilters($this->subject, $this->undotFilters($filters));
        return $this;
    }

    private function filterFilters($filters)
    {
        return Arr::where($filters, function ($filter) {
            return request()->has($filter->getName());
        });
    }

    private function undotFilters($filters)
    {
        $undotFilters = [];
        foreach($filters as $filter) {
            $relation = $filter->getRelation();
            $items = Arr::get($undotFilters, $relation, []);
            $items[] = $filter;
            Arr::set($undotFilters, $relation, $items);
        }
        return $undotFilters;
    }

    private function attachFilters($query, $array)
    {
        foreach($array as $key => $value) {
            if (is_string($key)) {
                $query->whereHas($key, function($query) use ($value) {
                    $this->attachFilters($query, $value);
                });
            } else 
                $this->attachFilter($query, $value);
        }
    }

    private function attachFilter($query, $filter)
    {
        $name = $filter->getName();
        $filter($query, request()->{$name});
    }
}
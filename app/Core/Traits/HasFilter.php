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

    // /**
    //  * Columns aliases
    //  *
    //  * @var array
    //  */
    // protected $columnAlias = [];

    // /**
    //  * Columns that are filterable
    //  *
    //  * @var array
    //  */
    // protected $filterable = [];

    // /**
    //  * Columns that are searchable
    //  *
    //  * @var array
    //  */
    // protected $searchable = [];
    
    // public function scopeFilter($query, $values)
    // {
    //     $query->where(function($query) use ($values) {
    //         foreach ($values as $key => $value) {
    //             $key = $this->columnAlias[$key] ?: $key;
    //             $key = preg_split('~_(?=[^.]*$)~', $key);
    //             $relation = $key[0];
    //             $key = $key[1];
    //             $query->whereHas($relation, function($query) use ($key, $value) {
    //                 $query->where($key, $value);
    //             });
    //         }
    //     });

    //     return $query;
    // }

    // public function scopeFilter($query, $filters)
    // {
    //     $undotFilters = [];
    //     foreach($filters as $filter) {
    //         $relation = $filter->getRelation();
    //         $items = Arr::get($undotFilters, $relation, []);
    //         $items[] = $filter;
    //         Arr::set($undotFilters, $relation, $items);
    //     }

    //     die(dd($undotFilters));
    //     $log = [];
    //     assoc_walk_deep(
    //         $undotFilters,
    //         function($item, $path)  use ($query){

    //         },
    //         function($item, $path)  use (&$log){
    //             $log[] = ['item'=>$item, 'path'=>$path];
    //         }
    //     );

    //     die(dd($log));

    //     return $query;
    // }

    // ppublic function scopeSearch($query, $value)
    // {
    //     $value = is_array($value) ? $value[0] : $value;
    //     $query->where(function($query) use ($value) {
    //         foreach ($this->searchable as $key) {
    //             $key = $this->columnAlias[$key] ?: $key;
    //             $query->orWhere($key, $value);
    //         }
    //     });

    //     return $query;
    // }
}
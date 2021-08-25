<?php

namespace App\Core\Traits;

use Illuminate\Support\Arr;

Trait HasSearch
{

    public function addSearch($fields)
    {
        if (request()->has('search')) {
            $this->attachSearch($this->subject, $this->undotSearch(($fields)));
        }
        return $this;
    }

    private function undotSearch($fields)
    {
        $undotSearch = [];
        foreach($fields as $field) {
            $parts = explodeByLast('.', $field);
            $path = isset($parts[1]) ? $parts[0] : null;
            $key = $parts[1] ?? $parts[0];

            $items = Arr::get($undotSearch, $path, []);
            $items[] = $key;
            Arr::set($undotSearch, $path, $items);
        }
        return $undotSearch;
    }

    private function attachSearch($query, $array)
    {
        $query->where(function($query) use ($array) {
            foreach($array as $key => $value) {
                if (is_array($key) || is_string($key)) {
                    $query->orWhereHas($key, function($query) use ($value) {
                        $this->attachSearch($query, $value);
                    });
                } else {
                    $search = request()->search;
                    $query->orWhere($value, 'LIKE', "%{$search}%");
                }
            }
        });
    }
}
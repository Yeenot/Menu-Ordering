<?php

namespace App\Core\Filters;

interface Filter
{
    public function __construct($name, $internalName = null);

    public function __invoke($query, $value);

    public function getRelation();
}
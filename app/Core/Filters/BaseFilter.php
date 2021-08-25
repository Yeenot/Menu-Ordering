<?php

namespace App\Core\Filters;

class BaseFilter
{
    protected $name;

    protected $relation;

    protected $column;

    public function __construct($name, $internalName = null)
    {
        $internalName = $internalName ?: $name;
        $parts = explodeByLast('.', $internalName);
        
        $this->name = $name;
        $this->relation = isset($parts[1]) ? $parts[0] : null;
        $this->column = $parts[1] ?? $parts[0];
    }

    public function getName()
    {
        return $this->name;
    }

    public function getRelation()
    {
        return $this->relation;
    }
}
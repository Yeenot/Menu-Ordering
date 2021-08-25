<?php

namespace App\Core;

use ArrayAccess;
use Illuminate\Contracts\Pagination\CursorPaginator;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Request;
use Illuminate\Support\Traits\ForwardsCalls;
use App\Core\Traits\HasFilter;
use App\Core\Traits\HasSearch;

class QueryBuilder implements ArrayAccess
{
    use ForwardsCalls, HasFilter, HasSearch;
    
    /** @var \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Relations\Relation */
    protected $subject;

    /**
     * @param \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Relations\Relation $subject
     * @param null|\Illuminate\Http\Request $request
     */
    public function __construct($subject)
    {
        $this->initializeSubject($subject);
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Relations\Relation $subject
     *
     * @return $this
     */
    protected function initializeSubject($subject): self
    {
        $this->subject = $subject;
        return $this;
    }


    public function getEloquentBuilder(): EloquentBuilder
    {
        if ($this->subject instanceof EloquentBuilder) {
            return $this->subject;
        }

        if ($this->subject instanceof Relation) {
            return $this->subject->getQuery();
        }
    }

    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @param EloquentBuilder|Relation|string $subject
     * @param Request|null $request
     *
     * @return static
     */
    public static function for($subject): self
    {
        if (is_subclass_of($subject, Model::class)) {
            $subject = $subject::query();
        }

        return new static($subject);
    }

    public function __call($name, $arguments)
    {
        $result = $this->forwardCallTo($this->subject, $name, $arguments);

        /*
         * If the forwarded method call is part of a chain we can return $this
         * instead of the actual $result to keep the chain going.
         */
        if ($result === $this->subject) {
            return $this;
        }

        return $result;
    }

    public function clone()
    {
        return clone $this;
    }

    public function __clone()
    {
        $this->subject = clone $this->subject;
    }

    public function __get($name)
    {
        return $this->subject->{$name};
    }

    public function __set($name, $value)
    {
        $this->subject->{$name} = $value;
    }

    public function offsetExists($offset)
    {
        return isset($this->subject[$offset]);
    }

    public function offsetGet($offset)
    {
        return $this->subject[$offset];
    }

    public function offsetSet($offset, $value)
    {
        $this->subject[$offset] = $value;
    }

    public function offsetUnset($offset)
    {
        unset($this->subject[$offset]);
    }
}
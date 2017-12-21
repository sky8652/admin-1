<?php

namespace Sco\Admin\View\Extensions;

use Closure;
use Illuminate\Database\Eloquent\Builder;

class Applies extends Extension
{
    public function add($value)
    {
        if (!($value instanceof Closure)) {
            throw new \InvalidArgumentException('apply value must be \\Closure');
        }

        $this->push($value);
        return $this;
    }

    public function apply(Builder $query)
    {
        $this->each(function ($apply) use ($query) {
            if (is_callable($apply)) {
                call_user_func($apply, $query);
            }
        });
    }
}
<?php

namespace Sco\Admin\Display\Extensions;

use Illuminate\Database\Eloquent\Builder;

class Scopes extends Extension
{
    public function add($value)
    {
        if (! is_array($value)) {
            $value = func_get_args();
        }

        $this->push($value);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function apply(Builder $query)
    {
        $this->each(function ($scope) use ($query) {
            $method = array_shift($scope);
            $parameters = $scope;
            call_user_func_array([$query, $method], $parameters);
        });
    }
}

<?php


namespace Sco\Admin\Column;

use JsonSerializable;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Sco\Admin\Contracts\Column\Column as ColumnContract;

class IvuColumn extends Column implements ColumnContract, Arrayable, Jsonable, JsonSerializable
{
    protected $defaults = [
        'fixed' => false,
    ];
}

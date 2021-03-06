<?php

namespace Sco\Admin\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @mixin \Sco\Admin\Display\ColumnFactory
 */
class AdminColumn extends Facade
{
    public static function getFacadeAccessor()
    {
        return 'admin.column.factory';
    }
}

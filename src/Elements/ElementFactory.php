<?php


namespace Sco\Admin\Elements;

use Illuminate\Foundation\Application;
use Sco\Admin\Contracts\ElementFactoryInterface;
use Sco\Admin\Traits\AliasBinder;

/**
 * Class ElementFactory
 * @method Text text($name, $title)
 * @method Select select($name, $title)
 */
class ElementFactory implements ElementFactoryInterface
{
    use AliasBinder;

    protected $app;

    public function __construct(Application $app)
    {
        $this->app = $app;

        $this->registerAliases([
            'text'   => Text::class,
            'select' => Select::class,
        ]);
    }
}

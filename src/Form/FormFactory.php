<?php

namespace Sco\Admin\Form;

use Illuminate\Foundation\Application;
use Sco\Admin\Contracts\Form\FormFactoryInterface;
use Sco\Admin\Traits\AliasBinder;

/**
 * @method static \Sco\Admin\Form\Form form()
 */
class FormFactory implements FormFactoryInterface
{
    use AliasBinder;

    /**
     * @var \Illuminate\Foundation\Application
     */
    protected $app;

    public function __construct(Application $app)
    {
        $this->app = $app;

        $this->registerAliases([
            'form' => Form::class,
        ]);
    }
}

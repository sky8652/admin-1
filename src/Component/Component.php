<?php

namespace Sco\Admin\Component;

use BadMethodCallException;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;
use Illuminate\Foundation\Application;
use Sco\Admin\Component\Concerns\HasAccess;
use Sco\Admin\Component\Concerns\HasEvents;
use Sco\Admin\Component\Concerns\HasNavigation;
use Sco\Admin\Contracts\ComponentInterface;
use Sco\Admin\Contracts\Form\FormInterface;
use Sco\Admin\Contracts\RepositoryInterface;
use Sco\Admin\Contracts\Display\DisplayInterface;

abstract class Component implements ComponentInterface
{
    use HasAccess, HasEvents, HasNavigation;

    /**
     * @var
     */
    protected $name;

    /**
     * @var \Illuminate\Foundation\Application
     */
    protected $app;

    /**
     * The component display name
     *
     * @var string
     */
    protected $title;

    /**
     * @var mixed|\Sco\Admin\Contracts\RepositoryInterface
     */
    protected $repository;

    /**
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $model;

    protected static $booted = [];

    /**
     * @var \Illuminate\Contracts\Events\Dispatcher
     */
    protected static $dispatcher;

    abstract public function model();

    public function __construct(Application $app)
    {
        $this->app = $app;

        $this->bootIfNotBooted();
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        if (is_null($this->name)) {
            $this->setName($this->getDefaultName());
        }

        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function setName(string $value)
    {
        $this->name = $value;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * {@inheritdoc}
     */
    public function setTitle(string $value)
    {
        $this->title = $value;

        return $this;
    }

    protected function getDefaultName()
    {
        return $this->getModelClassName();
    }

    /**
     * @return string
     */
    protected function getModelClassName()
    {
        return snake_case( // 蛇形命名
            str_plural( // 复数
                class_basename(
                    get_class($this->getModel())
                )
            )
        );
    }

    /**
     * {@inheritdoc}
     */
    public function setModel(Model $model)
    {
        $this->model = $model;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getModel()
    {
        if (is_null($this->model)) {
            $this->setModel($this->makeModel());
        }

        return $this->model;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Model|mixed
     */
    protected function makeModel()
    {
        $class = $this->model();
        if (empty($class)) {
            throw new InvalidArgumentException(
                sprintf(
                    'The component(%s) method "model()" not found value',
                    get_class($this)
                )
            );
        }

        $model = $this->app->make($this->model());

        if (! ($model instanceof Model)) {
            throw new InvalidArgumentException(
                sprintf(
                    "Class %s must be an instance of %s",
                    $this->model(),
                    Model::class
                )
            );
        }

        return $model;
    }

    /**
     * {@inheritdoc}
     */
    public function getRepository()
    {
        if (is_null($this->repository)) {
            $this->setRepository($this->makeRepository());
        }

        return $this->repository;
    }

    /**
     * {@inheritdoc}
     */
    public function setRepository(RepositoryInterface $repository)
    {
        $this->repository = $repository;

        return $this;
    }

    protected function makeRepository()
    {
        $repository = $this->app->make(RepositoryInterface::class);
        $repository->setModel($this->getModel());

        return $repository;
    }

    /**
     * {@inheritdoc}
     */
    public function getConfigs()
    {
        return collect([
            'title'    => $this->getTitle(),
            'accesses' => $this->getAccesses(),
            'display'  => $this->fireDisplay() ?: [],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    final public function fireDisplay()
    {
        if (! method_exists($this, 'callDisplay')) {
            return;
        }

        $display = $this->app->call([$this, 'callDisplay']);

        if (! ($display instanceof DisplayInterface)) {
            throw new InvalidArgumentException(
                sprintf(
                    'callDisplay must be instanced of "%s".',
                    DisplayInterface::class
                )
            );
        }

        $display->setModel($this->getModel());

        return $display;
    }

    public function get()
    {
        $display = $this->fireDisplay();
        if ($display) {
            return $display->get();
        }

        return;
    }

    /**
     * {@inheritdoc}
     */
    final public function fireCreate()
    {
        if (! method_exists($this, 'callCreate')) {
            return;
        }

        $form = $this->app->call([$this, 'callCreate']);
        if (! $form instanceof FormInterface) {
            throw new InvalidArgumentException(
                sprintf(
                    'callCreate must be instanced of "%s".',
                    FormInterface::class
                )
            );
        }

        $form->setModel($this->getModel());

        return $form;
    }

    /**
     * {@inheritdoc}
     */
    public function store()
    {
        $form = $this->fireCreate();
        if ($form) {
            return $form->validate()->save();
        }

        return;
    }

    /**
     * {@inheritdoc}
     */
    final public function fireEdit($id)
    {
        if (! method_exists($this, 'callEdit')) {
            return;
        }

        $form = $this->app->call([$this, 'callEdit'], ['id' => $id]);

        if (! $form instanceof FormInterface) {
            throw new InvalidArgumentException(
                sprintf(
                    'callEdit must be instanced of "%s".',
                    FormInterface::class
                )
            );
        }

        $model = $this->getRepository()->findOrFail($id);

        $form->setModel($model);

        return $form;
    }

    /**
     * {@inheritdoc}
     */
    public function update($id)
    {
        $form = $this->fireEdit($id);
        if ($form) {
            return $form->validate()->save();
        }

        return;
    }

    public function delete($id)
    {
        $this->getRepository()->delete($id);

        return true;
    }

    public function forceDelete($id)
    {
        $this->getRepository()->forceDelete($id);

        return true;
    }

    public function restore($id)
    {
        $this->getRepository()->restore($id);

        return true;
    }

    protected function bootIfNotBooted()
    {
        if (! isset(static::$booted[static::class])) {
            static::$booted[static::class] = true;

            $this->fireEvent('booting', false);

            $this->boot();

            $this->fireEvent('booted', false);
        }
    }

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected function boot()
    {
        $this->bootTraits();
    }

    /**
     * Boot all of the bootable traits on the model.
     *
     * @return void
     */
    protected function bootTraits()
    {
        foreach (class_uses_recursive($this) as $trait) {
            if (method_exists($this, $method = 'boot' . class_basename($trait))) {
                $this->$method();
            }
        }
    }
}

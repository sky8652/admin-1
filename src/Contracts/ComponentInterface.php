<?php


namespace Sco\Admin\Contracts;

interface ComponentInterface
{
    public function boot();

    public function getName();

    public function getTitle();

    public function getModel();

    public function getRepository();

    /**
     * @return \Illuminate\Support\Collection
     */
    public function getConfigs();

    /**
     * @return \Sco\Admin\Contracts\View\ViewInterface
     */
    public function fireView();

    public function get();

    /**
     * @return \Sco\Admin\Contracts\Form\FormInterface
     */
    public function fireCreate();

    /**
     * @return mixed
     */
    public function store();

    /**
     * @param $id
     *
     * @return \Sco\Admin\Contracts\Form\FormInterface
     */
    public function fireEdit($id);

    /**
     * @param mixed $id
     *
     * @return mixed
     */
    public function update($id);

    public function delete($id);

    public function forceDelete($id);

    public function restore($id);


    public function isView();

    public function isCreate();

    public function isEdit();

    public function isDelete();

    public function isDestroy();

    public function isRestore();

    public function registerObserver($class = null);

    public function registerPermission($permission, $callback);

    public function can($permission);

    public function getPermissions();
}

<?php

namespace Sco\Admin\Form\Elements;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class MultiSelect extends Select
{
    protected $defaultValue = [];

    public function getValue()
    {
        $value = $this->getValueFromModel();
        if (empty($value)) {
            return $value;
        }

        if ($this->isOptionsModel() && $this->isRelation()) {
            $model = $this->getOptionsModel();
            $key = $this->getOptionsValueAttribute() ?: $model->getKeyName();

            $value = $value->pluck($key)->map(function ($item) {
                return (string)$item;
            });
        }
        return $value;
    }

    protected function isOptionsModel()
    {
        return is_string($this->options) || $this->options instanceof Model;
    }

    protected function isRelation()
    {
        return method_exists($this->getModel(), $this->getName());
    }

    public function save()
    {
        if (!($this->isOptionsModel() && $this->isRelation())) {
            parent::save();
        }
    }

    public function finishSave()
    {
        if (!($this->isOptionsModel() && $this->isRelation())) {
            return;
        }
        $attribute = $this->getName();

        $relation = $this->getModel()->{$attribute}();
    }

    public function toArray()
    {
        return parent::toArray() + [
                'multiple' => true,
            ];
    }
}
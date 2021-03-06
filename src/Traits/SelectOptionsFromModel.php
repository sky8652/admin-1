<?php

namespace Sco\Admin\Traits;

use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

trait SelectOptionsFromModel
{
    /**
     * @var string
     */
    protected $optionsLabelAttribute;

    /**
     * @var string
     */
    protected $optionsValueAttribute;

    /**
     * Get the options label attribute.
     *
     * @return string
     */
    public function getOptionsLabelAttribute()
    {
        return $this->optionsLabelAttribute;
    }

    /**
     * Set the options label attribute.
     *
     * @param string $value
     *
     * @return $this
     */
    public function setOptionsLabelAttribute($value)
    {
        $this->optionsLabelAttribute = $value;

        return $this;
    }

    /**
     * Get the options value attribute.
     *
     * @return string
     */
    public function getOptionsValueAttribute()
    {
        return $this->optionsValueAttribute;
    }

    /**
     * Set the options value attribute.
     *
     * @param string $value
     *
     * @return $this
     */
    public function setOptionsValueAttribute($value)
    {
        $this->optionsValueAttribute = $value;

        return $this;
    }

    /**
     * Get the options model.
     *
     * @return Model
     */
    public function getOptionsModel()
    {
        $model = $this->options;

        if (is_string($model)) {
            $model = app($model);
        }

        if (! ($model instanceof Model)) {
            throw new InvalidArgumentException(
                sprintf(
                    'The %s element[%s] options class must be instanced of "%s".',
                    $this->getType(),
                    $this->getName(),
                    Model::class
                )
            );
        }

        return $model;
    }

    protected function isOptionsModel()
    {
        return is_string($this->options) || $this->options instanceof Model;
    }

    /**
     * Get the options from model.
     *
     * @return \Illuminate\Support\Collection
     */
    protected function getOptionsFromModel()
    {
        if (is_null(($label = $this->getOptionsLabelAttribute()))) {
            throw new InvalidArgumentException(
                sprintf(
                    'The %s element[%s] options must set label attribute',
                    $this->getType(),
                    $this->getName()
                )
            );
        }

        $model = $this->getOptionsModel();

        /**
         * @var \Illuminate\Support\Collection $results
         */
        $results = $model->get();

        $key = $this->getOptionsValueAttribute() ?: $model->getKeyName();

        return $results->pluck($label, $key);
    }
}

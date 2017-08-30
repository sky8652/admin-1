<?php

namespace Sco\Admin\Form\Elements;

class Images extends File
{
    protected $type = 'images';

    protected $listType = 'picture';

    public function cardListType()
    {
        $this->listType = 'picture-card';

        return $this;
    }

    protected function getDefaultExtensions()
    {
        return config('admin.upload.extensions.image');
    }
}
<?php

return [
    'title' => '用户',
    'model' => config('admin.user'),

    'permissions' => [
        'view'   => function () {
            return Auth::user()->can('view_user');
        },
        'create' => function () {
            return Auth::user()->can('create_user');
        },
        'edit'   => function () {
            return Auth::user()->can('edit_user');
        },
        'delete' => function () {
            return Auth::user()->can('delete_user');
        },
    ],
    'columns'     => [
        'id' => [
            'title' => 'ID',
            'width' => '80',
        ],
        'name' => [
            'title' => 'Name',
            'width' => '120',
        ],
        'email' => [
            'title' => 'email',
        ],
        'roles' => [
            'title'        => 'roles',
            'relationship' => 'roles',
            'fields'       => 'name,display_name',
            'render'       => '<span>{{item.display_name}}[{{item.name}}] {{index}}</span>',
            //'render'       => '<span>(:display_name)[(:name)] (:index)</span>',
        ],
        'created_at' => [
            'title' => 'created_at',
            'format' => 'humans',
        ],
    ],
];

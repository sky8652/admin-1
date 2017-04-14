<?php

Route::group([
    'prefix' => 'admin',
    'middleware' => 'web',
    'namespace' => 'Sco\Admin\Http\Controllers',
], function () {
    //登录页
    Route::get('login', 'Auth\LoginController@showLoginForm')->name('admin.login');
    //登录提交
    Route::post('postLogin', 'Auth\LoginController@login')->name('admin.postLogin');
    //退出
    Route::get('logout', 'Auth\LoginController@logout')->name('admin.logout');

    Route::group(['middleware' => 'auth.admin'], function () {
        // 控制台
        Route::get('/', 'BaseController@index')
            ->name('admin.index');

        Route::get('menu', function () {
            $menus = request()->get('admin.menu');
            return response()->json($menus);
        })->name('admin.menu')
            ->middleware('admin.menu');

        // 菜单管理
        Route::get('system/menu', 'BaseController@index')
            ->name('admin.system.menu');

        // 菜单列表数据
        Route::get('system/menu/list', 'System\MenuController@getList')
            ->name('admin.system.menu.list');

        // 保存菜单
        Route::post('system/menu/save', 'System\MenuController@save')
            ->name('admin.system.menu.save');

        // 删除菜单
        Route::delete('system/menu/{id}', 'System\MenuController@delete')
            ->name('admin.system.menu.delete');

        // 批量删除菜单
        Route::post('system/menu/batch/delete', 'System\MenuController@batchDelete')
            ->name('admin.system.menu.batch.delete');

        Route::get('manager/user', 'BaseController@index')
            ->name('admin.manager.user');

        // 管理员列表数据
        Route::get('manager/user/list', 'Manager\UserController@getList')
            ->name('admin.manager.user.list');

        Route::post('manager/user/save', 'Manager\UserController@save')
            ->name('admin.manager.user.save');

        Route::post('manager/user/save/role', 'Manager\UserController@saveRole')
            ->name('admin.manager.user.save.role');

        Route::delete('manager/user/{id}', 'Manager\UserController@delete')
            ->name('admin.manager.user.delete');

        //角色管理
        Route::get('manager/role', 'BaseController@index')
            ->name('admin.manager.role');

        // 角色列表
        Route::get('manager/role/list', 'Manager\RoleController@getList')
            ->name('admin.manager.role.list');

        Route::post('manager/role/authorize', 'Manager\RoleController@authorize')
            ->name('admin.manager.role.authorize');

        //用户管理
        /*Route::group(['prefix' => 'users', 'namespace' => 'Users'], function () {
            //用户列表
            Route::get('user', 'UserController@getIndex')
                ->name('admin.users.user')
                ->middleware('admin.menu');

            // 添加用户
            Route::get('user/add', 'UserController@getAdd')->name('admin.users.user.add');

            // 编辑用户
            Route::get('user/{uid}/edit', 'UserController@getEdit')->name('admin.users.user.edit');

            // 保存添加用户
            Route::post('user/postAdd', 'UserController@postAdd')->name('admin.users.user.postAdd');

            // 保存编辑用户
            Route::post('user/{uid}/edit', 'UserController@postEdit')
                ->name('admin.users.user.postEdit');

            // 删除用户
            Route::get('user/{uid}/delete', 'UserController@getDelete')
                ->name('admin.users.user.delete');

            //角色管理
            Route::get('role', 'RoleController@getIndex')
                ->name('admin.users.role')
                ->middleware('admin.menu');

            // 新增角色
            Route::get('role/add', 'RoleController@getAdd')->name('admin.users.role.add');

            // 保存新增角色
            Route::post('role/postAdd', 'RoleController@postAdd')->name('admin.users.role.postAdd');

            // 编辑角色
            Route::get('role/{id}/edit', 'RoleController@getEdit')->name('admin.users.role.edit');

            // 保存编辑角色
            Route::post('role/{id}/edit', 'RoleController@postEdit')
                ->name('admin.users.role.postEdit');

            // 角色授权
            Route::get('role/{id}/authorize', 'RoleController@getAuthorize')
                ->name('admin.users.role.authorize')
                ->middleware('admin.menu');

            // 删除角色
            Route::get('role/{id}/delete', 'RoleController@getDelete')
                ->name('admin.users.role.delete');

            // 保存角色授权
            Route::post('role/{id}/authorize', 'RoleController@postAuthorize')
                ->name('admin.users.role.postAuthorize');
        });*/
    });
});

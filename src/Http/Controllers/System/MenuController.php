<?php

namespace Sco\Admin\Http\Controllers\System;

use Illuminate\Http\Request;
use Sco\Admin\Http\Controllers\BaseController;
use Sco\Admin\Http\Requests\PermissionRequest;
use Sco\Admin\Models\Permission;

/**
 * 菜单管理
 */
class MenuController extends BaseController
{
    /**
     * @var Permission
     */
    private $permissionModel;

    /**
     * @return \Sco\Admin\Models\Permission
     */
    private function getPermissionModel()
    {
        if ($this->permissionModel) {
            return $this->permissionModel;
        }

        return $this->permissionModel = new Permission();
    }

    /**
     * 菜单列表
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getList()
    {
        $menus = $this->getPermissionModel()->getMenuTreeList();

        return response()->json($menus->values());
    }

    /**
     * 保存菜单信息
     *
     * @param \Sco\Admin\Http\Requests\PermissionRequest $request 提交数据
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function save(PermissionRequest $request)
    {
        $this->getPermissionModel()->saveMenu($request);
        return response()->json(['message' => 'ok']);
    }

    /**
     * 删除菜单
     *
     * @param int $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete($id)
    {
        $this->getPermissionModel()->deleteMenu($id);
        return response()->json(['message' => 'ok']);
    }

    /**
     * 批量删除菜单
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function batchDelete(Request $request)
    {
        $this->getPermissionModel()->deleteMenu($request->input('ids'));
        return response()->json(['message' => 'ok']);
    }

}

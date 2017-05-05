<?php


namespace Sco\Admin\Http\Controllers\System;

use Illuminate\Http\Request;
use Sco\ActionLog\Factory;
use Sco\Admin\Http\Controllers\BaseController;

class ActionLogController extends BaseController
{
    public function getList(Request $request)
    {
        $ActionLog = new Factory();
        if ($request->has('user_id')) {
            $ActionLog = $ActionLog->whereUserId(intval($request->input('user_id')));
        }

        if ($request->has('client_ip')) {
            $ActionLog = $ActionLog->whereClientIp($request->input('client_ip'));
        }

        if ($request->has('type')) {
            $ActionLog = $ActionLog->whereType($request->input('type'));
        }

        $list = $ActionLog->with('user')->orderBy('created_at', 'desc')->paginate();

        return response()->json($list);
    }
}

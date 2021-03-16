<?php

namespace App\Http\Controllers;

use App\Support\Support;
use Illuminate\Http\JsonResponse;

class QueryController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt.auth');
    }

    /**
     * 移动端扫码查看设备配件软件详情.
     *
     * @param $string
     *
     * @return JsonResponse
     */
    public function query($string): JsonResponse
    {
        $item = explode(':', $string)[0];
        $id = explode(':', $string)[1];
        $item = Support::getItemRecordByClass($item, $id);
        optional($item->user)->department;
        $item->category;
        $item->vendor;
        $item->channel;
        $return = [
            'code'    => 200,
            'message' => '查询成功',
            'data'    => $item,
        ];

        return response()->json($return);
    }
}

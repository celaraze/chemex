<?php

namespace App\Http\Controllers;

use Ace\Uni;
use App\Models\CheckTrack;
use App\Models\DeviceRecord;
use App\Models\PartRecord;
use App\Models\SoftwareRecord;
use Illuminate\Http\JsonResponse;

class CheckController extends Controller
{
    /**
     * 盘点.
     *
     * @param string $asset_number
     * @param int $check_status
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function check(string $asset_number, int $check_status): array|JsonResponse
    {
        // 分割类型和编码
        [$type, $asset_number] = explode(':', $asset_number);
        switch ($type) {
            case 'part':
                $item = PartRecord::where('asset_number', $asset_number)->first();
                $class_name = get_class(new PartRecord());
                break;
            case 'software':
                $item = SoftwareRecord::where('asset_number', $asset_number)->first();
                $class_name = get_class(new SoftwareRecord());
                break;
            default:
                $item = DeviceRecord::where('asset_number', $asset_number)->first();
                $class_name = get_class(new DeviceRecord());
        }

        $check_track = CheckTrack::where('check_item', $class_name)
            ->where('item_id', $item->id)
            ->first();
        if (empty($item)) {
            return Uni::response(404, '无法查询到相关信息');
        }

        if (empty($check_track)) {
            return Uni::response(404, '该资产目前没有盘点计划');
        }

        //TODO 缺少JWT组件依赖，这里还没写完
        if ($check_track->checker != '') {
            return Uni::response(403, '该资产盘点任务不属于你');
        }

        if ($check_track->status != 0) {
            return Uni::response(201, '该资产已经盘点过了');
        }

        $check_track->status = $check_status;
        //TODO 缺少JWT组件依赖，这里还没写完
        $check_track->checker = '';
        $check_track->save();

        return Uni::response(200, '盘点操作完成');
    }
}

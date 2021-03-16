<?php

namespace App\Services;

use App\Models\DeviceRecord;
use Dcat\EasyExcel\Excel;

/**
 * 和导出相关的功能模块
 * Class ExportService.
 */
class ExportService
{
    /**
     * 设备履历导出.
     *
     * @param $device_id
     *
     * @return mixed
     */
    public static function deviceHistory($device_id)
    {
        $device = DeviceRecord::where('id', $device_id)->first();
        if (empty($device)) {
            $name = '未知';
        } else {
            $name = $device->name;
        }
        $history = DeviceService::history($device_id);

        return Excel::export($history)->download($name.'履历清单.xlsx');
    }
}

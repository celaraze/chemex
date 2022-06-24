<?php

namespace App\Services;

use App\Models\MaintenanceRecord;


/**
 * 和资产故障记录相关的功能服务
 * Class MaintenanceService.
 */
class MaintenanceService
{
    /**
     * 资产记录删除.
     *
     * @param $maintenance_id
     */
    public static function maintenanceDelete($maintenance_id)
    {
        $maintenance = MaintenanceRecord::where('id', $maintenance_id)->first();
        if (!empty($maintenance)) {
            $maintenance->delete();
        }
    }

    /**
     * 删除资产故障记录（强制）.
     *
     * @param $maintenance_id
     */
    public static function maintenanceForceDelete($maintenance_id)
    {
        $maintenance = MaintenanceRecord::where('id', $maintenance_id)
            ->withTrashed()
            ->first();
        if (!empty($maintenance)) {
            $maintenance->forceDelete();
        }
    }
}

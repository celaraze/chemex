<?php


namespace App\Services;


use App\Models\ServiceRecord;

class ServiceService
{
    /**
     * 配件删除.
     *
     * @param $service_id
     */
    public static function serviceDelete($service_id)
    {
        $service = ServiceRecord::where('id', $service_id)->first();
        if (!empty($service)) {
            $service->delete();
        }
    }

    /**
     * 删除配件（强制）.
     *
     * @param $service_id
     */
    public static function serviceForceDelete($service_id)
    {
        $service = ServiceRecord::where('id', $service_id)
            ->withTrashed()
            ->first();
        if (!empty($service)) {
            $service->forceDelete();
        }
    }
}

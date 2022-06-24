<?php

namespace App\Services;

use App\Models\DeviceRecord;
use App\Models\DeviceTrack;
use App\Models\PartTrack;
use App\Models\SoftwareTrack;
use App\Support\Support;

/**
 * 和设备记录相关的功能服务
 * Class DeviceService.
 */
class DeviceService
{
    /**
     * 获取设备的履历清单.
     *
     * @param $id
     *
     * @return array
     */
    public static function history($id): array
    {
        $data = [];

        $single = [
            'type' => '',
            'name' => '',
            'status' => '',
            'style' => '',
            'datetime' => '',
        ];

        // 处理设备使用者变动履历
        $device_tracks = DeviceTrack::withTrashed()
            ->where('device_id', $id)
            ->get();
        foreach ($device_tracks as $device_track) {
            $single['type'] = '用户';
            $user = $device_track->user()->withTrashed()->first();
            $username = $user->name;
            $department = $device_track->user()
                ->withTrashed()
                ->first()
                ->department()
                ->withTrashed()
                ->first();
            if (empty($department)) {
                $department = '无部门';
            } else {
                $department = $department->name;
            }
            $single['name'] = $username . ' - ' . $department;
            $data = Support::itemTrack($single, $device_track, $data);
        }

        // 处理设备配件变动履历
        $part_tracks = PartTrack::withTrashed()
            ->where('device_id', $id)
            ->get();
        foreach ($part_tracks as $part_track) {
            $single['type'] = trans('main.part');
            $part = $part_track->part()->withTrashed()->first();
            $single['name'] = $part->asset_number . ' - ' . $part->specification;
            $data = Support::itemTrack($single, $part_track, $data);
        }

        // 处理设备软件变动履历
        $software_tracks = SoftwareTrack::withTrashed()
            ->where('device_id', $id)
            ->get();
        foreach ($software_tracks as $software_track) {
            $single['type'] = trans('main.software');
            $software = $software_track->software()->withTrashed()->first();
            $single['name'] = $software->name . ' ' . $software->version;
            $data = Support::itemTrack($single, $software_track, $data);
        }

        $datetime = array_column($data, 'datetime');
        array_multisort($datetime, SORT_DESC, $data);

        return $data;
    }

    /**
     * 删除设备.
     *
     * @param $device_id
     */
    public static function deviceDelete($device_id)
    {
        $device_record = DeviceRecord::where('id', $device_id)->first();
        if (!empty($device_record)) {
            $device_record->delete();
        }
    }

    /**
     * 删除设备（强制）.
     *
     * @param $device_id
     */
    public static function deviceForceDelete($device_id)
    {
        $device_record = DeviceRecord::where('id', $device_id)
            ->withTrashed()
            ->first();
        if (!empty($device_record)) {
            $device_record->forceDelete();
        }
    }
}

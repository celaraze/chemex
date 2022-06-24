<?php

namespace App\Services;

use App\Models\PartRecord;
use App\Models\PartTrack;
use App\Support\Support;

/**
 * 和配件记录相关的功能服务
 * Class PartRecordService.
 */
class PartService
{
    /**
     * 获取配件的履历清单.
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

        $part_tracks = PartTrack::withTrashed()
            ->where('part_id', $id)
            ->get();

        foreach ($part_tracks as $part_track) {
            $single['type'] = '设备';
            $single['name'] = optional($part_track->device)->name;
            $data = Support::itemTrack($single, $part_track, $data);
        }

        $datetime = array_column($data, 'datetime');
        array_multisort($datetime, SORT_DESC, $data);

        return $data;
    }

    /**
     * 配件删除.
     *
     * @param $part_id
     */
    public static function partDelete($part_id)
    {
        $part = PartRecord::where('id', $part_id)->first();
        if (!empty($part)) {
            $part->delete();
        }
    }

    /**
     * 删除配件（强制）.
     *
     * @param $part_id
     */
    public static function partForceDelete($part_id)
    {
        $part_record = PartRecord::where('id', $part_id)
            ->withTrashed()
            ->first();
        if (!empty($part_record)) {
            $part_record->forceDelete();
        }
    }
}

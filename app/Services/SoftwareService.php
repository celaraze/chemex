<?php

namespace App\Services;

use App\Models\SoftwareRecord;
use App\Models\SoftwareTrack;
use App\Support\Support;
use Dcat\EasyExcel\Excel;

/**
 * 和软件记录相关的功能服务
 * Class SoftwareRecordService.
 */
class SoftwareService
{
    /**
     * 删除软件.
     *
     * @param $software_id
     */
    public static function deleteSoftware($software_id)
    {
        $software = SoftwareRecord::where('id', $software_id)->first();
        if (!empty($software)) {
            $software_tracks = SoftwareTrack::where('software_id', $software->id)
                ->get();

            foreach ($software_tracks as $software_track) {
                $software_track->delete();
            }

            $software->delete();
        }
    }

    /**
     * 软件履历导出.
     *
     * @param $software_id
     *
     * @return mixed
     */
    public static function exportHistory($software_id)
    {
        $software = SoftwareRecord::where('id', $software_id)->first();
        if (empty($software)) {
            $name = '未知';
        } else {
            $name = $software->name;
        }
        $history = SoftwareService::history($software_id);

        return Excel::export($history)->download($name.'履历清单.xlsx');
    }

    /**
     * 获取软件的履历清单.
     *
     * @param $id
     *
     * @return array
     */
    public static function history($id): array
    {
        $data = [];

        $single = [
            'type'     => '',
            'name'     => '',
            'status'   => '',
            'style'    => '',
            'datetime' => '',
        ];

        $software_tracks = SoftwareTrack::withTrashed()
            ->where('software_id', $id)
            ->get();

        foreach ($software_tracks as $software_track) {
            $single['type'] = '设备';
            $single['name'] = optional($software_track->device)->name;
            $data = Support::itemTrack($single, $software_track, $data);
        }

        $datetime = array_column($data, 'datetime');
        array_multisort($datetime, SORT_DESC, $data);

        return $data;
    }
}

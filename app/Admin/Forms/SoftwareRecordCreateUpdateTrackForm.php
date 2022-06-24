<?php

namespace App\Admin\Forms;

use App\Models\DeviceRecord;
use App\Models\SoftwareRecord;
use App\Models\SoftwareTrack;
use Dcat\Admin\Contracts\LazyRenderable;
use Dcat\Admin\Http\JsonResponse;
use Dcat\Admin\Traits\LazyWidget;
use Dcat\Admin\Widgets\Form;

class SoftwareRecordCreateUpdateTrackForm extends Form implements LazyRenderable
{
    use LazyWidget;

    /**
     * 处理表单提交逻辑.
     *
     * @param array $input
     *
     * @return JsonResponse
     */
    public function handle(array $input): JsonResponse
    {
        // 获取软件id
        $software_id = $this->payload['id'] ?? null;

        // 获取设备id，来自表单传参
        $device_id = $input['device_id'] ?? null;

        // 如果没有软件id或者设备id则返回错误
        if (!$software_id || !$device_id) {
            return $this->response()
                ->error(trans('main.parameter_missing'));
        }

        // 软件记录
        $software = SoftwareRecord::where('id', $software_id)->first();
        // 如果没有找到这个软件记录则返回错误
        if (!$software) {
            return $this->response()
                ->error(trans('main.record_none'));
        }

        // 设备记录
        $device = DeviceRecord::where('id', $device_id)->first();
        // 如果没有找到这个设备记录则返回错误
        if (!$device) {
            return $this->response()
                ->error(trans('main.record_none'));
        }

        // 软件追踪
        $software_track = SoftwareTrack::where('software_id', $software_id)
            ->first();

        // 如果软件授权数量为非无限制
        if ($software->counts != -1) {
            $software_tracks = SoftwareTrack::where('software_id', $software_id)
                ->get();
            $used = count($software_tracks);
            $diff = $software->counts - $used;
            if ($diff <= 0) {
                return $this->response()
                    ->error(trans('main.software_license_not_enough'));
            }
        }

        // 如果软件追踪非空，则删除旧追踪，为了留下流水记录
        if (!empty($software_track)) {
            // 如果新设备和旧设备相同，返回错误
            if ($software_track->device_id == $device_id) {
                return $this->response()
                    ->error(trans('main.no_change'));
            }
        }

        // 创建新的配件追踪
        $software_track = new SoftwareTrack();
        $software_track->software_id = $software_id;
        $software_track->device_id = $device_id;
        $software_track->save();

        return $this->response()
            ->success(trans('main.success'))
            ->refresh();
    }

    /**
     * 构造表单.
     */
    public function form()
    {
        $this->select('device_id', trans('main.device_id'))
            ->options(DeviceRecord::pluck('asset_number', 'id'))
            ->help(trans('main.software_record_create_update_track_device_id_help'))
            ->required();
    }
}

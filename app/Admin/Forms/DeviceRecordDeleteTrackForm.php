<?php

namespace App\Admin\Forms;

use App\Models\DeviceRecord;
use App\Models\DeviceTrack;
use Dcat\Admin\Contracts\LazyRenderable;
use Dcat\Admin\Http\JsonResponse;
use Dcat\Admin\Traits\LazyWidget;
use Dcat\Admin\Widgets\Form;

class DeviceRecordDeleteTrackForm extends Form implements LazyRenderable
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
        // 获取设备id
        $device_id = $this->payload['id'] ?? null;

        // 获取设备解除归属原因，来自表单传参
        $deleted_description = $input['deleted_description'] ?? null;

        // 如果没有设备id或者解除归属原因则返回错误
        if (!$device_id || !$deleted_description) {
            return $this->response()
                ->error(trans('main.parameter_missing'));
        }

        // 设备记录
        $device = DeviceRecord::where('id', $device_id)->first();
        // 如果没有找到这个设备记录则返回错误
        if (!$device) {
            return $this->response()
                ->error(trans('main.record_none'));
        }

        // 设备追踪
        $device_track = DeviceTrack::where('device_id', $device_id)->first();

        // 如果没有设备追踪
        if (empty($device_track)) {
            return $this->response()
                ->error(trans('main.record_none'));
        }

        $device_track->setAttribute('deleted_description', $deleted_description);
        $device_track->save();
        $device_track->delete();

        return $this->response()
            ->success(trans('main.success'))
            ->refresh();
    }

    /**
     * 构造表单.
     */
    public function form()
    {
        $this->text('deleted_description')
            ->required();
    }
}

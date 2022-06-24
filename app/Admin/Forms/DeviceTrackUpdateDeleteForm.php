<?php

namespace App\Admin\Forms;

use App\Models\DeviceTrack;
use Dcat\Admin\Contracts\LazyRenderable;
use Dcat\Admin\Http\JsonResponse;
use Dcat\Admin\Traits\LazyWidget;
use Dcat\Admin\Widgets\Form;

class DeviceTrackUpdateDeleteForm extends Form implements LazyRenderable
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
        $id = $this->payload['id'] ?? null;

        // 获取用户id，来自表单传参
        $return_time = $input['return_time'] ?? null;
        $return_description = $input['return_description'] ?? null;

        // 如果没有设备id或者归还时间或者归还描述则返回错误
        if (!$id || !$return_time || !$return_description) {
            return $this->response()
                ->error(trans('main.parameter_missing'));
        }

        // 设备追踪
        $device_track = DeviceTrack::where('id', $id)->first();

        $device_track->return_time = $return_time;
        $device_track->return_description = $return_description;

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
        $this->datetime('return_time', trans('main.return_time'))
            ->required();
        $this->textarea('return_description', trans('main.return_description'))
            ->required();
    }
}

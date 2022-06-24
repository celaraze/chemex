<?php

namespace App\Admin\Forms;

use App\Models\DeviceRecord;
use App\Models\ServiceRecord;
use App\Models\ServiceTrack;
use Dcat\Admin\Contracts\LazyRenderable;
use Dcat\Admin\Http\JsonResponse;
use Dcat\Admin\Traits\LazyWidget;
use Dcat\Admin\Widgets\Form;

class ServiceRecordCreateUpdateTrackForm extends Form implements LazyRenderable
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
        // 获取服务id
        $service_id = $this->payload['id'] ?? null;

        // 获取设备id，来自表单传参
        $device_id = $input['device_id'] ?? null;

        // 如果没有服务id或者设备id则返回错误
        if (!$service_id || !$device_id) {
            return $this->response()
                ->error(trans('main.parameter_missing'));
        }

        // 服务记录
        $service = ServiceRecord::where('id', $service_id)->first();
        // 如果没有找到这个服务记录则返回错误
        if (!$service) {
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

        // 服务追踪
        $service_track = ServiceTrack::where('service_id', $service_id)
            ->first();

        // 如果配件追踪非空，则删除旧追踪，为了留下流水记录
        if (!empty($service_track)) {
            // 如果新设备和旧设备相同，返回错误
            if ($service_track->device_id == $device_id) {
                return $this->response()
                    ->error(trans('main.no_change'));
            } else {
                $service_track->delete();
            }
        }

        // 创建新的配件追踪
        $service_track = new ServiceTrack();
        $service_track->service_id = $service_id;
        $service_track->device_id = $device_id;
        $service_track->save();

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
            ->help(trans('main.service_record_create_update_track_device_id_help'))
            ->required();
    }
}

<?php

namespace App\Admin\Forms;

use App\Models\MaintenanceRecord;
use Dcat\Admin\Contracts\LazyRenderable;
use Dcat\Admin\Http\JsonResponse;
use Dcat\Admin\Traits\LazyWidget;
use Dcat\Admin\Widgets\Form;

class MaintenanceRecordUpdateForm extends Form implements LazyRenderable
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
        // 获取物品id
        $id = $this->payload['id'] ?? null;

        // 获取故障说明，来自表单传参
        $ok_description = $input['ok_description'] ?? null;

        // 获取故障时间，来自表单传参
        $ok_time = $input['ok_time'] ?? null;

        // 获取故障时间，来自表单传参
        $status = $input['status'] ?? null;

        // 如果没有物品、物品id、故障说明、故障时间则返回错误
        if (!$id || !$ok_description || !$ok_time || !$status) {
            return $this->response()
                ->error(trans('main.parameter_missing'));
        }

        $maintenance_record = MaintenanceRecord::where('id', $id)->first();

        // 如果没有找到这个物品记录则返回错误
        if (!$maintenance_record) {
            return $this->response()
                ->error(trans('main.record_none'));
        }

        // 创建新的配件追踪
        $maintenance_record->ok_description = $ok_description;
        $maintenance_record->ok_time = $ok_time;
        $maintenance_record->status = $status;
        $maintenance_record->save();

        return $this->response()
            ->success(trans('success'))
            ->refresh();
    }

    /**
     * 构造表单.
     */
    public function form()
    {
        $this->text('ok_description', trans('main.maintenance_record_ok_description'))->required();
        $this->datetime('ok_time', trans('main.maintenance_record_ok_time'))->required();
        $this->select('status', trans('main.maintenance_record_status'))->options([
            1 => trans('main.maintenance_update_done'),
            2 => trans('main.maintenance_update_cancelled'),
        ])->required();
    }
}

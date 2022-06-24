<?php

namespace App\Admin\Forms;

use App\Models\MaintenanceRecord;
use Dcat\Admin\Contracts\LazyRenderable;
use Dcat\Admin\Http\JsonResponse;
use Dcat\Admin\Traits\LazyWidget;
use Dcat\Admin\Widgets\Form;

class MaintenanceRecordCreateForm extends Form implements LazyRenderable
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
        // 获取物品类型
        $asset_number = $this->payload['asset_number'] ?? null;

        // 获取故障说明，来自表单传参
        $ng_description = $input['ng_description'] ?? null;

        // 获取故障时间，来自表单传参
        $ng_time = $input['ng_time'] ?? null;

        // 如果没有资产编号、故障说明、故障时间则返回错误
        if (!$asset_number || !$ng_description || !$ng_time) {
            return $this->response()
                ->error(trans('main.parameter_missing'));
        }

        // 创建新的配件追踪
        $maintenance_record = new MaintenanceRecord();
        $maintenance_record->asset_number = $asset_number;
        $maintenance_record->ng_description = $ng_description;
        $maintenance_record->ng_time = $ng_time;
        $maintenance_record->status = 0;
        $maintenance_record->save();

        return $this->response()
            ->success(trans('main.success'))
            ->refresh();
    }

    /**
     * 构造表单.
     */
    public function form()
    {
        $this->text('ng_description', trans('main.ng_description'))->required();
        $this->datetime('ng_time', trans('main.ng_time'))->required();
    }
}

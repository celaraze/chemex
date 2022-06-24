<?php

namespace App\Admin\Forms;

use App\Models\ApprovalRecord;
use App\Models\DeviceRecord;
use App\Services\ApprovalService;
use Dcat\Admin\Contracts\LazyRenderable;
use Dcat\Admin\Http\JsonResponse;
use Dcat\Admin\Traits\LazyWidget;
use Dcat\Admin\Widgets\Form;

class DeviceRecordDeleteForm extends Form implements LazyRenderable
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
        $device_record_id = $this->payload['id'] ?? null;
        $approval_id = $input['approval_id'] ?? null;
        $description = $input['description'] ?? null;
        if (!$device_record_id || !$approval_id) {
            return $this->response()
                ->error(trans('main.parameter_missing'));
        }

        $approval_service = new ApprovalService($approval_id, DeviceRecord::find($device_record_id));
        if ($description) {
            $approval_service->setDescription($description);
        }
        $approval_service->go();

        return $this->response()
            ->success(trans('main.success'))
            ->refresh();
    }

    /**
     * 构造表单.
     */
    public function form()
    {
        $this->select('approval_id')
            ->options(ApprovalRecord::pluck('name', 'id'))
            ->required();
        $this->textarea('description');
    }
}

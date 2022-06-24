<?php

namespace App\Admin\Forms;

use App\Models\ServiceIssue;
use App\Models\ServiceRecord;
use Dcat\Admin\Contracts\LazyRenderable;
use Dcat\Admin\Http\JsonResponse;
use Dcat\Admin\Traits\LazyWidget;
use Dcat\Admin\Widgets\Form;

class ServiceIssueCreateForm extends Form implements LazyRenderable
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

        // 获取异常，来自表单传参
        $issue = $input['issue'] ?? null;

        // 获取开始时间，来自表单传参
        $start = $input['start'] ?? null;

        // 如果没有服务id或者设备id则返回错误
        if (!$service_id || !$issue || !$start) {
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

        $service_issue = new ServiceIssue();
        $service_issue->service_id = $service_id;
        $service_issue->issue = $issue;
        $service_issue->status = 1;
        $service_issue->start = $start;
        $service_issue->save();

        return $this->response()
            ->success(trans('main.success'))
            ->refresh();
    }

    /**
     * 构造表单.
     */
    public function form()
    {
        $this->text('issue', trans('main.service_issue'))
            ->required();
        $this->datetime('start', trans('main.service_issue_start'))
            ->required();
    }
}

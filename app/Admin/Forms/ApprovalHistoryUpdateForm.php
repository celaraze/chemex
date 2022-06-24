<?php

namespace App\Admin\Forms;

use App\Services\ApprovalService;
use Dcat\Admin\Http\JsonResponse;
use Dcat\Admin\Traits\LazyWidget;
use Dcat\Admin\Widgets\Form;
use Exception;
use Illuminate\Contracts\Support\Renderable;

class ApprovalHistoryUpdateForm extends Form implements Renderable
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
        $id = $this->payload['id'] ?? null;
        $approval_id = $this->payload['approval_id'] ?? null;
        $item = $this->payload['item'] ?? null;
        $type = $input['type'] ?? null;
        $description = $input['description'] ?? null;

        if (empty($id) || empty($approval_id) || empty($item) || empty($type)) {
            return $this->response()
                ->error(trans('main.parameter_missing'));
        }

        try {
            $approval_service = new ApprovalService($approval_id, new $item);
            $approval_service->setDescription($description);
            $approval_service->go();

            return $this->response()
                ->success(trans('main.success'))
                ->refresh();
        } catch (Exception $exception) {
            return $this->response()
                ->error(trans('main.fail') . ' : ' . $exception->getMessage());
        }
    }

    /**
     * 构造表单.
     */
    public function form()
    {
        $this->radio('type')
            ->options([
                'grant' => '通过',
                'refuse' => '退签',
                'abort' => '驳回'
            ])->default('grant');
        $this->textarea('description');
    }
}

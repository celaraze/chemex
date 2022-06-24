<?php

namespace App\Admin\Forms;

use App\Models\ApprovalTrack;
use App\Models\Role;
use Dcat\Admin\Http\JsonResponse;
use Dcat\Admin\Traits\LazyWidget;
use Dcat\Admin\Widgets\Form;
use Exception;
use Illuminate\Contracts\Support\Renderable;

class ApprovalRecordCreateTrackForm extends Form implements Renderable
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
        $role = $input['role'] ?? null;
        $order = $input['order'] ?? null;

        if (!$id || !$role || !$order) {
            return $this->response()
                ->error(trans('main.parameter_missing'));
        }

        try {
            $approval_track = new ApprovalTrack();
            $approval_track->approval_id = $id;
            $approval_track->role = $role;
            $approval_track->order = $order;
            $approval_track->save();
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
        $this->select('role')
            ->options(Role::pluck('name', 'id'))
            ->required();
        $this->number('order')
            ->default(99)
            ->required();
    }
}

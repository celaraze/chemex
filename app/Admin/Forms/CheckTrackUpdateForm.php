<?php

namespace App\Admin\Forms;

use App\Models\CheckTrack;
use Dcat\Admin\Admin;
use Dcat\Admin\Contracts\LazyRenderable;
use Dcat\Admin\Http\JsonResponse;
use Dcat\Admin\Traits\LazyWidget;
use Dcat\Admin\Widgets\Form;

class CheckTrackUpdateForm extends Form implements LazyRenderable
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
        // 获取盘点id
        $track_id = $this->payload['id'] ?? null;

        // 获取盘点状态
        $status = $input['status'] ?? null;

        // 获取盘点说明
        $description = $input['description'] ?? null;

        // 如果没有盘点id返回错误
        if (!$track_id || !$status) {
            return $this->response()
                ->error(trans('main.parameter_missing'));
        }

        $check_track = CheckTrack::where('id', $track_id)->first();
        if (empty($check_track)) {
            return $this->response()
                ->error(trans('main.check_track_none'));
        } else {
            $check_track->status = $status;
            $check_track->description = $description;
            $check_track->checker = Admin::user()->id;
            $check_track->save();
        }

        return $this->response()
            ->success(trans('main.check_track_success'))
            ->refresh();
    }

    /**
     * 构造表单.
     */
    public function form()
    {
        $this->radio('status', trans('main.check_status'))
            ->options([1 => trans('main.check_yes'), 2 => trans('main.check_no')])
            ->default(1)
            ->required();
        $this->textarea('description', trans('main.description'));
    }
}

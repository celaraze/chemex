<?php

namespace App\Admin\Forms;

use App\Models\DeviceRecord;
use App\Models\DeviceTrack;
use App\Models\User;
use App\Support\Support;
use Dcat\Admin\Contracts\LazyRenderable;
use Dcat\Admin\Http\JsonResponse;
use Dcat\Admin\Traits\LazyWidget;
use Dcat\Admin\Widgets\Form;

class DeviceRecordCreateUpdateTrackForm extends Form implements LazyRenderable
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
        $is_lend = $this->payload['is_lend'];

        // 获取用户id，来自表单传参
        $user_id = $input['user_id'] ?? null;
        $type = $input['type'] ?? null;
        $lend_user_id = $input['lend_user_id'] ?? null;
        $lend_time = $input['lend_time'] ?? null;
        $lend_description = $input['lend_description'] ?? null;
        $plan_return_time = $input['plan_return_time'] ?? null;

        // 如果没有设备id或者用户id则返回错误
        if ($type == 'track') {
            if (!$device_id || !$user_id) {
                return $this->response()
                    ->error(trans('main.parameter_missing'));
            }
        }

        if ($type == 'lend') {
            if (!$lend_user_id || !$lend_time || !$lend_description || !$plan_return_time) {
                return $this->response()
                    ->error(trans('main.parameter_missing'));
            }
            $user_id = $lend_user_id;
        }

        // 设备记录
        $device = DeviceRecord::where('id', $device_id)->first();
        // 如果没有找到这个设备记录则返回错误
        if (!$device) {
            return $this->response()
                ->error(trans('main.record_none'));
        }
        /*
         * 设备未归还，则不允许进行分配操作，包括分配归属和借出
         * 需要设备归还后可以继续执行
         * 在grid按钮处和弹窗表单虽然也做了卡关，此处是为了防止奇怪的请求绕过
         */
        if ($is_lend) {
            return $this->response()
                ->error(trans('main.item_is_lending'));
        }

        // 用户记录
        $user = User::where('id', $user_id)->first();
        // 如果没有找到这个用户记录则返回错误
        if (!$user) {
            return $this->response()
                ->error(trans('main.record_none'));
        }

        // 设备追踪
        $device_track = DeviceTrack::where('device_id', $device_id)->first();

        // 如果设备追踪非空，则删除旧追踪，为了留下流水记录
        if (!empty($device_track)) {
            // 如果新使用者和旧使用者相同，返回错误
            if ($device_track->user_id == $user_id) {
                return $this->response()
                    ->error(trans('main.record_same'));
            } else {
                $device_track->delete();
            }
        }

        // 创建新的设备追踪
        $device_track = new DeviceTrack();
        $device_track->device_id = $device_id;
        $device_track->user_id = $user_id;

        if ($type == 'lend') {
            $device_track->user_id = $lend_user_id;
            $device_track->lend_time = $lend_time;
            $device_track->lend_description = $lend_description;
            $device_track->plan_return_time = $plan_return_time;
        }

        $device_track->save();

        return $this->response()
            ->success(trans('main.success'))
            ->refresh();
    }

    /**
     * 构造表单.
     */
    public function form()
    {
        /*
         * 设备未归还，则不允许进行分配操作，包括分配归属和借出
         * 需要设备归还后可以继续执行
         * 在grid按钮处虽然也做了卡关，此处是为了防止奇怪的页面渗透
         */
        if ($this->payload['is_lend']) {
            $this->html(trans('main.item_is_lending'));
            $this->disableResetButton();
            $this->disableSubmitButton();
        } else {
            $users = User::pluck('name', 'id');
            $this->radio('type')
                ->when('track', function (Form $form) use ($users) {
                    if (Support::ifSelectCreate()) {
                        $form->selectCreate('user_id', trans('main.new_user_id'))
                            ->options(User::class)
                            ->ajax(admin_route('selection.organization.users'))
                            ->url(admin_route('organization.users.create'))
                            ->help(trans('main.user_id_help'));
                    } else {
                        $form->select('user_id', trans('main.new_user_id'))
                            ->options($users)
                            ->help(trans('main.user_id_help'));
                    }
                })
                ->when('lend', function (Form $form) use ($users) {
                    if (Support::ifSelectCreate()) {
                        $form->selectCreate('lend_user_id', trans('main.new_user_id'))
                            ->options(User::class)
                            ->ajax(admin_route('selection.organization.users'))
                            ->url(admin_route('organization.users.create'))
                            ->help(trans('main.user_id_help'));
                    } else {
                        $form->select('lend_user_id', trans('main.new_user_id'))
                            ->options($users)
                            ->help(trans('main.user_id_help'));
                    }
                    $form->datetime('lend_time', trans('main.lend_time'));
                    $form->textarea('lend_description', trans('main.lend_description'));
                    $form->datetime('plan_return_time', trans('main.plan_return_time'));
                })
                ->options(['track' => trans('main.track'), 'lend' => trans('main.lend')])
                ->default('track');
        }
    }
}

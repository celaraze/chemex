<?php

namespace App\Admin\Actions\Grid\RowAction;

use App\Models\CheckRecord;
use App\Models\CheckTrack;
use App\Services\NotificationService;
use Dcat\Admin\Actions\Response;
use Dcat\Admin\Grid\RowAction;

class CheckRecordUpdateYesAction extends RowAction
{
    public function __construct()
    {
        parent::__construct();
        $this->title = '<i class="fa fa-fw feather icon-check"></i> ' . admin_trans_label('Finish Record');
    }

    /**
     * 处理动作逻辑.
     *
     * @return Response
     */
    public function handle(): Response
    {
        $check_track = CheckTrack::where('status', 0)->where('check_id', $this->getKey())->first();
        if (empty($check_track)) {
            $check_record = CheckRecord::where('id', $this->getKey())->firstOrFail();
            if ($check_record->status == 1) {
                return $this->response()
                    ->warning(trans('main.check_record_fail_done'));
            }
            if ($check_record->status == 2) {
                return $this->response()
                    ->warning(trans('main.check_record_fail_cancelled'));
            }

            NotificationService::deleteNotificationWhenCheckFinishedOrCancelled($this->getKey());

            $check_record->status = 1;
            $check_record->save();

            return $this->response()
                ->success(trans('main.success'))
                ->refresh();
        } else {
            return $this->response()
                ->error(trans('main.check_record_fail_left'));
        }
    }

    /**
     * 对话框.
     *
     * @return string[]
     */
    public function confirm(): array
    {
        return [admin_trans_label('Finish Confirm'), admin_trans_label('Finish Confirm Description')];
    }
}

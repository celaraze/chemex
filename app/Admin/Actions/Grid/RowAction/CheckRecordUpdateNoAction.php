<?php

namespace App\Admin\Actions\Grid\RowAction;

use App\Models\CheckRecord;
use App\Models\CheckTrack;
use App\Services\NotificationService;
use Dcat\Admin\Actions\Response;
use Dcat\Admin\Grid\RowAction;

class CheckRecordUpdateNoAction extends RowAction
{
    public function __construct()
    {
        parent::__construct();
        $this->title = '<i class="fa fa-fw feather icon-x"></i> ' . admin_trans_label('Cancel Record');
    }

    /**
     * 处理动作逻辑.
     *
     * @return Response
     */
    public function handle(): Response
    {
        $check_tracks = CheckTrack::where('check_id', $this->getKey())->get();
        foreach ($check_tracks as $check_track) {
            $check_track->delete();
        }
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

        $check_record->status = 2;
        $check_record->save();

        return $this->response()
            ->success(trans('main.success'))
            ->refresh();
    }

    /**
     * 对话框.
     *
     * @return string[]
     */
    public function confirm(): array
    {
        return [admin_trans_label('Cancel Confirm'), admin_trans_label('Cancel Confirm Description')];
    }
}

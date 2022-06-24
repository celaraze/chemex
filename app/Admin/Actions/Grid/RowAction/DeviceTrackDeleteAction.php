<?php

namespace App\Admin\Actions\Grid\RowAction;

use App\Models\DeviceTrack;
use Dcat\Admin\Actions\Response;
use Dcat\Admin\Grid\RowAction;

class DeviceTrackDeleteAction extends RowAction
{
    public function __construct()
    {
        parent::__construct();
        $this->title = '<i class="fa fa-fw feather icon-user-minus"></i> ' . admin_trans_label('Delete');
    }

    /**
     * 处理动作逻辑.
     *
     * @return Response
     */
    public function handle(): Response
    {
        $device_track = DeviceTrack::where('id', $this->getKey())->first();

        if (empty($device_track)) {
            return $this->response()
                ->error(trans('main.fail'));
        }

        $device_track->delete();

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
        return [admin_trans_label('Delete Confirm')];
    }
}

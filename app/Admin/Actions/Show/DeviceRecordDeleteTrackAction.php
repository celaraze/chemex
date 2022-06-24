<?php

namespace App\Admin\Actions\Show;

use App\Models\DeviceTrack;
use Dcat\Admin\Actions\Response;
use Dcat\Admin\Show\AbstractTool;

class DeviceRecordDeleteTrackAction extends AbstractTool
{
    public function __construct()
    {
        parent::__construct();
        $this->title = '<i class="fa fa-fw feather icon-trash"></i> ' . admin_trans_label('Track Delete');
    }

    /**
     * Handle the action request.
     *
     * @return Response
     */
    public function handle(): Response
    {
        $device_track = DeviceTrack::where('device_id', $this->getKey())->first();

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
     * @return array
     */
    public function confirm(): array
    {
        return [admin_trans_label('Track Delete Confirm')];
    }
}

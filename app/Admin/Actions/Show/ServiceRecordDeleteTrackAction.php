<?php

namespace App\Admin\Actions\Show;

use App\Models\ServiceTrack;
use Dcat\Admin\Actions\Response;
use Dcat\Admin\Show\AbstractTool;

class ServiceRecordDeleteTrackAction extends AbstractTool
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
        $service_track = ServiceTrack::where('service_id', $this->getKey())->first();

        if (empty($service_track)) {
            return $this->response()
                ->error(trans('main.fail'));
        }

        $service_track->delete();

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

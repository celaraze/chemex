<?php

namespace App\Admin\Actions\Grid\RowAction;

use App\Models\ServiceTrack;
use Dcat\Admin\Actions\Response;
use Dcat\Admin\Grid\RowAction;

class ServiceTrackDeleteAction extends RowAction
{
    public function __construct()
    {
        parent::__construct();
        $this->title = '<i class="fa fa-fw feather icon-trash"></i> ' . admin_trans_label('Delete');
    }

    /**
     * 处理动作逻辑.
     *
     * @return Response
     */
    public function handle(): Response
    {
        $service_track = ServiceTrack::where('id', $this->getKey())->first();

        if (empty($service_track)) {
            return $this->response()
                ->error(trans('main.record_none'));
        }

        $service_track->delete();

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

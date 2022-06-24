<?php

namespace App\Admin\Actions\Grid\RowAction;

use App\Models\PartTrack;
use Dcat\Admin\Actions\Response;
use Dcat\Admin\Grid\RowAction;

class PartTrackDeleteAction extends RowAction
{
    public function __construct()
    {
        parent::__construct();
        $this->title = '<i class="fa fa-fw feather icon-link-2"></i> ' . admin_trans_label('Delete');
    }

    /**
     * 处理动作逻辑.
     *
     * @return Response
     */
    public function handle(): Response
    {
        $part_track = PartTrack::where('id', $this->getKey())->first();

        if (empty($part_track)) {
            return $this->response()
                ->error(trans('main.record_none'));
        }

        $part_track->delete();

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

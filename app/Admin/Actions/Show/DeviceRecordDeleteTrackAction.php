<?php

namespace App\Admin\Actions\Show;

use App\Admin\Forms\DeviceRecordDeleteTrackForm;
use App\Models\DeviceTrack;
use Dcat\Admin\Actions\Response;
use Dcat\Admin\Show\AbstractTool;
use Dcat\Admin\Widgets\Modal;

class DeviceRecordDeleteTrackAction extends AbstractTool
{
    public function __construct()
    {
        parent::__construct();
        $this->title = '<a class="btn btn-sm btn-primary" style="color: white;"><i class="fa fa-fw feather icon-trash"></i> ' . admin_trans_label('Track Delete') . '</a>';
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
     * 渲染模态框.
     *
     * @return Modal|string
     */
    public function render(): string|Modal
    {
        // 实例化表单类并传递自定义参数
        $form = DeviceRecordDeleteTrackForm::make()->payload([
            'id' => $this->getKey(),
        ]);

        return Modal::make()
            ->lg()
            ->title(admin_trans_label('Track Delete'))
            ->body($form)
            ->button($this->title);
    }
}

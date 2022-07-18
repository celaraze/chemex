<?php

namespace App\Admin\Actions\Grid\BatchAction;

use App\Services\DeviceService;
use Dcat\Admin\Grid\BatchAction;

class DeviceRecordBatchDiscardAction extends BatchAction
{
    public function __construct()
    {
        parent::__construct();
        $this->title = $this->title = '<i class="fa fa-fw feather icon-alert-triangle"></i> ' . admin_trans_label('Batch Discard');
    }

    /**
     * 确认弹窗.
     *
     * @return string
     */
    public function confirm(): string
    {
        return admin_trans_label('Batch Discard Confirm');
    }

    /**
     * 处理逻辑.
     *
     * @return \Dcat\Admin\Actions\Response|void
     */
    public function handle()
    {
        $keys = $this->getKey();

        foreach ($keys as $key) {
            DeviceService::deviceDiscard($key);
        }

        return $this->response()
            ->success(trans('main.success'))
            ->refresh();
    }
}

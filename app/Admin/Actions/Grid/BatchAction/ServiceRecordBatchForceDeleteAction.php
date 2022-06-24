<?php

namespace App\Admin\Actions\Grid\BatchAction;

use App\Services\ServiceService;
use Dcat\Admin\Actions\Response;
use Dcat\Admin\Grid\BatchAction;

class ServiceRecordBatchForceDeleteAction extends BatchAction
{
    public function __construct()
    {
        parent::__construct();
        $this->title = '<i class="fa fa-fw feather icon-alert-octagon"></i> ' . admin_trans_label('Batch Force Delete');
    }

    /**
     * 确认弹窗.
     *
     * @return string
     */
    public function confirm(): string
    {
        return admin_trans_label('Batch Force Delete Confirm');
    }

    /**
     * 处理逻辑.
     *
     * @return \Dcat\Admin\Actions\Response
     */
    public function handle(): Response
    {
        $keys = $this->getKey();

        foreach ($keys as $key) {
            ServiceService::serviceForceDelete($key);
        }

        return $this->response()
            ->success(trans('main.success'))
            ->refresh();
    }
}

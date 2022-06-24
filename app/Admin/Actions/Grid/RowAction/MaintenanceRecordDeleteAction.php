<?php

namespace App\Admin\Actions\Grid\RowAction;

use App\Services\MaintenanceService;
use Dcat\Admin\Actions\Response;
use Dcat\Admin\Grid\RowAction;

class MaintenanceRecordDeleteAction extends RowAction
{
    public function __construct()
    {
        parent::__construct();
        $this->title = '<i class="fa fa-fw feather icon-trash"></i> ' . admin_trans_label('删除记录');
    }

    /**
     * 处理动作逻辑.
     *
     * @return Response
     */
    public function handle(): Response
    {
        MaintenanceService::maintenanceDelete($this->getKey());

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
        return [admin_trans_label('确定是否删除'), admin_trans_label('记录将软删除')];
    }
}

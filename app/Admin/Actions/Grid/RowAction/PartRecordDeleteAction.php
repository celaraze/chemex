<?php

namespace App\Admin\Actions\Grid\RowAction;

use App\Services\PartService;
use Dcat\Admin\Actions\Response;
use Dcat\Admin\Grid\RowAction;

class PartRecordDeleteAction extends RowAction
{
    public function __construct()
    {
        parent::__construct();
        $this->title = '🔨 '.admin_trans_label('Delete');
    }

    /**
     * 处理动作逻辑.
     *
     * @return Response
     */
    public function handle(): Response
    {
        PartService::partDelete($this->getKey());

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
        return [admin_trans_label('Delete Confirm'), admin_trans_label('Delete Confirm Description')];
    }
}

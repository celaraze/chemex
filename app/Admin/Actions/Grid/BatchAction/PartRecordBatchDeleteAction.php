<?php

namespace App\Admin\Actions\Grid\BatchAction;

use App\Services\PartService;
use Dcat\Admin\Actions\Response;
use Dcat\Admin\Grid\BatchAction;

class PartRecordBatchDeleteAction extends BatchAction
{
    public function __construct($title = null)
    {
        parent::__construct($title);
        $this->title = '🔨 '.admin_trans_label('Batch Delete');
    }

    public function confirm(): string
    {
        return admin_trans_label('Batch Delete Confirm');
    }

    public function handle(): Response
    {
        $keys = $this->getKey();

        foreach ($keys as $key) {
            PartService::partDelete($key);
        }

        return $this->response()->success(trans('main.success'))->refresh();
    }
}

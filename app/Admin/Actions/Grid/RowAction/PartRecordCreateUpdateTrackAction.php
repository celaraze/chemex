<?php

namespace App\Admin\Actions\Grid\RowAction;

use App\Admin\Forms\PartRecordCreateUpdateTrackForm;
use Dcat\Admin\Grid\RowAction;
use Dcat\Admin\Widgets\Modal;

class PartRecordCreateUpdateTrackAction extends RowAction
{
    public function __construct()
    {
        parent::__construct();
        $this->title = '<i class="fa fa-fw feather icon-monitor"></i> ' . admin_trans_label('Track Create Update');
    }

    /**
     * 渲染模态框.
     *
     * @return Modal
     */
    public function render(): Modal
    {
        $form = PartRecordCreateUpdateTrackForm::make()->payload(['id' => $this->getKey()]);

        return Modal::make()
            ->lg()
            ->title(admin_trans_label('Track Create Update'))
            ->body($form)
            ->button($this->title);
    }
}

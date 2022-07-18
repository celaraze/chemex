<?php

namespace App\Admin\Actions\Grid\RowAction;

use App\Admin\Forms\DeviceTrackUpdateDeleteForm;
use Dcat\Admin\Grid\RowAction;
use Dcat\Admin\Widgets\Modal;

class DeviceTrackUpdateDeleteAction extends RowAction
{
    public function __construct()
    {
        parent::__construct();
        $this->title = '<i class="fa fa-fw feather icon-download"></i> ' . admin_trans_label('Update Delete');
    }

    /**
     * 渲染模态框.
     *
     * @return Modal
     */
    public function render(): Modal
    {
        $form = DeviceTrackUpdateDeleteForm::make()->payload([
            'id' => $this->getKey(),
        ]);

        return Modal::make()
            ->lg()
            ->title(admin_trans_label('Update Delete'))
            ->body($form)
            ->button($this->title);
    }
}

<?php

namespace App\Admin\Actions\Grid\RowAction;

use App\Admin\Forms\MaintenanceRecordCreateForm;
use Dcat\Admin\Grid\RowAction;
use Dcat\Admin\Widgets\Modal;


class MaintenanceRecordCreateAction extends RowAction
{
    protected ?string $asset_number = null;

    public function __construct($asset_number)
    {
        parent::__construct();
        $this->title = '<i class="fa fa-fw feather icon-box"></i> ' . admin_trans_label('Maintenance Create');
        $this->asset_number = $asset_number;
    }

    /**
     * 渲染模态框.
     *
     * @return Modal
     */
    public function render(): Modal
    {
        $form = MaintenanceRecordCreateForm::make()->payload([
            'asset_number' => $this->asset_number
        ]);

        return Modal::make()
            ->lg()
            ->title(admin_trans_label('Maintenance Create'))
            ->body($form)
            ->button($this->title);
    }
}

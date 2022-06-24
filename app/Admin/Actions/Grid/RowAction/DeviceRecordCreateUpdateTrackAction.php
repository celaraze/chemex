<?php

namespace App\Admin\Actions\Grid\RowAction;

use App\Admin\Forms\DeviceRecordCreateUpdateTrackForm;
use Dcat\Admin\Grid\RowAction;
use Dcat\Admin\Widgets\Modal;

class DeviceRecordCreateUpdateTrackAction extends RowAction
{
    protected bool $is_lend;

    public function __construct($is_lend)
    {
        parent::__construct();
        $this->title = '<i class="fa fa-fw feather icon-user-plus"></i> ' . admin_trans_label('Track Create Update');
        $this->is_lend = $is_lend;
    }

    /**
     * 渲染模态框.
     *
     * @return Modal|string
     */
    public function render()
    {
        // 实例化表单类并传递自定义参数
        $form = DeviceRecordCreateUpdateTrackForm::make()->payload([
            'id' => $this->getKey(),
            'is_lend' => $this->is_lend,
        ]);

        return Modal::make()
            ->lg()
            ->title(admin_trans_label('Track Create Update'))
            ->body($form)
            ->button($this->title);
    }
}

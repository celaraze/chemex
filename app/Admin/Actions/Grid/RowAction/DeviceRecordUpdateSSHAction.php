<?php

namespace App\Admin\Actions\Grid\RowAction;

use App\Admin\Forms\DeviceSSHInfoForm;
use Dcat\Admin\Grid\RowAction;
use Dcat\Admin\Widgets\Modal;

class DeviceRecordUpdateSSHAction extends RowAction
{
    public function __construct()
    {
        parent::__construct();
        $this->title = '<i class="fa fa-fw feather icon-clipboard"></i> ' . admin_trans_label('Update SSH');
    }

    /**
     * 渲染模态框.
     *
     * @return Modal
     */
    public function render(): Modal
    {
        // 实例化表单类并传递自定义参数
        $form = DeviceSSHInfoForm::make()->payload(['id' => $this->getKey()]);

        return Modal::make()
            ->lg()
            ->title(admin_trans_label('Update SSH'))
            ->body($form)
            ->button($this->title);
    }
}

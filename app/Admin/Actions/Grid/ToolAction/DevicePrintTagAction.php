<?php

namespace App\Admin\Actions\Grid\ToolAction;

use App\Admin\Forms\DeviceRecordTagPrintForm as DeviceRecordTagPrintForm;
use Dcat\Admin\Grid\BatchAction;
use Dcat\Admin\Widgets\Modal;

class DevicePrintTagAction extends BatchAction
{
    /**
     * 渲染模态框.
     */
    public function render()
    {
        $modalTitle = admin_trans_label('Tag Print Mode');
        $buttonTitle = admin_trans_label('Tag Print');

        $form = DeviceRecordTagPrintForm::make();//->payload(['id' => $this->getSelectedKeysScript()]);

        return Modal::make()
            ->lg()
            ->title($modalTitle)
            ->body($form)
            ->onLoad($this->getModalScript())
            ->button("<a class='btn btn-primary' style='color: white;'><i class='feather icon-tag'></i>&nbsp;$buttonTitle</a>");
    }

    protected function getModalScript()
    {
        // 弹窗显示后往隐藏的id表单中写入批量选中的行ID
        return <<<JS
        // 获取选中的ID数组
        var key = {$this->getSelectedKeysScript()};
        $('#check-Device-ids').val(key);
        JS;
    }
}

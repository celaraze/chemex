<?php

namespace App\Admin\Actions\Grid\ToolAction;

use Dcat\Admin\Grid\BatchAction;

class DevicePrintTagAction extends BatchAction
{
    protected $title = '打印标签';

    /**
     * Handle the action request.
     *
     * @return \Dcat\Admin\Actions\Response|void
     */
    public function handle()
    {
        $keys = $this->getkey();
        $url = admin_route("device.print.tag", ["ids" => implode("-", $keys)]);
        if (count($keys) > 0) {
            return $this->response()->script("window.open('{$url}')");
        }
    }

    public function actionScript(): string
    {
        $warning = "请选择打印的设备！";
        return <<<JS
function (data, target, action) {
 var key = {$this->getSelectedKeysScript()}
 if (key.length === 0) {
     Dcat.warning('{$warning}');
     return false;
 }
 // 设置主键为复选框选中的行ID数组
 action.options.key = key;
}
JS;
    }

    protected function html(): string
    {
        return <<<HTML
<a {$this->formatHtmlAttributes()}><button class="btn btn-primary btn-mini">{$this->title()}</button></a>


HTML;
    }
}

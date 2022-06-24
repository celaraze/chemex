<?php

namespace App\Admin\Actions\Tree\ToolAction;

use App\Admin\Forms\ConsumableCategoryImportForm;
use Dcat\Admin\Tree\AbstractTool;
use Dcat\Admin\Widgets\Modal;

class ConsumableCategoryImportAction extends AbstractTool
{
    public function __construct()
    {
        parent::__construct();
        $this->title = admin_trans_label('Import');
    }

    /**
     * 渲染模态框.
     *
     * @return Modal
     */
    public function render(): Modal
    {
        return Modal::make()
            ->lg()
            ->body(new ConsumableCategoryImportForm())
            ->button("<a class='btn btn-sm btn-success' style='color: white;'><i class='feather icon-package'></i>&nbsp;$this->title</a>");
    }
}

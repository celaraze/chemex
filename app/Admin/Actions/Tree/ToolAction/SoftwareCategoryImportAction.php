<?php

namespace App\Admin\Actions\Tree\ToolAction;

use App\Admin\Forms\SoftwareCategoryImportForm;
use Dcat\Admin\Tree\AbstractTool;
use Dcat\Admin\Widgets\Modal;

class SoftwareCategoryImportAction extends AbstractTool
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
            ->body(new SoftwareCategoryImportForm())
            ->button("<a class='btn btn-sm btn-success' style='color: white;'><i class='feather icon-package'></i>&nbsp;$this->title</a>");
    }
}

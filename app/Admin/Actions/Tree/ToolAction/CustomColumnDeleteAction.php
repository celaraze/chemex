<?php

namespace App\Admin\Actions\Tree\ToolAction;

use App\Admin\Forms\CustomColumnDeleteForm;
use Dcat\Admin\Tree\RowAction;
use Dcat\Admin\Widgets\Modal;

class CustomColumnDeleteAction extends RowAction
{
    protected ?string $table_name;

    public function __construct($table_name = null)
    {
        parent::__construct();
        $this->title = admin_trans_label('Delete');
        $this->table_name = $table_name;
    }

    /**
     * 渲染模态框.
     *
     * @return Modal|string
     */
    public function render()
    {
        $form = CustomColumnDeleteForm::make()->payload(['table_name' => $this->table_name]);

        return Modal::make()
            ->lg()
            ->body($form)
            ->button("<a class='btn btn-sm btn-danger' style='color: white;'><i class='feather icon-package'></i>&nbsp;$this->title</a>");
    }
}

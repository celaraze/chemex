<?php

namespace App\Admin\Actions\Tree\ToolAction;

use App\Admin\Forms\CustomColumnUpdateForm;
use Dcat\Admin\Tree\RowAction;
use Dcat\Admin\Widgets\Modal;

class CustomColumnUpdateAction extends RowAction
{
    protected ?string $table_name;

    public function __construct($table_name = null)
    {
        parent::__construct();
        $this->title = admin_trans_label('Update');
        $this->table_name = $table_name;
    }

    /**
     * 渲染模态框.
     *
     * @return Modal|string
     */
    public function render()
    {
        $form = CustomColumnUpdateForm::make()->payload(['table_name' => $this->table_name]);

        return Modal::make()
            ->lg()
            ->body($form)
            ->button("<a class='btn btn-sm btn-warning' style='color: white;'><i class='feather icon-package'></i>&nbsp;$this->title</a>");
    }
}

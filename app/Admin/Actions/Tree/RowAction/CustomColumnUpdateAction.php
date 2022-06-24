<?php

namespace App\Admin\Actions\Tree\RowAction;

use App\Admin\Forms\CustomColumnUpdateForm;
use Dcat\Admin\Tree\RowAction;
use Dcat\Admin\Widgets\Modal;

class CustomColumnUpdateAction extends RowAction
{
    protected ?string $table_name;
    protected ?string $custom_column_name;

    public function __construct(string $table_name = null, string $custom_column_name = null)
    {
        parent::__construct();
        $this->title = '<i class="fa fa-fw feather icon-edit-1"></i> ' . admin_trans_label('Update');
        $this->table_name = $table_name;
        $this->custom_column_name = $custom_column_name;
    }

    public function render(): Modal
    {
        // 实例化表单类并传递自定义参数
        $form = CustomColumnUpdateForm::make()->payload([
            'table_name' => $this->table_name,
            'name' => $this->custom_column_name
        ]);

        return Modal::make()
            ->lg()
            ->title(admin_trans_label('Update'))
            ->body($form)
            ->button($this->title);
    }
}

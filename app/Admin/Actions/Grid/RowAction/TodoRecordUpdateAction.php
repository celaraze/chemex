<?php

namespace App\Admin\Actions\Grid\RowAction;

use App\Admin\Forms\TodoRecordUpdateForm;
use Dcat\Admin\Grid\RowAction;
use Dcat\Admin\Widgets\Modal;

class TodoRecordUpdateAction extends RowAction
{
    public function __construct()
    {
        parent::__construct();
        $this->title = '👨‍💼 '.admin_trans_label('Update');
    }

    /**
     * 渲染模态框.
     *
     * @return Modal|string
     */
    public function render()
    {
        $form = TodoRecordUpdateForm::make()->payload(['id' => $this->getKey()]);

        return Modal::make()
            ->lg()
            ->title(admin_trans_label('Update'))
            ->body($form)
            ->button($this->title);
    }
}

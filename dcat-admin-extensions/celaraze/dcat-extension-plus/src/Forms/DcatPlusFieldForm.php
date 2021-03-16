<?php

namespace Celaraze\DcatPlus\Forms;

use Celaraze\DcatPlus\Support;
use Dcat\Admin\Widgets\Form;

class DcatPlusFieldForm extends Form
{
    /**
     * Handle the form request.
     *
     * @param array $input
     *
     * @return mixed
     */
    public function handle(array $input)
    {
        admin_setting($input);

        return $this
            ->response()
            ->success('站点配置更新成功！')
            ->refresh();
    }

    /**
     * Build a form here.
     */
    public function form()
    {
        $this->switch('switch_to_select_create', Support::trans('main.switch_to_select_create'))
            ->default(admin_setting('switch_to_select_create'));
    }
}

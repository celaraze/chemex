<?php

namespace Celaraze\DcatPlus;

use Dcat\Admin\Extend\Setting as Form;

class Setting extends Form
{
    public function form()
    {
        $this->text('additional_theme_colors', 'Additional Theme Colors')
            ->help('cssname1:Title1,cssname2:Title2');
    }
}

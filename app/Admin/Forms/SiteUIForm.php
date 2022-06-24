<?php

namespace App\Admin\Forms;

use Dcat\Admin\Widgets\Form;

class SiteUIForm extends Form
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
            ->success(trans('main.success'))
            ->refresh();
    }

    /**
     * Build a form here.
     */
    public function form()
    {
        $this->switch('footer_remove')
            ->default(admin_setting('footer_remove'));
        $defaultColors = [
            'default' => '墨蓝',
            'blue' => '蓝',
            'blue-light' => '亮蓝',
            'green' => '墨绿',
        ];
        $this->radio('theme_color')
            ->options($defaultColors)
            ->default(admin_setting('theme_color'));
        $this->switch('grid_row_actions_right')
            ->help(admin_trans_label('Gird Row Action Right Help'))
            ->default(admin_setting('grid_row_actions_right'));
        $this->switch('switch_to_filter_panel')
            ->default(admin_setting('switch_to_filter_panel'));
    }
}

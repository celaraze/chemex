<?php

namespace Celaraze\DcatPlus\Forms;

use Celaraze\DcatPlus\ServiceProvider;
use Celaraze\DcatPlus\Support;
use Dcat\Admin\Widgets\Form;

class DcatPlusUIForm extends Form
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
        $this->switch('footer_remove', Support::trans('main.footer_remove'))
            ->default(admin_setting('footer_remove'));
        $this->switch('header_padding_fix', Support::trans('main.header_padding_fix'))
            ->default(admin_setting('header_padding_fix'));
        $defaultColors = [
            'default'    => '墨蓝',
            'blue'       => '蓝',
            'blue-light' => '亮蓝',
            'green'      => '墨绿',
        ];
        foreach (explode(',', ServiceProvider::setting('additional_theme_colors')) as $value) {
            if (!empty($value)) {
                [$k, $v] = explode(':', $value);
                $defaultColors[$k] = $v;
            }
        }

        $this->radio('theme_color', Support::trans('main.theme_color'))
            ->options($defaultColors)
            ->default(admin_setting('theme_color'));
        $this->radio('sidebar_style', Support::trans('main.sidebar_style'))
            ->options([
                'default'          => '默认',
                'sidebar-separate' => '菜单分离',
                'horizontal_menu'  => '水平菜单',
            ])
            ->default(admin_setting('sidebar_style'));
        $this->switch('grid_row_actions_right', Support::trans('main.grid_row_actions_right'))
            ->help('启用后表格行操作按钮将永远贴着最右侧。')
            ->default(admin_setting('grid_row_actions_right'));
    }
}

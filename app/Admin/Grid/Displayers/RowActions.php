<?php

namespace App\Admin\Grid\Displayers;

use Dcat\Admin\Grid\Displayers\DropdownActions;
use Illuminate\Contracts\View\Factory;
use Illuminate\View\View;

class RowActions extends DropdownActions
{
    /**
     * 构造行操作菜单按钮.
     *
     * @param array $callbacks
     *
     * @return Factory|View
     */
    public function display(array $callbacks = [])
    {
        $this->resetDefaultActions();

        $this->call($callbacks);

        $this->prependDefaultActions();

        $default = $this->default;

        // 判断行操作按钮是否启用了查看
        // 如果有就返回按钮，如果无就返回空
        if (isset($default[0])) {
            $view = $default[0];
            unset($default[0]);
        } else {
            $view = '';
        }

        // 判断行操作按钮是否启用了编辑
        // 如果有就返回按钮，如果无就返回空
        if (isset($default[1])) {
            $edit = $default[1];
            unset($default[1]);
        } else {
            $edit = '';
        }

        // 判断行操作按钮是否启用了删除
        // 如果启用了则返回空
        if (isset($default[2])) {
            array_unshift($this->appends, $default[2]);
            unset($default[2]);
        }

        // 判断有没有查看、编辑和删除这三个默认按钮
        // 如果都没有，则表示这里是独立按钮
        if (empty($view) && empty($edit)) {
            $remove = true;
        } else {
            $remove = false;
        }

        $actions = [
            'view'     => $view,
            'edit'     => $edit,
            'default'  => $default,
            'custom'   => $this->appends,
            'remove'   => $remove,
            'selector' => ".{$this->grid->getRowName()}-checkbox",
        ];

        return view('grid.row-actions', $actions);
    }
}

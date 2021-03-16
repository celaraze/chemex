<?php

namespace App\Admin\Controllers;

use Dcat\Admin\Http\Controllers\MenuController as BaseMenuController;
use Dcat\Admin\Layout\Content;
use Dcat\Admin\Layout\Row;

class MenuController extends BaseMenuController
{
    public function index(Content $content): Content
    {
        return $content
            ->title($this->title())
            ->description(trans('admin.list'))
            ->body(function (Row $row) {
                $row->column(12, $this->treeView()->render());
            });
    }
}

<?php


namespace App\Traits;


use Dcat\Admin\Grid;
use Dcat\Admin\Layout\Content;
use Illuminate\Contracts\Translation\Translator;

trait ControllerHasTab
{
    /**
     * 列表页面布局.
     * @param Content $content
     * @return Content
     */
    public function index(Content $content): Content
    {
        return $content
            ->title($this->title())
            ->description(admin_trans_label('description'))
            ->body($this->tab($this->renderGrid()));
    }

    /**
     * 标题.
     * @return array|Translator|string|null
     */
    public function title(): array|string|Translator|null
    {
        return admin_trans_label('title');
    }

    /**
     * 默认渲染grid.
     * @return \Dcat\Admin\Grid|\App\Grid
     */
    public function renderGrid(): Grid|\App\Grid
    {
        return $this->grid();
    }
}

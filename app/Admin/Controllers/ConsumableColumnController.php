<?php

namespace App\Admin\Controllers;

use App\Admin\Repositories\ConsumableRecord;
use App\Support\Data;
use App\Traits\ControllerHasColumnSort;
use App\Traits\ControllerHasTab;
use Dcat\Admin\Http\Controllers\AdminController;
use Dcat\Admin\Layout\Row;
use Dcat\Admin\Repositories\Repository;
use Dcat\Admin\Widgets\Tab;

class ConsumableColumnController extends AdminController
{
    use ControllerHasColumnSort;
    use ControllerHasTab;

    protected Repository $repository;

    /**
     * 标签布局.
     * @return Row
     */
    public function tab(): Row
    {
        $row = new Row();
        $tab = new Tab();
        $tab->addLink(Data::icon('record') . trans('main.record'), admin_route('consumable.records.index'));
        $tab->addLink(Data::icon('category') . trans('main.category'), admin_route('consumable.categories.index'));
        $tab->addLink(Data::icon('track') . trans('main.history'), admin_route('consumable.tracks.index'));
        $tab->add(Data::icon('column') . trans('main.column'), $this->renderGrid(), true);
        $row->column(12, $tab);
        return $row;
    }

    /**
     * 重写渲染为自定义.
     * @return Row
     */
    public function renderGrid(): Row
    {
        return $this->render();
    }

    /**
     * 指定数据仓库.
     * @return ConsumableRecord
     */
    public function repository(): ConsumableRecord
    {
        return new ConsumableRecord();
    }
}

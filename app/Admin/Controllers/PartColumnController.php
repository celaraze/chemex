<?php

namespace App\Admin\Controllers;

use App\Admin\Repositories\PartRecord;
use App\Support\Data;
use App\Traits\ControllerHasColumnSort;
use App\Traits\ControllerHasTab;
use Dcat\Admin\Http\Controllers\AdminController;
use Dcat\Admin\Layout\Row;
use Dcat\Admin\Repositories\Repository;
use Dcat\Admin\Widgets\Tab;

class PartColumnController extends AdminController
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
        $tab->addLink(Data::icon('record') . trans('main.record'), admin_route('part.records.index'));
        $tab->addLink(Data::icon('category') . trans('main.category'), admin_route('part.categories.index'));
        $tab->addLink(Data::icon('track') . trans('main.track'), admin_route('part.tracks.index'));
        $tab->addLink(Data::icon('statistics') . trans('main.statistics'), admin_route('part.statistics'));
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
     * @return PartRecord
     */
    public function repository(): PartRecord
    {
        return new PartRecord();
    }
}

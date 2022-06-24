<?php

namespace App\Admin\Controllers;

use App\Admin\Repositories\ServiceRecord;
use App\Support\Data;
use App\Traits\ControllerHasColumnSort;
use App\Traits\ControllerHasTab;
use Dcat\Admin\Http\Controllers\AdminController;
use Dcat\Admin\Layout\Row;
use Dcat\Admin\Repositories\Repository;
use Dcat\Admin\Widgets\Tab;

class ServiceColumnController extends AdminController
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
        $tab->addLink(Data::icon('record') . trans('main.record'), admin_route('service.records.index'));
        $tab->addLink(Data::icon('track') . trans('main.track'), admin_route('service.tracks.index'));
        $tab->addLink(Data::icon('issue') . trans('main.issue'), admin_route('service.issues.index'));
        $tab->addLink(Data::icon('statistics') . trans('main.statistics'), admin_route('service.statistics'));
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
     * @return ServiceRecord
     */
    public function repository(): ServiceRecord
    {
        return new ServiceRecord();
    }
}

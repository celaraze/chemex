<?php

namespace App\Admin\Controllers;

use App\Admin\Forms\CustomColumnUpdateForm;
use App\Admin\Repositories\DeviceRecord;
use App\Support\Data;
use App\Traits\ControllerHasColumnSort;
use App\Traits\ControllerHasTab;
use Dcat\Admin\Http\Controllers\AdminController;
use Dcat\Admin\Layout\Column;
use Dcat\Admin\Layout\Row;
use Dcat\Admin\Repositories\Repository;
use Dcat\Admin\Widgets\Box;
use Dcat\Admin\Widgets\Tab;

class CustomColumnUpdateController extends AdminController
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
        $tab->addLink(Data::icon('record') . trans('main.record'), admin_route('device.records.index'));
        $tab->addLink(Data::icon('category') . trans('main.category'), admin_route('device.categories.index'));
        $tab->addLink(Data::icon('track') . trans('main.track'), admin_route('device.tracks.index'));
        $tab->addLink(Data::icon('statistics') . trans('main.statistics'), admin_route('device.statistics'));
        $tab->addLink(Data::icon('column') . trans('main.column'), admin_route('device.columns.index'));
        $tab->add(Data::icon('column') . trans('main.update_column'), $this->renderGrid(), true);
        $row->column(12, $tab);
        return $row;
    }

    /**
     * 重写渲染为自定义.
     * @return Row
     */
    public function renderGrid(): Row
    {
        $name = request('name');
        $table_name = request('table_name');

        $box = Box::make(trans('admin.edit'), (new CustomColumnUpdateForm())
            ->payload(['name' => $name, 'table_name' => $table_name]));
        return new Row(function (Column $column) use ($box) {
            $column->row($box);
        });
    }

    /**
     * 指定数据仓库.
     * @return DeviceRecord
     */
    public function repository(): DeviceRecord
    {
        return new DeviceRecord();
    }
}

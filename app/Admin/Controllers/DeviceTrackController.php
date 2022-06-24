<?php

namespace App\Admin\Controllers;

use App\Admin\Actions\Grid\RowAction\DeviceTrackDeleteAction;
use App\Admin\Actions\Grid\RowAction\DeviceTrackUpdateDeleteAction;
use App\Admin\Grid\Displayers\RowActions;
use App\Admin\Repositories\DeviceTrack;
use App\Support\Data;
use App\Traits\ControllerHasTab;
use Dcat\Admin\Admin;
use Dcat\Admin\Grid;
use Dcat\Admin\Http\Controllers\AdminController;
use Dcat\Admin\Layout\Row;
use Dcat\Admin\Show;
use Dcat\Admin\Widgets\Alert;
use Dcat\Admin\Widgets\Tab;

/**
 * @property string deleted_at
 */
class DeviceTrackController extends AdminController
{
    use ControllerHasTab;

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
        $tab->add(Data::icon('track') . trans('main.track'), $this->renderGrid(), true);
        $tab->addLink(Data::icon('statistics') . trans('main.statistics'), admin_route('device.statistics'));
        $tab->addLink(Data::icon('column') . trans('main.column'), admin_route('device.columns.index'));
        $row->column(12, $tab);
        return $row;
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid(): Grid
    {
        return Grid::make(new DeviceTrack(['device', 'user']), function (Grid $grid) {
            $grid->column('id');
            $grid->column('device.asset_number');
            $grid->column('device.name');
            $grid->column('user.name');
            $grid->column('lend_time');
            $grid->column('lend_description');
            $grid->column('created_at');

            /**
             * 行操作按钮.
             */
            $grid->actions(function (RowActions $actions) {
                if (empty($this->lend_time)) {
                    // @permissions
                    if (Admin::user()->can('device.track.delete') && $this->deleted_at == null) {
                        $actions->append(new DeviceTrackDeleteAction());
                    }
                } else {
                    // @permissions
                    if (Admin::user()->can('device.track.update_delete') && $this->deleted_at == null) {
                        $actions->append(new DeviceTrackUpdateDeleteAction());
                    }
                }
            });

            /**
             * 字段过滤.
             */
            $grid->filter(function (Grid\Filter $filter) {
                if (admin_setting('switch_to_filter_panel')) {
                    $filter->panel();
                }
                $filter->scope('history', admin_trans_label('History Scope'))->onlyTrashed();
            });

            /**
             * 快速搜索.
             */
            $grid->quickSearch('id', 'device.asset_number', 'user.name')
                ->placeholder(trans('main.quick_search'))
                ->auto(false);

            /**
             * 按钮控制.
             */
            $grid->disableCreateButton();
            $grid->disableRowSelector();
            $grid->disableBatchActions();
            $grid->disableEditButton();
            $grid->disableDeleteButton();
            $grid->toolsWithOutline(false);
        });
    }

    /**
     * Make a show builder.
     *
     * @param int $id
     *
     * @return Show
     */
    protected function detail(int $id): Show
    {
        return Show::make($id, new DeviceTrack(['device', 'user']), function (Show $show) {
            $show->field('id');
            $show->field('device.name');
            $show->field('user.name');
            $show->field('lend_time');
            $show->field('lend_description');
            $show->field('plan_return_time');
            $show->field('return_time');
            $show->field('return_description');
            $show->field('created_at');
            $show->field('updated_at');

            /**
             * 按钮控制.
             */
            $show->disableDeleteButton();
            $show->disableEditButton();
        });
    }

    /**
     * Make a form builder.
     *
     * @return Alert
     */
    protected function form(): Alert
    {
        return Data::unsupportedOperationWarning();
    }
}

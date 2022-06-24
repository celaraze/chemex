<?php

namespace App\Admin\Controllers;

use App\Admin\Actions\Grid\RowAction\CheckTrackDeleteAction;
use App\Admin\Actions\Grid\RowAction\CheckTrackUpdateAction;
use App\Admin\Grid\Displayers\RowActions;
use App\Admin\Repositories\CheckTrack;
use App\Support\Data;
use App\Traits\ControllerHasTab;
use Dcat\Admin\Admin;
use Dcat\Admin\Grid;
use Dcat\Admin\Http\Controllers\AdminController;
use Dcat\Admin\Layout\Row;
use Dcat\Admin\Widgets\Alert;
use Dcat\Admin\Widgets\Tab;

/**
 * @property int status
 * @property int check_id
 */
class CheckTrackController extends AdminController
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
        $tab->addLink(Data::icon('record') . trans('main.check_record'), admin_route('check.records.index'));
        $tab->add(Data::icon('track') . trans('main.check_track'), $this->renderGrid(), true);
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
        return Grid::make(new CheckTrack(['checker', 'item']), function (Grid $grid) {
            $grid->column('id');
            $grid->column('check_item')->using(Data::items());
            $grid->column('check_id');
            $grid->column('item.asset_number');
            $grid->column('status')->using(Data::checkTrackStatus());
            $grid->column('checker.name');
            $grid->column('created_at');
            $grid->column('updated_at');

            $grid->disableRowSelector();
            $grid->disableBatchActions();
            $grid->disableCreateButton();
            $grid->disableEditButton();
            $grid->disableViewButton();
            $grid->disableDeleteButton();

            $grid->actions(function (RowActions $actions) {
                // @permissions
                if (Admin::user()->can('check.track.update') && $this->status == 0) {
                    $actions->append(new CheckTrackUpdateAction());
                }
            });

            $grid->toolsWithOutline(false);

            $grid->quickSearch('id', 'item.asset_number', 'check_id', 'checker.name')
                ->placeholder(trans('main.quick_search'))
                ->auto(false);
        });
    }

    /**
     * Make a show builder.
     *
     * @return Alert
     */
    protected function detail(): Alert
    {
        return Data::unsupportedOperationWarning();
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

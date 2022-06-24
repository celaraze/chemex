<?php

namespace App\Admin\Controllers;

use App\Admin\Actions\Grid\RowAction\MaintenanceRecordDeleteAction;
use App\Admin\Actions\Grid\RowAction\MaintenanceRecordForceDeleteAction;
use App\Admin\Actions\Grid\RowAction\MaintenanceRecordUpdateAction;
use App\Admin\Grid\Displayers\RowActions;
use App\Admin\Repositories\MaintenanceRecord;
use App\Support\Data;
use Dcat\Admin\Admin;
use Dcat\Admin\Grid;
use Dcat\Admin\Grid\Tools\Selector;
use Dcat\Admin\Http\Controllers\AdminController;
use Dcat\Admin\Layout\Content;
use Dcat\Admin\Widgets\Alert;
use Illuminate\Contracts\Translation\Translator;

/**
 * @property int item_id
 * @property string item
 * @property int status
 */
class MaintenanceRecordController extends AdminController
{
    /**
     * Index interface.
     *
     * @param Content $content
     *
     * @return Content
     */
    public function index(Content $content): Content
    {
        return $content
            ->title($this->title())
            ->description(admin_trans_label('description'))
            ->body($this->grid());
    }

    public function title(): array|string|Translator|null
    {
        return admin_trans_label('title');
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid(): Grid
    {
        return Grid::make(new MaintenanceRecord(), function (Grid $grid) {
            $grid->model()->orderBy('status', 'ASC');

            $grid->column('id');
            $grid->column('asset_number');
            $grid->column('ng_description')->limit(30);
            $grid->column('ok_description')->limit(30);
            $grid->column('ng_time');
            $grid->column('ok_time');
            $grid->column('status')->using(Data::maintenanceStatus());

            /**
             * 行操作按钮.
             */
            $grid->actions(function (RowActions $actions) {
                // @permissions
                if ($this->status == 0 && Admin::user()->can('maintenance.record.delete')) {
                    $actions->append(new MaintenanceRecordDeleteAction());
                }

                if ($this->status == 0 && Admin::user()->can('maintenance.record.forcedelete')) {
                    $actions->append(new MaintenanceRecordForceDeleteAction());
                }
                if ($this->status == 0 && Admin::user()->can('maintenance.record.update')) {
                    $actions->append(new MaintenanceRecordUpdateAction());
                }
            });

            $grid->quickSearch('id',
                'ng_description',
                'ok_description',
                'asset_number')
                ->placeholder(trans('main.quick_search'))
                ->auto(false);

            $grid->selector(function (Selector $selector) {
                $selector->select('status', [
                    0 => admin_trans_label('Status Waiting'),
                    1 => admin_trans_label('Status Done'),
                    2 => admin_trans_label('Status Cancelled'),
                ]);
            });

            /**
             * 按钮控制.
             */
            $grid->disableCreateButton();
            $grid->disableViewButton();
            $grid->disableEditButton();
            $grid->disableDeleteButton();
            $grid->disableBatchActions();
            $grid->disableRowSelector();
            $grid->toolsWithOutline(false);
            // @permissions
            if (Admin::user()->can('maintenance.record.export')) {
                $grid->export();
            }
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

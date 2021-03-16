<?php

namespace App\Admin\Controllers;

use App\Admin\Actions\Grid\RowAction\ServiceRecordCreateIssueAction;
use App\Admin\Actions\Grid\RowAction\ServiceRecordCreateUpdateTrackAction;
use App\Admin\Actions\Grid\RowAction\ServiceRecordDeleteAction;
use App\Admin\Grid\Displayers\RowActions;
use App\Admin\Repositories\ServiceRecord;
use App\Grid;
use App\Models\ColumnSort;
use App\Models\DeviceRecord;
use App\Models\PurchasedChannel;
use App\Support\Data;
use App\Support\Support;
use App\Traits\ControllerHasCustomColumns;
use Dcat\Admin\Admin;
use Dcat\Admin\Form;
use Dcat\Admin\Http\Controllers\AdminController;
use Dcat\Admin\Layout\Content;
use Dcat\Admin\Layout\Row;
use Dcat\Admin\Show;
use Dcat\Admin\Widgets\Tab;

/**
 * @property  DeviceRecord device
 */
class ServiceRecordController extends AdminController
{
    public function index(Content $content): Content
    {
        return $content
            ->title($this->title())
            ->description(admin_trans_label('description'))
            ->body(function (Row $row) {
                $tab = new Tab();
                $tab->add(Data::icon('record').trans('main.record'), $this->grid(), true);
                $tab->addLink(Data::icon('track').trans('main.track'), admin_route('service.tracks.index'));
                $tab->addLink(Data::icon('issue').trans('main.issue'), admin_route('service.issues.index'));
                $tab->addLink(Data::icon('statistics').trans('main.statistics'), admin_route('service.statistics'));
                $tab->addLink(Data::icon('column').trans('main.column'), admin_route('service.columns.index'));
                $row->column(12, $tab);
            });
    }

    public function title()
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
        return Grid::make(new ServiceRecord(['device', 'channel']), function (Grid $grid) {
            $column_sort = ColumnSort::where('table_name', (new ServiceRecord())->getTable())
                ->get(['field', 'order'])
                ->toArray();
            $grid->column('id', '', $column_sort);
            $grid->column('name', '', $column_sort);
            $grid->column('description', '', $column_sort);
            $grid->column('status', '', $column_sort)->switch('green');
            $grid->column('device.name', '', $column_sort)->link(function () {
                if (!empty($this->device)) {
                    return admin_route('device.records.show', [$this->device['id']]);
                }
            });
            $grid->column('channel.name', '', $column_sort);
            $grid->column('created_at', '', $column_sort);
            $grid->column('updated_at', '', $column_sort);

            ControllerHasCustomColumns::makeGrid((new ServiceRecord())->getTable(), $grid, $column_sort);

            /**
             * 行操作按钮.
             */
            $grid->actions(function (RowActions $actions) {
                // @permissions
                if (Admin::user()->can('service.record.delete')) {
                    $actions->append(new ServiceRecordDeleteAction());
                }
                // @permissions
                if (Admin::user()->can('service.record.track.create_update')) {
                    $actions->append(new ServiceRecordCreateUpdateTrackAction());
                }
                // @permissions
                if (Admin::user()->can('service.record.issue.create')) {
                    $actions->append(new ServiceRecordCreateIssueAction());
                }
            });

            /**
             * 字段过滤.
             */
            $grid->showColumnSelector();

            /**
             * 快速搜索.
             */
            $grid->quickSearch(
                array_merge([
                    'id',
                    'name',
                    'description',
                    'device.name',
                ], ControllerHasCustomColumns::makeQuickSearch((new ServiceRecord())->getTable()))
            )
                ->placeholder(trans('main.quick_search'))
                ->auto(false);

            /**
             * 筛选.
             */
            $grid->filter(function ($filter) {
                $filter->equal('device.name');
                /**
                 * 自定义字段.
                 */
                ControllerHasCustomColumns::makeFilter((new ServiceRecord())->getTable(), $filter);
            });

            /**
             * 按钮控制.
             */
            $grid->enableDialogCreate();
            $grid->disableRowSelector();
            $grid->disableDeleteButton();
            $grid->disableBatchActions();
            $grid->toolsWithOutline(false);
            // @permissions
            if (!Admin::user()->can('service.record.create')) {
                $grid->disableCreateButton();
            }
            // @permissions
            if (!Admin::user()->can('service.record.update')) {
                $grid->disableEditButton();
            }
            // @permissions
            if (Admin::user()->can('service.record.export')) {
                $grid->export();
            }
        });
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     *
     * @return Show
     */
    protected function detail($id): Show
    {
        return Show::make($id, new ServiceRecord(['channel', 'device']), function (Show $show) {
            $show->field('id');
            $show->field('name');
            $show->field('description');
            $show->field('device.name');
            $show->field('price');
            $show->field('purchased');
            $show->field('expired');
            $show->field('channel.name');

            /**
             * 自定义字段.
             */
            ControllerHasCustomColumns::makeDetail((new ServiceRecord())->getTable(), $show);

            $show->field('created_at');
            $show->field('updated_at');

            /**
             * 按钮控制.
             */
            $show->disableDeleteButton();
            // @permissions
            if (!Admin::user()->can('service.record.update')) {
                $show->disableEditButton();
            }
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form(): Form
    {
        return Form::make(new ServiceRecord(), function (Form $form) {
            $form->display('id');
            $form->text('name')->required();
            $form->divider();
            $form->text('description');
            $form->switch('status')
                ->default(0)
                ->help(admin_trans_label('Status Help'));
            $form->currency('price');
            $form->date('purchased');
            $form->date('expired');

            if (Support::ifSelectCreate()) {
                $form->selectCreate('purchased_channel_id')
                    ->options(PurchasedChannel::class)->ajax(admin_route('selection.purchased.channels'))
                    ->ajax(admin_route('selection.purchased.channels'))
                    ->url(admin_route('purchased.channels.create'));
            } else {
                $form->select('purchased_channel_id')
                    ->options(PurchasedChannel::pluck('name', 'id'));
            }

            /**
             * 自定义字段.
             */
            ControllerHasCustomColumns::makeForm((new ServiceRecord())->getTable(), $form);

            $form->display('created_at');
            $form->display('updated_at');

            /**
             * 按钮控制.
             */
            $form->disableDeleteButton();
            $form->disableCreatingCheck();
            $form->disableEditingCheck();
            $form->disableViewCheck();
        });
    }
}

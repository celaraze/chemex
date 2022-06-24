<?php

namespace App\Admin\Controllers;

use Ace\Uni;
use App\Admin\Actions\Grid\BatchAction\ServiceRecordBatchDeleteAction;
use App\Admin\Actions\Grid\BatchAction\ServiceRecordBatchForceDeleteAction;
use App\Admin\Actions\Grid\RowAction\ServiceRecordCreateIssueAction;
use App\Admin\Actions\Grid\RowAction\ServiceRecordCreateUpdateTrackAction;
use App\Admin\Actions\Grid\RowAction\ServiceRecordDeleteAction;
use App\Admin\Actions\Show\ServiceRecordDeleteTrackAction;
use App\Admin\Grid\Displayers\RowActions;
use App\Admin\Repositories\ServiceRecord;
use App\Form;
use App\Grid;
use App\Models\ColumnSort;
use App\Models\DeviceRecord;
use App\Show;
use App\Support\Data;
use App\Traits\ControllerHasCustomColumns;
use App\Traits\ControllerHasDeviceRelatedGrid;
use App\Traits\ControllerHasTab;
use DateTime;
use Dcat\Admin\Admin;
use Dcat\Admin\Grid\Tools\BatchActions;
use Dcat\Admin\Http\Controllers\AdminController;
use Dcat\Admin\Layout\Row;
use Dcat\Admin\Show\Tools;
use Dcat\Admin\Widgets\Tab;

/**
 * @property  DeviceRecord device
 * @property DateTime deleted_at
 * @method track()
 */
class ServiceRecordController extends AdminController
{
    use ControllerHasDeviceRelatedGrid;
    use ControllerHasCustomColumns;
    use ControllerHasTab;

    /**
     * 标签布局.
     * @return Row
     */
    public function tab(): Row
    {
        $row = new Row();
        $tab = new Tab();
        $tab->add(Data::icon('record') . trans('main.record'), $this->renderGrid(), true);
        $tab->addLink(Data::icon('track') . trans('main.track'), admin_route('service.tracks.index'));
        $tab->addLink(Data::icon('issue') . trans('main.issue'), admin_route('service.issues.index'));
        $tab->addLink(Data::icon('statistics') . trans('main.statistics'), admin_route('service.statistics'));
        $tab->addLink(Data::icon('column') . trans('main.column'), admin_route('service.columns.index'));
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
        return Grid::make(new ServiceRecord(['device']), function (Grid $grid) {
            $sort_columns = $this->sortColumns();
            $grid->column('id', '', $sort_columns);
            $grid->column('name', '', $sort_columns);
            $grid->column('description', '', $sort_columns);
            $grid->column('status', '', $sort_columns)->switch('green');
            $grid->column('device.asset_number', '', $sort_columns)->link(function () {
                if (!empty($this->device)) {
                    return admin_route('device.records.show', [$this->device['id']]);
                }
            });
            $grid->column('created_at', '', $sort_columns);
            $grid->column('updated_at', '', $sort_columns);

            ControllerHasCustomColumns::makeGrid((new ServiceRecord())->getTable(), $grid, $sort_columns);

            /**
             * 行操作按钮.
             */
            $grid->actions(function (RowActions $actions) {
                if ($this->deleted_at == null) {
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
                }
            });

            /**
             * 批量操作.
             */
            $grid->batchActions(function (BatchActions $batchActions) {
                // @permissions
                if (Admin::user()->can('service.record.batch.delete')) {
                    $batchActions->add(new ServiceRecordBatchDeleteAction());
                }
                // @permissions
                if (Admin::user()->can('service.record.batch.force.delete')) {
                    $batchActions->add(new ServiceRecordBatchForceDeleteAction());
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
                    'device.asset_number',
                ], ControllerHasCustomColumns::makeQuickSearch((new ServiceRecord())->getTable()))
            )
                ->placeholder(trans('main.quick_search'))
                ->auto(false);

            /**
             * 筛选.
             */
            $grid->filter(function ($filter) {
                if (admin_setting('switch_to_filter_panel')) {
                    $filter->panel();
                }
                $filter->scope('history', admin_trans_label('Deleted'))->onlyTrashed();
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
            $grid->disableDeleteButton();
            $grid->disableBatchDelete();
            $grid->toolsWithOutline(false);
            if (!request('_scope_')) {
                // @permissions
                if (!Admin::user()->can('service.record.create')) {
                    $grid->disableCreateButton();
                }
                // @permissions
                if (!Admin::user()->can('service.record.update')) {
                    $grid->disableEditButton();
                }
            }
            // @permissions
            if (Admin::user()->can('service.record.export')) {
                $grid->export()->rows(function ($rows) {
                    foreach ($rows as $row) {
                        $service = \App\Models\ServiceRecord::query()
                            ->where('id', $row['id'])
                            ->first();
                        //导出服务状态定义
                        $row['status'] = Uni::yesOrNo()[$service->status];
                        //导出所属设备定义
                        $row['device.asset_number'] = $service?->device?->asset_number;
                    }
                    return $rows;
                });
            }
        });
    }

    /**
     * 返回字段排序.
     *
     * @return array
     */
    public function sortColumns(): array
    {
        return ColumnSort::where('table_name', (new ServiceRecord())->getTable())
            ->get(['name', 'order'])
            ->toArray();
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
        return Show::make($id, new ServiceRecord(['device']), function (Show $show) {
            $sort_columns = $this->sortColumns();
            $show->field('id', '', $sort_columns);
            $show->field('name', '', $sort_columns);
            $show->field('description', '', $sort_columns);
            $show->field('device.asset_number', '', $sort_columns);
            $show->field('price', '', $sort_columns);
            $show->field('purchased', '', $sort_columns);
            $show->field('expired', '', $sort_columns);

            /**
             * 自定义字段.
             */
            ControllerHasCustomColumns::makeDetail((new ServiceRecord())->getTable(), $show, $sort_columns);

            $show->field('created_at', '', $sort_columns);
            $show->field('updated_at', '', $sort_columns);

            /**
             * 自定义按钮.
             */
            $show->tools(function (Tools $tools) {
                // @permissions
                if (Admin::user()->can('service.track.delete') && !empty($this->track()->first())) {
                    $tools->append(new ServiceRecordDeleteTrackAction());
                }
                // @permissions
                if (Admin::user()->can('service.record.track.create_update') && empty($this->track()->first())) {
                    $tools->append(new \App\Admin\Actions\Show\ServiceRecordCreateUpdateTrackAction());
                }
                $tools->append('&nbsp;');
            });

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
            $form->row(function (\Dcat\Admin\Form\Row $row) {
                $row->width(6)
                    ->text('name')->required();
                $row->width(6)
                    ->text('description');
                $row->width(6)
                    ->switch('status')
                    ->default(0)
                    ->help(admin_trans_label('Status Help'));
                $row->width(6)
                    ->currency('price');
                $row->width(6)
                    ->date('purchased');
                $row->width(6)
                    ->date('expired');

                /**
                 * 自定义字段
                 */
                foreach (ControllerHasCustomColumns::getCustomColumns((new ServiceRecord())->getTable()) as $custom_column) {
                    ControllerHasCustomColumns::makeForm($custom_column, $row->width(6));
                }
            });

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

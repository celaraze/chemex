<?php

namespace App\Admin\Controllers;

use App\Admin\Actions\Grid\BatchAction\ConsumableRecordBatchDeleteAction;
use App\Admin\Actions\Grid\BatchAction\ConsumableRecordBatchForceDeleteAction;
use App\Admin\Actions\Grid\RowAction\ConsumableRecordDeleteAction;
use App\Admin\Actions\Grid\ToolAction\ConsumableImportAction;
use App\Admin\Actions\Grid\ToolAction\ConsumableInAction;
use App\Admin\Actions\Grid\ToolAction\ConsumableOutAction;
use App\Admin\Grid\Displayers\RowActions;
use App\Admin\Repositories\ConsumableRecord;
use App\Form;
use App\Grid;
use App\Models\ColumnSort;
use App\Models\ConsumableCategory;
use App\Models\VendorRecord;
use App\Show;
use App\Support\Data;
use App\Support\Support;
use App\Traits\ControllerHasCustomColumns;
use App\Traits\ControllerHasDeviceRelatedGrid;
use App\Traits\ControllerHasTab;
use Dcat\Admin\Admin;
use Dcat\Admin\Grid\Tools;
use Dcat\Admin\Grid\Tools\BatchActions;
use Dcat\Admin\Http\Controllers\AdminController;
use Dcat\Admin\Layout\Row;
use Dcat\Admin\Widgets\Tab;

/**
 * @method allCounts()
 */
class ConsumableRecordController extends AdminController
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
        $tab->addLink(Data::icon('category') . trans('main.category'), admin_route('consumable.categories.index'));
        $tab->addLink(Data::icon('track') . trans('main.history'), admin_route('consumable.tracks.index'));
        $tab->addLink(Data::icon('column') . trans('main.column'), admin_route('consumable.columns.index'));
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
        return Grid::make(new ConsumableRecord(['category', 'vendor', 'track']), function (Grid $grid) {
            $sort_columns = $this->sortColumns();
            $grid->column('id', '', $sort_columns);
            $grid->column('name', '', $sort_columns);
            $grid->column('description', '', $sort_columns);
            $grid->column('specification', '', $sort_columns);
            $grid->column('category.name', '', $sort_columns);
            $grid->column('vendor.name', '', $sort_columns);
            $grid->column('price', '', $sort_columns);
            $grid->column('track.number', '', $sort_columns);
            $grid->column('created_at', '', $sort_columns);
            $grid->column('updated_at', '', $sort_columns);

            /**
             * 自定义字段.
             */
            ControllerHasCustomColumns::makeGrid((new ConsumableRecord())->getTable(), $grid, $sort_columns);

            /**
             * 字段过滤.
             */
            $grid->showColumnSelector();

            $grid->actions(function (RowActions $actions) {
                // @permissions
                if (Admin::user()->can('consumable.record.delete')) {
                    $actions->append(new ConsumableRecordDeleteAction());
                }
            });

            /**
             * 工具按钮.
             */
            $grid->tools(function (Tools $tools) {
                // @permissions
                if (Admin::user()->can('consumable.record.in')) {
                    $tools->append(new ConsumableInAction());
                }
                // @permissions
                if (Admin::user()->can('consumable.record.out')) {
                    $tools->append(new ConsumableOutAction());

                }
                if (Admin::user()->can('consumable.record.import')) {
                    $tools->append(new ConsumableImportAction());


                }
            });

            /**
             * 批量操作.
             */
            $grid->batchActions(function (BatchActions $batchActions) {
                // @permissions
                if (Admin::user()->can('consumable.record.batch.delete')) {
                    $batchActions->add(new ConsumableRecordBatchDeleteAction());
                }
                // @permissions
                if (Admin::user()->can('consumable.record.batch.force.delete')) {
                    $batchActions->add(new ConsumableRecordBatchForceDeleteAction());
                }
            });

            /**
             * 快速搜索.
             */
            $grid->quickSearch(
                array_merge([
                    'id',
                    'name',
                    'description',
                    'specification',
                    'category.name',
                    'vendor.name',
                    'price',
                ], ControllerHasCustomColumns::makeQuickSearch((new ConsumableRecord())->getTable()))
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
                $filter->equal('category_id')->select(ConsumableCategory::pluck('name', 'id'));
                $filter->equal('vendor_id')->select(VendorRecord::pluck('name', 'id'));
                /**
                 * 自定义字段.
                 */
                ControllerHasCustomColumns::makeFilter((new ConsumableRecord())->getTable(), $filter);
            });

            $grid->disableBatchDelete();
            $grid->disableDeleteButton();
            $grid->enableDialogCreate();
            $grid->toolsWithOutline(false);
            if (!request('_scope_')) {
                // @permissions
                if (!Admin::user()->can('consumable.record.create')) {
                    $grid->disableCreateButton();
                }
                // @permissions
                if (!Admin::user()->can('consumable.record.update')) {
                    $grid->disableEditButton();
                }
            }
            // @permissions
            if (Admin::user()->can('consumable.record.export')) {
                $grid->export()->rows(function ($rows) {
                    foreach ($rows as $row) {
                        $consumable = \App\Models\ConsumableRecord::query()
                            ->where('id', $row['id'])
                            ->first();
                        //导出耗材分类定义
                        $row['category.name'] = $consumable?->category->name ?? '未知';
                        //导出厂商定义
                        $row['vendor.name'] = $consumable?->vendor->name ?? '未知';
                        //导出总数定义
                        $row['track.number'] = $consumable?->track->number ?? '未知';
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
        return ColumnSort::where('table_name', (new ConsumableRecord())->getTable())
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
        return Show::make($id, new ConsumableRecord(['category', 'vendor']), function (Show $show) {
            $sort_columns = $this->sortColumns();
            $show->field('id', '', $sort_columns);
            $show->field('name', '', $sort_columns);
            $show->field('description', '', $sort_columns);
            $show->field('specification', '', $sort_columns);
            $show->field('category.name', '', $sort_columns);
            $show->field('vendor.name', '', $sort_columns);
            $show->field('price', '', $sort_columns);

            /**
             * 自定义字段.
             */
            ControllerHasCustomColumns::makeDetail((new ConsumableRecord())->getTable(), $show, $sort_columns);

            $show->field('created_at', '', $sort_columns);
            $show->field('updated_at', '', $sort_columns);

            /**
             * 按钮控制.
             */
            $show->disableDeleteButton();
            // @permissions
            if (!Admin::user()->can('consumable.record.update')) {
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
        return Form::make(new ConsumableRecord(), function (Form $form) {
            $form->row(function (\Dcat\Admin\Form\Row $row) use ($form) {
                $row->width(6)->text('name')->required();
                $row->width(6)->text('specification')
                    ->required();
                if (Support::ifSelectCreate()) {
                    $row->width(6)->selectCreate('category_id')
                        ->options(ConsumableCategory::class)
                        ->ajax(admin_route('selection.consumable.categories'))
                        ->url(admin_route('consumable.categories.create'))
                        ->required();
                    $row->width(6)->selectCreate('vendor_id')
                        ->options(VendorRecord::class)
                        ->ajax(admin_route('selection.vendor.records'))
                        ->url(admin_route('vendor.records.create'))
                        ->required();
                } else {
                    $row->width(6)->select('category_id')
                        ->options(ConsumableCategory::pluck('name', 'id'))
                        ->required();
                    $row->width(6)->select('vendor_id')
                        ->options(VendorRecord::pluck('name', 'id'))
                        ->required();
                }

                $row->width(6)->text('description');
                $row->width(6)->text('price');

                /**
                 * 自定义字段.
                 */
                foreach (ControllerHasCustomColumns::getCustomColumns((new ConsumableRecord())->getTable()) as $custom_column) {
                    ControllerHasCustomColumns::makeForm($custom_column, $row->width(6));
                }
            });

            $form->disableDeleteButton();
        });
    }
}


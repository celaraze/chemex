<?php

namespace App\Admin\Controllers;

use App\Admin\Actions\Grid\BatchAction\PartRecordBatchDeleteAction;
use App\Admin\Actions\Grid\BatchAction\PartRecordBatchForceDeleteAction;
use App\Admin\Actions\Grid\RowAction\MaintenanceRecordCreateAction;
use App\Admin\Actions\Grid\RowAction\PartRecordCreateUpdateTrackAction;
use App\Admin\Actions\Grid\RowAction\PartRecordDeleteAction;
use App\Admin\Actions\Grid\ToolAction\PartRecordImportAction;
use App\Admin\Actions\Show\PartRecordDeleteTrackAction;
use App\Admin\Grid\Displayers\RowActions;
use App\Admin\Repositories\PartRecord;
use App\Form;
use App\Grid;
use App\Models\ColumnSort;
use App\Models\DepreciationRule;
use App\Models\DeviceRecord;
use App\Models\PartCategory;
use App\Models\VendorRecord;
use App\Services\ExpirationService;
use App\Show;
use App\Support\Data;
use App\Support\Support;
use App\Traits\ControllerHasCustomColumns;
use App\Traits\ControllerHasDeviceRelatedGrid;
use App\Traits\ControllerHasTab;
use DateTime;
use Dcat\Admin\Admin;
use Dcat\Admin\Grid\Tools;
use Dcat\Admin\Grid\Tools\BatchActions;
use Dcat\Admin\Http\Controllers\AdminController;
use Dcat\Admin\Layout\Row;
use Dcat\Admin\Widgets\Tab;

/**
 * @property DeviceRecord device
 * @property int id
 * @property float price
 * @property string purchased
 * @property DateTime deleted_at
 * @property string asset_number
 *
 * @method device()
 * @method track()
 */
class PartRecordController extends AdminController
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
        $tab->addLink(Data::icon('category') . trans('main.category'), admin_route('part.categories.index'));
        $tab->addLink(Data::icon('track') . trans('main.track'), admin_route('part.tracks.index'));
        $tab->addLink(Data::icon('statistics') . trans('main.statistics'), admin_route('part.statistics'));
        $tab->addLink(Data::icon('column') . trans('main.column'), admin_route('part.columns.index'));
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
        return Grid::make(new PartRecord(['category', 'vendor', 'device', 'depreciation']), function (Grid $grid) {
            $sort_columns = $this->sortColumns();
            $grid->column('id', '', $sort_columns);
            $grid->column('asset_number', '', $sort_columns)->display(function ($asset_number) {
                return "<span class='badge badge-secondary'>$asset_number</span>";
            });
            $grid->column('asset_number_qrcode', '', $sort_columns)->qrcode(function () {
                return 'part:' . $this->asset_number;
            });
            $grid->column('price', '', $sort_columns);
            $grid->column('purchased', '', $sort_columns);
            $grid->column('name', '', $sort_columns);
            $grid->column('description', '', $sort_columns);
            $grid->column('sn', '', $sort_columns);
            $grid->column('category.name', '', $sort_columns);
            $grid->column('vendor.name', '', $sort_columns);
            $grid->column('specification', '', $sort_columns);
            $grid->column('expiration_left_days', '', $sort_columns)->display(function () {
                return ExpirationService::itemExpirationLeftDaysRender('part', $this->id);
            });
            $grid->column('device.asset_number')->link(function () {
                if (!empty($this->device)) {
                    return admin_route('device.records.show', [$this->device()->first()->id]);
                }
            });
            $grid->column('depreciation.name', '', $sort_columns);
            $grid->column('created_at', '', $sort_columns);
            $grid->column('updated_at', '', $sort_columns);

            /**
             * 自定义字段.
             */
            ControllerHasCustomColumns::makeGrid((new PartRecord())->getTable(), $grid, $sort_columns);

            /**
             * 行操作按钮.
             */
            $grid->actions(function (RowActions $actions) {
                if ($this->deleted_at == null) {
                    // @permissions
                    if (Admin::user()->can('part.record.delete')) {
                        $actions->append(new PartRecordDeleteAction());
                    }
                    // @permissions
                    if (Admin::user()->can('part.record.track.create_update')) {
                        $actions->append(new PartRecordCreateUpdateTrackAction());
                    }
                    // @permissions
                    if (Admin::user()->can('part.maintenance.create')) {
                        $actions->append(new MaintenanceRecordCreateAction($this->asset_number));
                    }
                }
            });

            /**
             * 字段过滤.
             */
            $grid->showColumnSelector();
            $grid->hideColumns([
                'id',
                'description',
                'price',
                'expired'
            ]);

            /**
             * 快速搜索.
             */
            $grid->quickSearch(
                array_merge([
                    'id',
                    'asset_number',
                    'description',
                    'sn',
                    'category.name',
                    'vendor.name',
                    'specification',
                    'device.asset_number',
                ], ControllerHasCustomColumns::makeQuickSearch((new PartRecord())->getTable()))
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
                $filter->equal('category_id')->select(PartCategory::pluck('name', 'id'));
                $filter->equal('vendor_id')->select(VendorRecord::pluck('name', 'id'));
                $filter->equal('device.asset_number');
                $filter->equal('depreciation_id')->select(DepreciationRule::pluck('name', 'id'));
                /**
                 * 自定义字段.
                 */
                ControllerHasCustomColumns::makeFilter((new PartRecord())->getTable(), $filter);
            });

            /**
             * 批量按钮.
             */
            $grid->batchActions(function (BatchActions $batchActions) {
                // @permissions
                if (Admin::user()->can('part.record.batch.delete')) {
                    $batchActions->add(new PartRecordBatchDeleteAction());
                }
                // @permissions
                if (Admin::user()->can('part.record.batch.force.delete')) {
                    $batchActions->add(new PartRecordBatchForceDeleteAction());
                }
            });

            /**
             * 工具按钮.
             */
            $grid->tools(function (Tools $tools) {
                // @permissions
                if (Admin::user()->can('part.record.import')) {
                    $tools->append(new PartRecordImportAction());
                }
            });

            /**
             * 按钮控制.
             */
            $grid->enableDialogCreate();
            $grid->disableDeleteButton();
            $grid->disableBatchDelete();
            $grid->disableEditButton();
            $grid->toolsWithOutline(false);
            if (!request('_scope_')) {
                // @permissions
                if (!Admin::user()->can('part.record.create')) {
                    $grid->disableCreateButton();
                }
                // @permissions
                if (Admin::user()->can('part.record.update')) {
                    $grid->showQuickEditButton();
                }
            }
            // @permissions
            if (Admin::user()->can('part.record.export')) {
                $grid->export()->rows(function ($rows) {
                    foreach ($rows as $row) {
                        $part = \App\Models\PartRecord::query()
                            ->where('id', $row['id'])
                            ->first();
                        //导出分类定义
                        $row['category.name'] = PartCategory::query()
                            ->where('id', $row['category_id'])
                            ->value('name');
                        //导出厂商定义
                        $row['vendor.name'] = VendorRecord::query()
                            ->where('id', $row['vendor_id'])
                            ->value('name');
                        //导出所属设备定义
                        $row['device.asset_number'] = $part?->device?->asset_number ?? '未知';
                        //导出折旧规则定义
                        $row['depreciation.name'] = $part?->depreciation->name ?? '未知';
                        //导出保固剩余天数定义
                        $row['expiration_left_days'] = ExpirationService::itemExpirationLeftDays('part', $part->id);
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
        return ColumnSort::where('table_name', (new PartRecord())->getTable())
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
        return Show::make($id, new PartRecord(['category', 'vendor', 'device', 'depreciation']), function (Show $show) {
            $sort_columns = $this->sortColumns();
            $show->field('id', '', $sort_columns);
            $show->field('asset_number', '', $sort_columns);
            $show->field('description', '', $sort_columns);
            $show->field('category.name', '', $sort_columns);
            $show->field('vendor.name', '', $sort_columns);
            $show->field('sn', '', $sort_columns);
            $show->field('device.asset_number', '', $sort_columns);
            $show->field('specification', '', $sort_columns);
            $show->field('price', '', $sort_columns);
            $show->field('depreciation_price', '', $sort_columns)->as(function () {
                $part_record = \App\Models\PartRecord::where('id', $this->id)->first();
                if (!empty($part_record)) {
                    $depreciation_rule_id = Support::getDepreciationRuleId($part_record);

                    return Support::depreciationPrice($this->price, $this->purchased, $depreciation_rule_id);
                }
            });
            $show->field('purchased', '', $sort_columns);
            $show->field('expired', '', $sort_columns);
            $show->field('depreciation.name', '', $sort_columns);
            $show->field('depreciation.termination', '', $sort_columns);

            /**
             * 自定义字段.
             */
            ControllerHasCustomColumns::makeDetail((new PartRecord())->getTable(), $show, $sort_columns);

            $show->field('created_at', '', $sort_columns);
            $show->field('updated_at', '', $sort_columns);

            /**
             * 自定义按钮.
             */
            $show->tools(function (\Dcat\Admin\Show\Tools $tools) {
                // @permissions
                if (Admin::user()->can('part.track.delete') && !empty($this->track()->first())) {
                    $tools->append(new PartRecordDeleteTrackAction());
                }
                // @permissions
                if (Admin::user()->can('part.record.track.create_update') && empty($this->track()->first())) {
                    $tools->append(new \App\Admin\Actions\Show\PartRecordCreateUpdateTrackAction());
                }
                $tools->append('&nbsp;');
            });

            /**
             * 按钮控制.
             */
            $show->disableDeleteButton();
            // @permissions
            if (!Admin::user()->can('part.record.update')) {
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
        return Form::make(new PartRecord(), function (Form $form) {
            $form->display('id');

            $form->row(function (\Dcat\Admin\Form\Row $row) use ($form) {
                $row->width(6)->text('name')->required();
                if ($form->isCreating() || empty($form->model()->asset_number)) {
                    $row->text('asset_number')->required();
                } else {
                    $row->display('asset_number')->required();
                }
                $row->width(6)
                    ->select('category_id', admin_trans_label('Category'))
                    ->options(PartCategory::selectOptions())
                    ->required();
                $row->width(6)
                    ->text('specification')->required();
                $row->width(6)
                    ->select('vendor_id', admin_trans_label('Vendor'))
                    ->options(VendorRecord::pluck('name', 'id'))
                    ->required();
                $row->width(6)
                    ->currency('price');
                $row->width(6)->text('description');
                $row->width(6)->text('sn');
                $row->width(6)->date('purchased');
                $row->width(6)->date('expired');
                $row->width(6)->select('depreciation_rule_id', admin_trans_label('Depreciation Rule'))
                    ->options(DepreciationRule::pluck('name', 'id'));

                /**
                 * 自定义字段
                 */
                foreach (ControllerHasCustomColumns::getCustomColumns((new PartRecord())->getTable()) as $custom_column) {
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

            $form->saving(function (Form $form) {
                if ($form->isCreating() || empty($form->model()->asset_number)) {
                    $return = Support::ifAssetNumberUsed($form->input('asset_number'));
                    if ($return) {
                        return $form->response()
                            ->error(trans('main.asset_number_exist'));
                    }
                }
            });
        });
    }
}

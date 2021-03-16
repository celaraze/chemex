<?php

namespace App\Admin\Controllers;

use App\Admin\Actions\Grid\BatchAction\PartRecordBatchDeleteAction;
use App\Admin\Actions\Grid\RowAction\MaintenanceRecordCreateAction;
use App\Admin\Actions\Grid\RowAction\PartRecordCreateUpdateTrackAction;
use App\Admin\Actions\Grid\RowAction\PartRecordDeleteAction;
use App\Admin\Actions\Grid\ToolAction\PartRecordImportAction;
use App\Admin\Grid\Displayers\RowActions;
use App\Admin\Repositories\PartRecord;
use App\Grid;
use App\Models\ColumnSort;
use App\Models\DepreciationRule;
use App\Models\DeviceRecord;
use App\Models\PartCategory;
use App\Models\PurchasedChannel;
use App\Models\VendorRecord;
use App\Services\ExpirationService;
use App\Support\Data;
use App\Support\Support;
use App\Traits\ControllerHasCustomColumns;
use Dcat\Admin\Admin;
use Dcat\Admin\Form;
use Dcat\Admin\Grid\Tools;
use Dcat\Admin\Grid\Tools\BatchActions;
use Dcat\Admin\Http\Controllers\AdminController;
use Dcat\Admin\Layout\Content;
use Dcat\Admin\Layout\Row;
use Dcat\Admin\Show;
use Dcat\Admin\Widgets\Tab;

/**
 * @property DeviceRecord device
 * @property int id
 * @property float price
 * @property string purchased
 *
 * @method device()
 */
class PartRecordController extends AdminController
{
    public function index(Content $content): Content
    {
        return $content
            ->title($this->title())
            ->description(admin_trans_label('description'))
            ->body(function (Row $row) {
                $tab = new Tab();
                $tab->add(Data::icon('record').trans('main.record'), $this->grid(), true);
                $tab->addLink(Data::icon('category').trans('main.category'), admin_route('part.categories.index'));
                $tab->addLink(Data::icon('track').trans('main.track'), admin_route('part.tracks.index'));
                $tab->addLink(Data::icon('statistics').trans('main.statistics'), admin_route('part.statistics'));
                $tab->addLink(Data::icon('column').trans('main.column'), admin_route('part.columns.index'));
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
        return Grid::make(new PartRecord(['category', 'vendor', 'device', 'depreciation']), function (Grid $grid) {
            $column_sort = ColumnSort::where('table_name', (new PartRecord())->getTable())
                ->get(['field', 'order'])
                ->toArray();
            $grid->column('id', '', $column_sort);
            $grid->column('qrcode', '', $column_sort)->qrcode(function () {
                return 'part:'.$this->id;
            }, 200, 200);
            $grid->column('price', '', $column_sort);
            $grid->column('purchased', '', $column_sort);
            $grid->column('asset_number', '', $column_sort);
            $grid->column('name', '', $column_sort);
            $grid->column('description', '', $column_sort);
            $grid->column('category.name', '', $column_sort);
            $grid->column('vendor.name', '', $column_sort);
            $grid->column('specification', '', $column_sort);
            $grid->column('expiration_left_days', '', $column_sort)->display(function () {
                return ExpirationService::itemExpirationLeftDaysRender('part', $this->id);
            });
            $grid->column('device.name')->link(function () {
                if (!empty($this->device)) {
                    return admin_route('device.records.show', [$this->device()->first()->id]);
                }
            });
            $grid->column('depreciation.name', '', $column_sort);
            $grid->column('created_at', '', $column_sort);
            $grid->column('updated_at', '', $column_sort);

            /**
             * 自定义字段.
             */
            ControllerHasCustomColumns::makeGrid((new PartRecord())->getTable(), $grid, $column_sort);

            /**
             * 行操作按钮.
             */
            $grid->actions(function (RowActions $actions) {
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
                    $actions->append(new MaintenanceRecordCreateAction('part'));
                }
            });

            /**
             * 字段过滤.
             */
            $grid->showColumnSelector();
            $grid->hideColumns(['description', 'price', 'expired']);

            /**
             * 快速搜索.
             */
            $grid->quickSearch(
                array_merge([
                    'id',
                    'name',
                    'asset_number',
                    'description',
                    'category.name',
                    'vendor.name',
                    'specification',
                    'device.name',
                ], ControllerHasCustomColumns::makeQuickSearch((new PartRecord())->getTable()))
            )
                ->placeholder(trans('main.quick_search'))
                ->auto(false);

            /**
             * 筛选.
             */
            $grid->filter(function ($filter) {
                $filter->equal('category_id')->select(PartCategory::pluck('name', 'id'));
                $filter->equal('vendor_id')->select(VendorRecord::pluck('name', 'id'));
                $filter->equal('device.name');
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
            $grid->toolsWithOutline(false);
            // @permissions
            if (!Admin::user()->can('part.record.create')) {
                $grid->disableCreateButton();
            }
            // @permissions
            if (!Admin::user()->can('part.record.update')) {
                $grid->disableEditButton();
            }
            // @permissions
            if (Admin::user()->can('part.record.export')) {
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
        return Show::make($id, new PartRecord(['category', 'vendor', 'channel', 'device', 'depreciation']), function (Show $show) {
            $show->field('id');
            $show->field('name');
            $show->field('asset_number');
            $show->field('description');
            $show->field('category.name');
            $show->field('vendor.name');
            $show->field('channel.name');
            $show->field('device.name');
            $show->field('specification');
            $show->field('price');
            $show->field('expiration_left_days')->as(function () {
                $part_record = \App\Models\PartRecord::where('id', $this->id)->first();
                if (!empty($part_record)) {
                    $depreciation_rule_id = Support::getDepreciationRuleId($part_record);

                    return Support::depreciationPrice($this->price, $this->purchased, $depreciation_rule_id);
                }
            });
            $show->field('purchased');
            $show->field('expired');
            $show->field('depreciation.name');
            $show->field('depreciation.termination');

            /**
             * 自定义字段.
             */
            ControllerHasCustomColumns::makeDetail((new PartRecord())->getTable(), $show);

            $show->field('created_at');
            $show->field('updated_at');

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
            $form->text('name')->required();

            if (Support::ifSelectCreate()) {
                $form->selectCreate('category_id', admin_trans_label('Category'))
                    ->options(PartCategory::class)
                    ->ajax(admin_route('selection.part.categories'))
                    ->url(admin_route('part.categories.create'))
                    ->required();
            } else {
                $form->select('category_id', admin_trans_label('Category'))
                    ->options(PartCategory::selectOptions())
                    ->required();
            }

            $form->text('specification')->required();

            if (Support::ifSelectCreate()) {
                $form->selectCreate('vendor_id', admin_trans_label('Vendor'))
                    ->options(VendorRecord::class)->ajax(admin_route('selection.vendor.records'))
                    ->ajax(admin_route('selection.vendor.records'))
                    ->url(admin_route('vendor.records.create'))
                    ->required();
            } else {
                $form->select('vendor_id', admin_trans_label('Vendor'))
                    ->options(VendorRecord::pluck('name', 'id'))
                    ->required();
            }

            $form->divider();
            $form->text('asset_number');
            $form->text('description');

            if (Support::ifSelectCreate()) {
                $form->selectCreate('purchased_channel_id', admin_trans_label('Purchased Channel'))
                    ->options(PurchasedChannel::class)->ajax(admin_route('selection.purchased.channels'))
                    ->ajax(admin_route('selection.purchased.channels'))
                    ->url(admin_route('purchased.channels.create'));
            } else {
                $form->select('purchased_channel_id', admin_trans_label('Purchased Channel'))
                    ->options(PurchasedChannel::pluck('name', 'id'));
            }

            $form->currency('price');
            $form->date('purchased');
            $form->date('expired');

            if (Support::ifSelectCreate()) {
                $form->selectCreate('depreciation_rule_id', admin_trans_label('Depreciation Rule'))
                    ->options(DepreciationRule::class)
                    ->ajax(admin_route('selection.depreciation.rules'))
                    ->url(admin_route('depreciation.rules.create'));
            } else {
                $form->select('depreciation_rule_id', admin_trans_label('Depreciation Rule'))
                    ->options(DepreciationRule::pluck('name', 'id'));
            }

            /**
             * 自定义字段.
             */
            ControllerHasCustomColumns::makeForm((new PartRecord())->getTable(), $form);

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

<?php

namespace App\Admin\Controllers;

use App\Admin\Actions\Grid\ToolAction\ConsumableInAction;
use App\Admin\Actions\Grid\ToolAction\ConsumableOutAction;
use App\Admin\Repositories\ConsumableRecord;
use App\Admin\Repositories\DeviceRecord;
use App\Grid;
use App\Models\ColumnSort;
use App\Models\ConsumableCategory;
use App\Models\VendorRecord;
use App\Support\Data;
use App\Support\Support;
use App\Traits\ControllerHasCustomColumns;
use Dcat\Admin\Admin;
use Dcat\Admin\Form;
use Dcat\Admin\Grid\Tools;
use Dcat\Admin\Http\Controllers\AdminController;
use Dcat\Admin\Layout\Content;
use Dcat\Admin\Layout\Row;
use Dcat\Admin\Show;
use Dcat\Admin\Widgets\Tab;

/**
 * @method allCounts()
 */
class ConsumableRecordController extends AdminController
{
    public function index(Content $content): Content
    {
        return $content
            ->title($this->title())
            ->description(admin_trans_label('description'))
            ->body(function (Row $row) {
                $tab = new Tab();
                $tab->add(Data::icon('record').trans('main.record'), $this->grid(), true);
                $tab->addLink(Data::icon('category').trans('main.category'), admin_route('consumable.categories.index'));
                $tab->addLink(Data::icon('track').trans('main.history'), admin_route('consumable.tracks.index'));
                $tab->addLink(Data::icon('column').trans('main.column'), admin_route('consumable.columns.index'));
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
        return Grid::make(new ConsumableRecord(['category', 'vendor']), function (Grid $grid) {
            $column_sort = ColumnSort::where('table_name', (new DeviceRecord())->getTable())
                ->get(['field', 'order'])
                ->toArray();
            $grid->column('id', '', $column_sort);
            $grid->column('name', '', $column_sort);
            $grid->column('description', '', $column_sort);
            $grid->column('specification', '', $column_sort);
            $grid->column('category.name', '', $column_sort);
            $grid->column('vendor.name', '', $column_sort);
            $grid->column('price', '', $column_sort);
            $grid->column('number', '', $column_sort)->display(function () {
                return $this->allCounts();
            });
            $grid->column('created_at', '', $column_sort);
            $grid->column('updated_at', '', $column_sort);

            /**
             * 自定义字段.
             */
            ControllerHasCustomColumns::makeGrid((new ConsumableRecord())->getTable(), $grid, $column_sort);

            /**
             * 字段过滤.
             */
            $grid->showColumnSelector();

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
                $filter->equal('category_id')->select(ConsumableCategory::pluck('name', 'id'));
                $filter->equal('vendor_id')->select(VendorRecord::pluck('name', 'id'));
                /**
                 * 自定义字段.
                 */
                ControllerHasCustomColumns::makeFilter((new ConsumableRecord())->getTable(), $filter);
            });

            //TODO 耗材的删除功能（逻辑和权限）
            $grid->disableBatchDelete();
            $grid->disableDeleteButton();
            $grid->enableDialogCreate();
            $grid->toolsWithOutline(false);
            // @permissions
            if (!Admin::user()->can('consumable.record.create')) {
                $grid->disableCreateButton();
            }
            // @permissions
            if (!Admin::user()->can('consumable.record.update')) {
                $grid->disableEditButton();
            }
            // @permissions
            if (Admin::user()->can('consumable.record.export')) {
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
        return Show::make($id, new ConsumableRecord(['category', 'vendor']), function (Show $show) {
            $show->field('id');
            $show->field('name');
            $show->field('description');
            $show->field('specification');
            $show->field('category.name');
            $show->field('vendor.name');
            $show->field('price');

            /**
             * 自定义字段.
             */
            ControllerHasCustomColumns::makeDetail((new ConsumableRecord())->getTable(), $show);

            $show->field('created_at');
            $show->field('updated_at');

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
            $form->display('id');
            $form->text('name')
                ->required();
            $form->text('specification')
                ->required();
            if (Support::ifSelectCreate()) {
                $form->selectCreate('category_id')
                    ->options(ConsumableCategory::class)
                    ->ajax(admin_route('selection.consumable.categories'))
                    ->url(admin_route('consumable.categories.create'))
                    ->required();
                $form->selectCreate('vendor_id')
                    ->options(VendorRecord::class)
                    ->ajax(admin_route('selection.vendor.records'))
                    ->url(admin_route('vendor.records.create'))
                    ->required();
            } else {
                $form->select('category_id')
                    ->options(ConsumableCategory::pluck('name', 'id'))
                    ->required();
                $form->select('vendor_id')
                    ->options(VendorRecord::pluck('name', 'id'))
                    ->required();
            }
            $form->divider();
            $form->text('description');
            $form->text('price');

            /**
             * 自定义字段.
             */
            ControllerHasCustomColumns::makeForm((new ConsumableRecord())->getTable(), $form);

            $form->display('created_at');
            $form->display('updated_at');
        });
    }
}

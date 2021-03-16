<?php

namespace App\Traits;

use App\Admin\Actions\Tree\ToolAction\CustomColumnDeleteAction;
use App\Admin\Actions\Tree\ToolAction\CustomColumnUpdateAction;
use App\Admin\Repositories\ConsumableRecord;
use App\Admin\Repositories\DeviceRecord;
use App\Admin\Repositories\PartRecord;
use App\Admin\Repositories\ServiceRecord;
use App\Admin\Repositories\SoftwareRecord;
use App\Models\ColumnSort;
use App\Models\CustomColumn;
use App\Support\Data;
use Dcat\Admin\Admin;
use Dcat\Admin\Form;
use Dcat\Admin\Layout\Column;
use Dcat\Admin\Layout\Row;
use Dcat\Admin\Tree;
use Dcat\Admin\Widgets\Box;
use Dcat\Admin\Widgets\Form as WidgetForm;
use Pour\Plus\LaravelAdmin;

trait ControllerHasColumnSort
{
    /**
     * 页面渲染.
     *
     * @return Row
     */
    protected function render(): Row
    {
        return new Row(function (Column $column) {
            $column->row(function (Row $row) {
                if ($this->creatable()) {
                    $row->column(6, $this->treeView());
                    $row->column(6, $this->createBox());
                } else {
                    $row->column(12, $this->treeView());
                }
            });
        });
    }

    /**
     * 判断当前实例和权限，是否有可创建的权限.
     *
     * @return bool
     */
    public function creatable(): bool
    {
        $repository = $this->repository();
        $creatable = false;
        // @permissions
        if ($repository instanceof DeviceRecord && Admin::user()->can('device.column.create')) {
            $creatable = true;
        }
        if ($repository instanceof PartRecord && Admin::user()->can('part.column.create')) {
            $creatable = true;
        }
        if ($repository instanceof SoftwareRecord && Admin::user()->can('software.column.create')) {
            $creatable = true;
        }
        if ($repository instanceof ConsumableRecord && Admin::user()->can('consumable.column.create')) {
            $creatable = true;
        }
        if ($repository instanceof ServiceRecord && Admin::user()->can('service.column.create')) {
            $creatable = true;
        }

        return $creatable;
    }

    /**
     * 模型树渲染，也就是字段排序的那块.
     *
     * @return Tree
     */
    protected function treeView(): Tree
    {
        $repository = $this->repository();
        $repository = new $repository();

        return new Tree($repository, function (Tree $tree) use ($repository) {
            $tree->maxDepth(1);

            /**
             * 工具按钮.
             */
            $tree->tools(function (Tree\Tools $tools) use ($repository) {
                if ($this->deletable()) {
                    $tools->add(new CustomColumnDeleteAction($repository->getTable()));
                }
//                $tools->add(new CustomColumnUpdateAction($repository->getTable()));
            });

            /**
             * 行操作按钮.
             */
            $tree->actions(function (Tree\Actions $actions) {
                $actions->disableQuickEdit();
                $actions->disableEdit();
                $actions->disableDelete();
            });

            /**
             * 按钮控制.
             */
            $tree->disableCreateButton();
            $tree->disableQuickCreateButton();
            $tree->disableDeleteButton();
        });
    }

    /**
     * 判断当前实例和权限，是否有可创建的权限.
     *
     * @return bool
     */
    public function deletable(): bool
    {
        $repository = $this->repository();
        $deletable = false;
        // @permissions
        if ($repository instanceof DeviceRecord && Admin::user()->can('device.column.delete')) {
            $deletable = true;
        }
        if ($repository instanceof PartRecord && Admin::user()->can('part.column.delete')) {
            $deletable = true;
        }
        if ($repository instanceof SoftwareRecord && Admin::user()->can('software.column.delete')) {
            $deletable = true;
        }
        if ($repository instanceof ConsumableRecord && Admin::user()->can('consumable.column.delete')) {
            $deletable = true;
        }
        if ($repository instanceof ServiceRecord && Admin::user()->can('service.column.delete')) {
            $deletable = true;
        }

        return $deletable;
    }

    /**
     * 表单渲染，也就是新建字段那块.
     *
     * @return Box
     */
    protected function createBox(): Box
    {
        $form = new WidgetForm();
        $form->text('name')
            ->help(admin_trans_label('Name Help'))
            ->required();
        $form->text('nick_name')
            ->help(admin_trans_label('Nick Name Help'))
            ->required();
        $form->select('type')
            ->when('select', function (WidgetForm $form) {
                $form->table('select_options', function (Form\NestedForm $table) {
                    $table->text('item');
                });
            })
            ->options(Data::customColumnTypes())
            ->required();
        $form->radio('is_nullable')
            ->options(LaravelAdmin::yesOrNo())
            ->help(admin_trans_label('Is Nullable Help'))
            ->default(0);

        return Box::make(trans('admin.new'), $form);
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form(): Form
    {
        $repository = $this->repository();
        $repository = new $repository();

        return Form::make($repository, function (Form $form) use ($repository) {
            /**
             * 拦截saving回调，是为了实现字段排序，因为默认是对DeviceRecord做数据处理
             * 但是DeviceRecord重写了数据仓库中toTree方法后，就变成了模型树排序也会走到这个方法中
             * 因此要做拦截，然后在拦截中做判断.
             */
            $form->saving(function (Form $form) use ($repository) {
                $table_name = $repository->getTable();
                /**
                 * 如果请求中包含了_order字段，就表示这个请求是排序，而不是DeviceRecord的表单提交
                 * 这里做判断是为了区别是排序还是对DeviceRecord数据做处理.
                 */
                if (request()->has('_order')) {
                    // orders的索引代表排序，orders['id']代表现在数据表中的排序
                    $needle_columns = $repository->sortNeedleColumns($table_name);
                    $orders = request('_order');
                    $orders = json_decode($orders, true);
                    foreach ($orders as $key => $order) {
                        $column_name = $needle_columns[$order['id']];
                        $column_sort = ColumnSort::where('table_name', $table_name)
                            ->where('field', $column_name)
                            ->first();
                        if (empty($column_sort)) {
                            $column_sort = new ColumnSort();
                        }
                        $column_sort->table_name = $table_name;
                        $column_sort->field = $column_name;
                        $column_sort->order = $key;
                        $column_sort->save();
                    }

                    return $form->response()
                        ->success(trans('main.success'))
                        ->refresh();
                } else {
                    /**
                     * 这里是对字段创建拦截处理，拦截表单提交后自行代码实现字段新建的动作
                     * 然后直接return请求，达到拦截并转而创建字段的目的.
                     */
                    $exist = CustomColumn::where('table_name', $table_name)
                        ->where('name', $form->input('name'))
                        ->first();
                    if (!empty($exist)) {
                        return $form->response()
                            ->error(trans('main.record_same'));
                    }
                    $custom_column = new CustomColumn();
                    $custom_column->table_name = $table_name;
                    $custom_column->name = $form->input('name');
                    $custom_column->nick_name = $form->input('nick_name');
                    $custom_column->type = $form->input('type');
                    if ($custom_column->type == 'select') {
                        $custom_column->select_options = $form->input('select_options');
                    }
                    $custom_column->is_nullable = $form->input('is_nullable');
                    $custom_column->save();

                    return $form->response()
                        ->refresh();
                }
            });
        });
    }
}

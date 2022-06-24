<?php

namespace App\Admin\Controllers;

use App\Admin\Actions\Grid\RowAction\TodoRecordUpdateAction;
use App\Admin\Actions\Grid\ToolAction\TodoRecordCreateAction;
use App\Admin\Grid\Displayers\RowActions;
use App\Admin\Repositories\TodoRecord;
use App\Form;
use App\Grid;
use App\Models\ColumnSort;
use App\Models\DeviceRecord;
use App\Support\Data;
use App\Support\Support;
use Dcat\Admin\Admin;
use Dcat\Admin\Grid\Tools;
use Dcat\Admin\Http\Controllers\AdminController;
use Dcat\Admin\Layout\Column;
use Dcat\Admin\Layout\Content;
use Dcat\Admin\Layout\Row;
use Dcat\Admin\Show;
use Illuminate\Contracts\Translation\Translator;

/**
 * @property  DeviceRecord device
 * @property  int id
 * @property  string deleted_at
 */
class TodoRecordController extends AdminController
{
    public function index(Content $content): Content
    {
        return $content
            ->title($this->title())
            ->description(admin_trans_label('description'))
            ->body(function (Row $row) {
                $row->column(12, function (Column $column) {
                    $column->row(function (Row $row) {
                    });
                });
                $row->column(12, $this->grid());
            });
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
        return Grid::make(new TodoRecord(['user']), function (Grid $grid) {
            $column_sort = ColumnSort::where('table_name', TodoRecord::getTable())->get(['name', 'order'])->toArray();
            if (!Admin::user()->isAdministrator()) {
                $grid->model()->where('user_id', Admin::user()->id);
            }
            $grid->column('id', '', $column_sort);
            $grid->column('name', '', $column_sort);
            $grid->column('description', '', $column_sort);
            $grid->column('start', '', $column_sort);
            $grid->column('end', '', $column_sort);
            $grid->column('priority', '', $column_sort)->using(Data::priority());
            $grid->column('user.name', '', $column_sort);
            $grid->column('tags', '', $column_sort)->explode()->label();
            $grid->column('done_description', '', $column_sort);
            $grid->column('emoji', '', $column_sort)->using(Data::emoji());
            $grid->column('created_at', '', $column_sort);
            $grid->column('updated_at', '', $column_sort);

            /**
             * 行操作按钮.
             */
            $grid->actions(function (RowActions $actions) {
                // @permissions
                if (empty($this->end) && Admin::user()->can('todo.record.update')) {
                    $actions->append(new TodoRecordUpdateAction());
                }
            });

            /**
             * 工具按钮.
             */
            $grid->tools(function (Tools $tools) {
                // @permissions
                if (Admin::user()->can('todo.record.create')) {
                    $tools->append(new TodoRecordCreateAction());
                }
            });

            /**
             * 字段过滤.
             */
            $grid->showColumnSelector();

            /**
             * 筛选.
             */
            $grid->filter(function ($filter) {
                if (admin_setting('switch_to_filter_panel')) {
                    $filter->panel();
                }
                $filter->equal('user_id')->select(Support::selectUsers('id'));
            });

            /**
             * 按钮控制.
             */
            // @permissions
            if (!Admin::user()->can('todo.record.delete')) {
                $grid->disableDeleteButton();
            }
            // @permissions
            if (!Admin::user()->can('todo.record.batch.delete')) {
                $grid->disableBatchDelete();
            }
            $grid->disableCreateButton();
            $grid->disableEditButton();
            $grid->toolsWithOutline(false);
            // @permissions
            if (Admin::user()->can('todo.record.export')) {
                $grid->export();
            }
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
        return Show::make($id, new TodoRecord(['user']), function (Show $show) {
            $show->field('id');
            $show->field('name');
            $show->field('description');
            $show->field('start')->width(6);
            $show->field('priority')
                ->using(Data::priority())
                ->width(6);
            $show->field('end');
            $show->field('user.name');
            $show->field('tags');
            $show->field('done_description');
            $show->field('emoji');
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
     * @return \App\Form
     */
    protected function form(): Form
    {
        return Form::make(new TodoRecord(), function (Form $form) {

        });
    }
}

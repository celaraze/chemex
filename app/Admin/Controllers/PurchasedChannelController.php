<?php

namespace App\Admin\Controllers;

use App\Admin\Actions\Grid\ToolAction\PurchasedChannelImportAction;
use App\Admin\Repositories\PurchasedChannel;
use Dcat\Admin\Admin;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Http\Controllers\AdminController;
use Dcat\Admin\Layout\Content;
use Dcat\Admin\Show;
use Illuminate\Http\Request;

class PurchasedChannelController extends AdminController
{
    /**
     * @param Request $request
     *
     * @return mixed
     */
    public function selectList(Request $request)
    {
        $q = $request->get('q');

        return \App\Models\PurchasedChannel::where('name', 'like', "%$q%")
            ->paginate(null, ['id', 'name as text']);
    }

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
        return Grid::make(new PurchasedChannel(), function (Grid $grid) {
            $grid->column('id');
            $grid->column('name');
            $grid->column('description');
            $grid->column('created_at');
            $grid->column('updated_at');

            /**
             * 工具按钮.
             */
            $grid->tools(function (Grid\Tools $tools) {
                // @permissions
                if (Admin::user()->can('purchased.channel.import')) {
                    $tools->append(new PurchasedChannelImportAction());
                }
            });

            /**
             * 快速搜索.
             */
            $grid->quickSearch('id', 'name', 'description')
                ->placeholder(trans('main.quick_search'))
                ->auto(false);

            /**
             * 按钮控制.
             */
            // @permissions
            if (!Admin::user()->can('purchased.channel.create')) {
                $grid->disableCreateButton();
            }
            // @permissions
            if (!Admin::user()->can('purchased.channel.update')) {
                $grid->disableEditButton();
            }
            // @permissions
            if (!Admin::user()->can('purchased.channel.delete')) {
                $grid->disableDeleteButton();
            }
            // @permissions
            if (!Admin::user()->can('purchased.channel.batch.delete')) {
                $grid->disableBatchDelete();
            }
            $grid->toolsWithOutline(false);
            // @permissions
            if (Admin::user()->can('purchased.channel.export')) {
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
        return Show::make($id, new PurchasedChannel(), function (Show $show) {
            $show->field('id');
            $show->field('name');
            $show->field('description');
            $show->field('created_at');
            $show->field('updated_at');

            // @permissions
            if (!Admin::user()->can('purchased.channel.update')) {
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
        return Form::make(new PurchasedChannel(), function (Form $form) {
            $form->display('id');
            $form->text('name')->required();
            $form->text('description');
            $form->display('created_at');
            $form->display('updated_at');

            /**
             * 按钮控制.
             */
            $form->disableCreatingCheck();
            $form->disableEditingCheck();
            $form->disableViewCheck();
        });
    }
}

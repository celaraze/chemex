<?php

namespace App\Admin\Controllers;

use App\Admin\Repositories\DepreciationRule;
use App\Form;
use Dcat\Admin\Admin;
use Dcat\Admin\Form\NestedForm;
use Dcat\Admin\Grid;
use Dcat\Admin\Http\Controllers\AdminController;
use Dcat\Admin\Layout\Content;
use Dcat\Admin\Show;
use Illuminate\Http\Request;

class DepreciationRuleController extends AdminController
{
    /**
     * @param Request $request
     *
     * @return mixed
     */
    public function selectList(Request $request)
    {
        $q = $request->get('q');

        return \App\Models\DepreciationRule::where('name', 'like', "%$q%")
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
        return Grid::make(new DepreciationRule(), function (Grid $grid) {
            $grid->column('id');
            $grid->column('name');
            $grid->column('description');

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
            if (!Admin::user()->can('depreciation.rule.create')) {
                $grid->disableCreateButton();
            }
            // @permissions
            if (!Admin::user()->can('depreciation.rule.update')) {
                $grid->disableEditButton();
            }
            // @permissions
            if (!Admin::user()->can('depreciation.rule.delete')) {
                $grid->disableDeleteButton();
            }
            // @permissions
            if (!Admin::user()->can('depreciation.rule.batch.delete')) {
                $grid->disableBatchDelete();
            }
            $grid->toolsWithOutline(false);
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
        return Show::make($id, new DepreciationRule(), function (Show $show) {
            $show->field('id');
            $show->field('name');
            $show->field('description');
            $show->field('rules')->view('depreciation_rules.rules');
            $show->field('created_at');
            $show->field('updated_at');

            // @permissions
            if (!Admin::user()->can('depreciation.rule.update')) {
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
        return Form::make(new DepreciationRule(), function (Form $form) {
            $form->display('id');
            $form->text('name')->required();
            $form->text('description')
                ->help(admin_trans_label('Description Help'));
            $form->table('rules', function (NestedForm $table) {
                $table->number('number')
                    ->min(0)
                    ->required();
                $table->select('scale')
                    ->options([
                        'day' => '天',
                        'month' => '月',
                        'year' => '年',
                    ])
                    ->required();
                $table->currency('ratio')
                    ->symbol(admin_trans_label('Rules Symbol'))
                    ->required();
            });
            $form->display('created_at');
            $form->display('updated_at');
        });
    }
}

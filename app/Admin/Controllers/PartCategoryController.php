<?php

namespace App\Admin\Controllers;

use App\Admin\Actions\Tree\ToolAction\PartCategoryImportAction;
use App\Admin\Repositories\PartCategory;
use App\Form;
use App\Models\DepreciationRule;
use App\Support\Data;
use App\Traits\ControllerHasTab;
use Dcat\Admin\Admin;
use Dcat\Admin\Http\Controllers\AdminController;
use Dcat\Admin\Layout\Row;
use Dcat\Admin\Tree;
use Dcat\Admin\Widgets\Tab;
use Illuminate\Http\Request;

class PartCategoryController extends AdminController
{
    use ControllerHasTab;

    /**
     * ajax联动选择.
     * @param Request $request
     * @return mixed
     */
    public function selectList(Request $request): mixed
    {
        $q = $request->get('q');

        return \App\Models\PartCategory::where('name', 'like', "%$q%")
            ->paginate(null, ['id', 'name as text']);
    }

    /**
     * 标签布局.
     * @return Row
     */
    public function tab(): Row
    {
        $row = new Row();
        $tab = new Tab();
        $tab->addLink(Data::icon('record') . trans('main.record'), admin_route('part.records.index'));
        $tab->add(Data::icon('category') . trans('main.category'), $this->renderGrid(), true);
        $tab->addLink(Data::icon('track') . trans('main.track'), admin_route('part.tracks.index'));
        $tab->addLink(Data::icon('statistics') . trans('main.statistics'), admin_route('part.statistics'));
        $tab->addLink(Data::icon('column') . trans('main.column'), admin_route('part.columns.index'));
        $row->column(12, $tab);
        return $row;
    }

    /**
     * 重写渲染为tree.
     * @return Tree
     */
    public function renderGrid(): Tree
    {
        return $this->treeView();
    }

    /**
     * 模型树构建.
     * @return Tree
     */
    protected function treeView(): Tree
    {
        return new Tree(new \App\Models\PartCategory(), function (Tree $tree) {
            /**
             * 工具按钮.
             */
            $tree->tools(function (Tree\Tools $tools) {
                // @permissions
                if (Admin::user()->can('part.category.import')) {
                    $tools->add(new PartCategoryImportAction());
                }
            });

            /**
             * 按钮控制.
             */
            // @permissions
            if (!Admin::user()->can('part.category.create')) {
                $tree->disableQuickCreateButton();
            }
            // @permissions
            if (!Admin::user()->can('part.category.update')) {
                $tree->disableEditButton();
                $tree->disableQuickEditButton();
            }
            // @permissions
            if (!Admin::user()->can('part.category.delete')) {
                $tree->disableDeleteButton();
            }
            $tree->disableCreateButton();
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form(): Form
    {
        return Form::make(new PartCategory(['parent', 'depreciation']), function (Form $form) {
            $form->display('id');
            $form->text('name')->required();
            $form->text('description');
            $form->select('parent_id')
                ->options(\App\Models\PartCategory::pluck('name', 'id'));
            $form->select('depreciation_rule_id')
                ->options(DepreciationRule::pluck('name', 'id'));

            $form->display('created_at');
            $form->display('updated_at');

            $form->disableCreatingCheck();
            $form->disableEditingCheck();
            $form->disableViewCheck();
        });
    }
}

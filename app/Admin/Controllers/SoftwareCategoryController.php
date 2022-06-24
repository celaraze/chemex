<?php

namespace App\Admin\Controllers;

use App\Admin\Actions\Tree\ToolAction\SoftwareCategoryImportAction;
use App\Admin\Repositories\SoftwareCategory;
use App\Form;
use App\Support\Data;
use App\Traits\ControllerHasTab;
use Dcat\Admin\Admin;
use Dcat\Admin\Http\Controllers\AdminController;
use Dcat\Admin\Layout\Row;
use Dcat\Admin\Tree;
use Dcat\Admin\Widgets\Tab;
use Illuminate\Http\Request;

class SoftwareCategoryController extends AdminController
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

        return \App\Models\SoftwareCategory::where('name', 'like', "%$q%")
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
        $tab->addLink(Data::icon('record') . trans('main.record'), admin_route('software.records.index'));
        $tab->add(Data::icon('category') . trans('main.category'), $this->renderGrid(), true);
        $tab->addLink(Data::icon('track') . trans('main.track'), admin_route('software.tracks.index'));
        $tab->addLink(Data::icon('statistics') . trans('main.statistics'), admin_route('software.statistics'));
        $tab->addLink(Data::icon('column') . trans('main.column'), admin_route('software.columns.index'));
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
        return new Tree(new \App\Models\SoftwareCategory(), function (Tree $tree) {
            /**
             * 工具按钮.
             */
            $tree->tools(function (Tree\Tools $tools) {
                // @permissions
                if (Admin::user()->can('software.category.import')) {
                    $tools->add(new SoftwareCategoryImportAction());
                }
            });

            /**
             * 按钮控制.
             */
            // @permissions
            if (!Admin::user()->can('software.category.create')) {
                $tree->disableQuickCreateButton();
            }
            // @permissions
            if (!Admin::user()->can('software.category.update')) {
                $tree->disableEditButton();
                $tree->disableQuickEditButton();
            }
            // @permissions
            if (!Admin::user()->can('software.category.delete')) {
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
        return Form::make(new SoftwareCategory(), function (Form $form) {
            $form->display('id');
            $form->text('name')->required();
            $form->text('description');
            $form->select('parent_id')
                ->options(\App\Models\SoftwareCategory::pluck('name', 'id'));

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

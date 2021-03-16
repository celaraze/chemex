<?php

namespace App\Admin\Controllers;

use App\Admin\Actions\Tree\ToolAction\PartCategoryImportAction;
use App\Admin\Repositories\PartCategory;
use App\Models\DepreciationRule;
use App\Support\Data;
use App\Support\Support;
use Dcat\Admin\Admin;
use Dcat\Admin\Form;
use Dcat\Admin\Http\Controllers\AdminController;
use Dcat\Admin\Layout\Content;
use Dcat\Admin\Layout\Row;
use Dcat\Admin\Tree;
use Dcat\Admin\Widgets\Tab;
use Illuminate\Http\Request;

class PartCategoryController extends AdminController
{
    /**
     * @param Request $request
     *
     * @return mixed
     */
    public function selectList(Request $request)
    {
        $q = $request->get('q');

        return \App\Models\PartCategory::where('name', 'like', "%$q%")
            ->paginate(null, ['id', 'name as text']);
    }

    public function index(Content $content): Content
    {
        return $content
            ->title($this->title())
            ->description(admin_trans_label('description'))
            ->body(function (Row $row) {
                $tab = new Tab();
                $tab->addLink(Data::icon('record').trans('main.record'), admin_route('part.records.index'));
                $tab->add(Data::icon('category').trans('main.category'), $this->treeView(), true);
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
            if (Support::ifSelectCreate()) {
                $form->selectCreate('parent_id')
                    ->options(\App\Models\PartCategory::class)
                    ->ajax(admin_route('selection.part.categories'))
                    ->url(admin_route('part.categories.create'));
                $form->selectCreate('depreciation_rule_id')
                    ->options(DepreciationRule::class)
                    ->ajax(admin_route('selection.depreciation.rules'))
                    ->url(admin_route('depreciation.rules.create'));
            } else {
                $form->select('parent_id')
                    ->options(\App\Models\PartCategory::pluck('name', 'id'));
                $form->select('depreciation_rule_id')
                    ->options(DepreciationRule::pluck('name', 'id'));
            }

            $form->display('created_at');
            $form->display('updated_at');

            $form->disableCreatingCheck();
            $form->disableEditingCheck();
            $form->disableViewCheck();
        });
    }
}

<?php

namespace App\Admin\Controllers;

use App\Admin\Actions\Tree\ToolAction\DepartmentImportAction;
use App\Admin\Repositories\Department;
use App\Support\Data;
use App\Support\Support;
use Dcat\Admin\Admin;
use Dcat\Admin\Form;
use Dcat\Admin\Http\Controllers\AdminController;
use Dcat\Admin\Layout\Content;
use Dcat\Admin\Layout\Row;
use Dcat\Admin\Show;
use Dcat\Admin\Tree;
use Dcat\Admin\Widgets\Tab;
use Illuminate\Http\Request;

class DepartmentController extends AdminController
{
    /**
     * @param Request $request
     *
     * @return mixed
     */
    public function selectList(Request $request)
    {
        $q = $request->get('q');

        return \App\Models\Department::where('name', 'like', "%$q%")
            ->paginate(null, ['id', 'name as text']);
    }

    public function index(Content $content): Content
    {
        return $content
            ->title($this->title())
            ->description(admin_trans_label('description'))
            ->body(function (Row $row) {
                $tab = new Tab();
                $tab->addLink(Data::icon('user').admin_trans_label('User'), admin_route('organization.users.index'));
                $tab->add(Data::icon('department').admin_trans_label('Department'), $this->treeView(), true);
                $tab->addLink(Data::icon('role').admin_trans_label('Role'), admin_route('organization.roles.index'));
                $tab->addLink(Data::icon('permission').admin_trans_label('Permission'), admin_route('organization.permissions.index'));
                $row->column(12, $tab);
            });
    }

    public function title()
    {
        return admin_trans_label('title');
    }

    protected function treeView(): Tree
    {
        return new Tree(new \App\Models\Department(), function (Tree $tree) {
            /**
             * 行显示.
             */
            $tree->branch(function ($branch) {
                $display = "{$branch['name']}";
                if ($branch['ad_tag'] === 1) {
                    $display = "<span class='badge badge-primary mr-1'>AD</span>".$display;
                }

                return $display;
            });

            /**
             * 工具按钮.
             */
            $tree->tools(function (Tree\Tools $tools) {
                if (Admin::user()->can('department.import')) {
                    $tools->add(new DepartmentImportAction());
                }
            });

            /**
             * 按钮控制.
             */
            // @permissions
            if (!Admin::user()->can('department.create')) {
                $tree->disableQuickCreateButton();
            }
            // @permissions
            if (!Admin::user()->can('department.update')) {
                $tree->disableEditButton();
                $tree->disableQuickEditButton();
            }
            // @permissions
            if (!Admin::user()->can('department.delete')) {
                $tree->disableDeleteButton();
            }
            $tree->disableCreateButton();
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
        return Show::make($id, new Department(['parent']), function (Show $show) {
            $show->field('id');
            $show->field('name');
            $show->field('description');
            $show->field('parent.name');
            $show->field('created_at');
            $show->field('updated_at');
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form(): Form
    {
        return Form::make(new Department(), function (Form $form) {
            $form->display('id');
            $form->text('name')->required();
            $form->divider();
            $form->text('description');
            if (Support::ifSelectCreate()) {
                $form->selectCreate('parent_id')
                    ->options(\App\Models\Department::class)
                    ->ajax(admin_route('selection.organization.departments'))
                    ->url(admin_route('organization.departments.create'))
                    ->default(0);
            } else {
                $form->select('parent_id')
                    ->options(\App\Models\Department::pluck('name', 'id'))
                    ->default(0);
            }

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

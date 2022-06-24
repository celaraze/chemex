<?php

namespace App\Admin\Controllers;

use App\Admin\Actions\Tree\ToolAction\DepartmentImportAction;
use App\Admin\Repositories\Department;
use App\Form;
use App\Models\Role;
use App\Models\RoleUser;
use App\Models\User;
use App\Support\Data;
use App\Traits\ControllerHasTab;
use Dcat\Admin\Admin;
use Dcat\Admin\Http\Controllers\AdminController;
use Dcat\Admin\Layout\Row;
use Dcat\Admin\Show;
use Dcat\Admin\Tree;
use Dcat\Admin\Widgets\Tab;
use Illuminate\Http\Request;

class DepartmentController extends AdminController
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

        return \App\Models\Department::where('name', 'like', "%$q%")
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
        $tab->addLink(Data::icon('user') . admin_trans_label('User'), admin_route('organization.users.index'));
        $tab->add(Data::icon('department') . admin_trans_label('Department'), $this->renderGrid(), true);
        $tab->addLink(Data::icon('role') . admin_trans_label('Role'), admin_route('organization.roles.index'));
        $tab->addLink(Data::icon('permission') . admin_trans_label('Permission'), admin_route('organization.permissions.index'));
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
        return new Tree(new \App\Models\Department(), function (Tree $tree) {
            /**
             * 行显示.
             */
            $tree->branch(function ($branch) {
                $display = "{$branch['name']}";
                if (!empty($branch['role_id'])) {
                    $role = Role::where('id', $branch['role_id'])->value('name');
                    $users = RoleUser::where('role_id', $branch['role_id'])->get(['user_id']);
                    $users_array = [];
                    foreach ($users as $user) {
                        $user_name = User::where('id', $user->user_id)->value('name');
                        array_push($users_array, $user_name);
                    }
                    $users = implode('，', $users_array);
                    $display = $display . ' - ' . $role . ' : ' . $users;
                }
                if ($branch['ad_tag'] === 1) {
                    $display = "<span class='badge badge-primary mr-1'>AD</span>" . $display;
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
     * @param int $id
     *
     * @return Show
     */
    protected function detail(int $id): Show
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
            $form->select('parent_id')
                ->options(\App\Models\Department::pluck('name', 'id'))
                ->default(0);
            $form->select('role_id')
                ->options(Role::pluck('name', 'id'));


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

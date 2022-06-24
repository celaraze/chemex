<?php

namespace App\Admin\Controllers;

use App\Support\Data;
use App\Traits\ControllerHasTab;
use Dcat\Admin\Admin;
use Dcat\Admin\Grid;
use Dcat\Admin\Http\Controllers\RoleController as BaseRoleController;
use Dcat\Admin\Http\Repositories\Role;
use Dcat\Admin\Layout\Row;
use Dcat\Admin\Models\Role as RoleModel;
use Dcat\Admin\Show;
use Dcat\Admin\Support\Helper;
use Dcat\Admin\Widgets\Tab;
use Dcat\Admin\Widgets\Tree;
use Illuminate\Http\Request;

class RoleController extends BaseRoleController
{
    use ControllerHasTab;

    /**
     * 标签布局.
     * @return Row
     */
    public function tab(): Row
    {
        $row = new Row();
        $tab = new Tab();
        $tab->addLink(Data::icon('user') . admin_trans_label('User'), admin_route('organization.users.index'));
        $tab->addLink(Data::icon('department') . admin_trans_label('Department'), admin_route('organization.departments.index'));
        $tab->add(Data::icon('role') . admin_trans_label('Role'), $this->renderGrid(), true);
        $tab->addLink(Data::icon('permission') . admin_trans_label('Permission'), admin_route('organization.permissions.index'));
        $row->column(12, $tab);
        return $row;
    }

    /**
     * ajax联动选择.
     * @param Request $request
     * @return mixed
     */
    public function selectList(Request $request): mixed
    {
        $q = $request->get('q');

        return \App\Models\Role::where('name', 'like', "%$q%")
            ->paginate(null, ['id', 'name as text']);
    }

    /**
     * 列表页.
     * @return Grid
     */
    protected function grid(): Grid
    {
        return new Grid(new Role(), function (Grid $grid) {
            $grid->column('id', 'ID')->sortable();
            $grid->column('slug')->label('primary');
            $grid->column('name');
            $grid->column('created_at');
            $grid->column('updated_at')->sortable();

            /**
             * 快速搜索.
             */
            $grid->quickSearch(['id', 'name', 'slug'])
                ->placeholder(trans('main.quick_search'))
                ->auto(false);

            /**
             * 行操作按钮.
             */
            $grid->actions(function (Grid\Displayers\Actions $actions) {
                $roleModel = config('admin.database.roles_model');
                // @permissions
                if ($roleModel::isAdministrator($actions->row->slug) || !Admin::user()->can('role.delete')) {
                    $actions->disableDelete();
                }
            });

            /**
             * 按钮控制.
             */
            // @permissions
            if (Admin::user()->can('role.update')) {
                $grid->showQuickEditButton();
            }
            // @permissions
            if (!Admin::user()->can('role.create')) {
                $grid->disableCreateButton();
            }
            // @permissions
            if (!Admin::user()->can('role.delete')) {
                $grid->disableDeleteButton();
            }
            $grid->disableEditButton();
            $grid->enableDialogCreate();
            $grid->disableBatchActions();
            $grid->toolsWithOutline(false);
        });
    }

    /**
     * 详情页.
     * @param $id
     * @return Show
     */
    protected function detail($id): Show
    {
        return Show::make($id, new Role('permissions'), function (Show $show) {
            $show->field('id');
            $show->field('slug');
            $show->field('name');

            $show->field('permissions')->unescape()->as(function ($permission) {
                $permissionModel = config('admin.database.permissions_model');
                $permissionModel = new $permissionModel();
                $nodes = $permissionModel->allNodes();

                $tree = Tree::make($nodes);

                $keyName = $permissionModel->getKeyName();
                $tree->check(
                    array_column(Helper::array($permission), $keyName)
                );

                return $tree->render();
            });

            $show->field('created_at');
            $show->field('updated_at');

            // @permissions
            if ($show->getKey() == RoleModel::ADMINISTRATOR_ID || !Admin::user()->can('role.update')) {
                $show->disableDeleteButton();
            }
        });
    }
}

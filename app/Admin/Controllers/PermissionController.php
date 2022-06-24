<?php

namespace App\Admin\Controllers;

use App\Form;
use App\Support\Data;
use App\Traits\ControllerHasTab;
use Dcat\Admin\Admin;
use Dcat\Admin\Http\Controllers\PermissionController as BasePermissionController;
use Dcat\Admin\Http\Repositories\Permission;
use Dcat\Admin\Layout\Row;
use Dcat\Admin\Tree;
use Dcat\Admin\Widgets\Tab;
use Illuminate\Support\Str;

class PermissionController extends BasePermissionController
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
        $tab->addLink(Data::icon('role') . admin_trans_label('Role'), admin_route('organization.roles.index'));
        $tab->add(Data::icon('permission') . admin_trans_label('Permission'), $this->renderGrid(), true);
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
        $model = config('admin.database.permissions_model');

        return new Tree(new $model(), function (Tree $tree) {
            $tree->disableCreateButton();
            $tree->disableEditButton();

            $tree->branch(function ($branch) {
                $payload = "<div class='pull-left' style='min-width:310px'><b>{$branch['name']}</b>&nbsp;&nbsp;[<span class='text-primary'>{$branch['slug']}</span>]";

                $path = array_filter($branch['http_path']);

                if (!$path) {
                    return $payload . '</div>&nbsp;';
                }

                $max = 3;
                if (count($path) > $max) {
                    $path = array_slice($path, 0, $max);
                    array_push($path, '...');
                }

                $method = $branch['http_method'] ?: [];

                $path = collect($path)->map(function ($path) use (&$method) {
                    if (Str::contains($path, ':')) {
                        [$me, $path] = explode(':', $path);

                        $method = array_merge($method, explode(',', $me));
                    }
                    if ($path !== '...' && !empty(config('admin.route.prefix')) && !Str::contains($path, '.')) {
                        $path = trim(admin_base_path($path), '/');
                    }

                    $color = Admin::color()->primaryDarker();

                    return "<code style='color:$color'>$path</code>";
                })->implode('&nbsp;&nbsp;');

                $method = collect($method ?: ['ANY'])->unique()->map(function ($name) {
                        return strtoupper($name);
                    })->map(function ($name) {
                        return "<span class='label bg-primary'>$name</span>";
                    })->implode('&nbsp;') . '&nbsp;';

                $payload .= "</div>&nbsp; $method<a class=\"dd-nodrag\">$path</a>";

                return $payload;
            });

            // @permissions
            if (!Admin::user()->can('permission.create')) {
                $tree->disableQuickCreateButton();
            }
            // @permissions
            if (!Admin::user()->can('permission.update')) {
                $tree->disableQuickEditButton();
            }
            // @permissions
            if (!Admin::user()->can('permission.delete')) {
                $tree->disableDeleteButton();
            }
        });
    }

    /**
     * 表单页.
     * @return Form|\Dcat\Admin\Form
     */
    public function form(): \Dcat\Admin\Form|Form
    {
        return Form::make(new Permission(), function (Form $form) {
            $permissionTable = config('admin.database.permissions_table');
            $connection = config('admin.database.connection');
            $permissionModel = config('admin.database.permissions_model');

            $id = $form->getKey();

            $form->display('id', 'ID');

            $form->select('parent_id', trans('admin.parent_id'))
                ->options($permissionModel::selectOptions())
                ->saving(function ($v) {
                    return (int)$v;
                });

            $form->text('slug', trans('admin.slug'))
                ->required()
                ->creationRules(['required', "unique:$connection.$permissionTable"])
                ->updateRules(['required', "unique:$connection.$permissionTable,slug,$id"]);
            $form->text('name', trans('admin.name'))->required();

            $form->multipleSelect('http_method', trans('admin.http.method'))
                ->options($this->getHttpMethodsOptions())
                ->help(trans('admin.all_methods_if_empty'));

            $form->tags('http_path', trans('admin.http.path'))
                ->options($this->getRoutes());

            $form->display('created_at', trans('admin.created_at'));
            $form->display('updated_at', trans('admin.updated_at'));

            $form->disableViewButton();
            $form->disableViewCheck();
        });
    }
}

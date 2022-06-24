<?php

namespace App\Admin\Controllers;

use App\Admin\Actions\Grid\BatchAction\UserBatchDeleteAction;
use App\Admin\Actions\Grid\BatchAction\UserBatchForceDeleteAction;
use App\Admin\Actions\Grid\RowAction\UserDeleteAction;
use App\Admin\Actions\Grid\ToolAction\UserImportAction;
use App\Admin\Grid\Displayers\RowActions;
use App\Admin\Repositories\User;
use App\Form;
use App\Models\Department;
use App\Support\Data;
use App\Traits\ControllerHasTab;
use Dcat\Admin\Admin;
use Dcat\Admin\Grid;
use Dcat\Admin\Http\Controllers\UserController as BaseUserController;
use Dcat\Admin\Layout\Row;
use Dcat\Admin\Show;
use Dcat\Admin\Widgets\Tab;
use Dcat\Admin\Widgets\Tree;
use Illuminate\Http\Request;

/**
 * @property int ad_tag
 */
class UserController extends BaseUserController
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
        $tab->add(Data::icon('user') . admin_trans_label('User'), $this->renderGrid(), true);
        $tab->addLink(Data::icon('department') . admin_trans_label('Department'), admin_route('organization.departments.index'));
        $tab->addLink(Data::icon('role') . admin_trans_label('Role'), admin_route('organization.roles.index'));
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

        return \App\Models\User::where('name', 'like', "%$q%")
            ->paginate(null, ['id', 'name as text']);
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    public function form(): Form
    {
        return Form::make(User::with(['roles']), function (Form $form) {
            $userTable = config('admin.database.users_table');
            $connection = config('admin.database.connection');
            $id = $form->getKey();

            $form->display('id');
            $form->text('username', trans('admin.username'))
                ->required()
                ->creationRules(['required', "unique:$connection.$userTable"])
                ->updateRules(['required', "unique:$connection.$userTable,username,$id"]);
            $form->text('name', trans('admin.name'))->required();
            $form->radio('status')->options(['账户冻结', '账号正常'])->default(1);
            $form->select('gender')
                ->options(Data::genders())
                ->required();
            $form->select('department_id', admin_trans_label('Department'))
                ->options(Department::selectOptions())
                ->required();
            $form->divider();

            if ($id) {
                $form->password('password', trans('admin.password'))
                    ->minLength(5)
                    ->maxLength(20)
                    ->customFormat(function () {
                        return '';
                    })
                    ->attribute('autocomplete', 'new-password');
            } else {
                $form->password('password', trans('admin.password'))
                    ->required()
                    ->minLength(5)
                    ->maxLength(20)
                    ->attribute('autocomplete', 'new-password');
            }

            $form->password('password_confirmation', trans('admin.password_confirmation'))->same('password');

            $form->ignore(['password_confirmation']);

            if (config('admin.permission.enable')) {
                $form->multipleSelect('roles', trans('admin.roles'))
                    ->options(function () {
                        $roleModel = config('admin.database.roles_model');

                        return $roleModel::pluck('name', 'id');
                    })
                    ->customFormat(function ($v) {
                        return array_column($v, 'id');
                    });
            }

            $form->image('avatar', trans('admin.avatar'))->autoUpload();
            $form->text('title');
            $form->mobile('mobile');
            $form->email('email');

            $form->display('created_at');
            $form->display('updated_at');

            /**
             * 按钮控制.
             */
            $form->disableDeleteButton();
            $form->disableCreatingCheck();
            $form->disableEditingCheck();
            $form->disableViewCheck();

            if ($id == \App\Models\User::DEFAULT_ID) {
                $form->disableDeleteButton();
            }
        })->saving(function (Form $form) {
            if ($form->password && $form->model()->get('password') != $form->password) {
                $form->password = bcrypt($form->password);
            }

            if (!$form->password) {
                $form->deleteInput('password');
            }

            // 创建用户时通过工号判断是否有相同记录
            $exist = \App\Models\User::where('username', $form->input('username'))
                ->withTrashed()
                ->first();
            if ($form->isEditing() && !empty($exist) && $form->model()->id != $exist->id) {
                return $form->response()
                    ->error(trans('main.record_same'));
            }
            if ($form->isCreating()) {
                if (!empty($exist)) {
                    return $form->response()
                        ->error(trans('main.record_same'));
                }
            }
        });
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid(): Grid
    {
        return Grid::make(User::with(['roles', 'department']), function (Grid $grid) {
            $grid->column('id');
            $grid->column('username');
            $grid->column('name')->display(function ($name) {
                if ($this->ad_tag === 1) {
                    return "<span class='badge badge-primary mr-1'>AD</span>$name";
                }

                return $name;
            });
            $grid->column('gender');
            $grid->column('status')->using(['账户冻结', '账户正常'])->badge([
                'default' => 'success', // 设置默认颜色，不设置则默认为 default
                0 => 'danger',
                1 => 'success'

            ]);
            $grid->column('department.name');
            $grid->column('title');
            $grid->column('mobile');
            $grid->column('email');

            if (config('admin.permission.enable')) {
                $grid->column('roles')->pluck('name')->label('primary', 3);

                $permissionModel = config('admin.database.permissions_model');
                $roleModel = config('admin.database.roles_model');
                $nodes = (new $permissionModel())->allNodes();
                $grid->column('permissions')
                    ->if(function () {
                        return !$this->roles->isEmpty();
                    })
                    ->showTreeInDialog(function (Grid\Displayers\DialogTree $tree) use (&$nodes, $roleModel) {
                        $tree->nodes($nodes);

                        foreach (array_column($this->roles->toArray(), 'slug') as $slug) {
                            if ($roleModel::isAdministrator($slug)) {
                                $tree->checkAll();
                            }
                        }
                    })
                    ->else()
                    ->display('');
            }

            /**
             * 字段过滤.
             */
            $grid->showColumnSelector();
            $grid->hideColumns([
                'title',
                'mobile',
                'email',
            ]);

            /**
             * 批量操作.
             */
            $grid->batchActions(function (Grid\Tools\BatchActions $batchActions) {
                // @permissions
                if (Admin::user()->can('user.batch.delete')) {
                    $batchActions->add(new UserBatchDeleteAction());
                }
                // @permissions
                if (Admin::user()->can('user.batch.force.delete')) {
                    $batchActions->add(new UserBatchForceDeleteAction());
                }
            });

            /**
             * 行内操作.
             */
            $grid->actions(function (RowActions $actions) {
                // @permissions
                if (Admin::user()->can('user.delete')) {
                    $actions->append(new UserDeleteAction());
                }
            });

            /**
             * 工具按钮.
             */
            $grid->tools(function (Grid\Tools $tools) {
                // @permissions
                if (Admin::user()->can('user.import')) {
                    $tools->append(new UserImportAction());
                }
            });

            /**
             * 快速搜索.
             */
            $grid->quickSearch('id', 'username', 'name', 'department.name', 'gender', 'title', 'mobile', 'email')
                ->placeholder(trans('main.quick_search'))
                ->auto(false);

            /**
             * 筛选.
             */
            $grid->filter(function ($filter) {
                if (admin_setting('switch_to_filter_panel')) {
                    $filter->panel();
                }
                $filter->scope('history', admin_trans_label('Deleted'))->onlyTrashed();
                $filter->equal('department_id')->select(Department::pluck('name', 'id'));
            });

            /**
             * 按钮控制.
             */
            $grid->enableDialogCreate();
            $grid->disableDeleteButton();
            $grid->disableBatchDelete();
            $grid->toolsWithOutline(false);
            // @permissions
            if (!Admin::user()->can('user.create')) {
                $grid->disableCreateButton();
            }
            // @permissions
            if (!Admin::user()->can('user.update')) {
                $grid->disableEditButton();
            }
            // @permissions
            if (Admin::user()->can('user.export')) {
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
        return Show::make($id, User::with(['roles', 'department']), function (Show $show) {
            $show->field('id');
            $show->field('name')->unescape()->as(function ($name) {
                if ($this->ad_tag === 1) {
                    return "<span class='badge badge-primary mr-1'>AD</span>$name";
                }

                return $name;
            });
            $show->field('avatar', __('admin.avatar'))->image();
            $show->field('department.name');
            $show->field('gender');
            $show->field('status')->using(['账号冻结', '账号正常']);
            $show->field('title');
            $show->field('mobile');
            $show->field('email');

            if (config('admin.permission.enable')) {
                $show->field('roles')->as(function ($roles) {
                    if (!$roles) {
                        return;
                    }

                    return collect($roles)->pluck('name');
                })->label();

                $show->field('permissions')->unescape()->as(function () {
                    $roles = $this->roles->toArray();

                    $permissionModel = config('admin.database.permissions_model');
                    $roleModel = config('admin.database.roles_model');
                    $permissionModel = new $permissionModel();
                    $nodes = $permissionModel->allNodes();

                    $tree = Tree::make($nodes);

                    $isAdministrator = false;
                    foreach (array_column($roles, 'slug') as $slug) {
                        if ($roleModel::isAdministrator($slug)) {
                            $tree->checkAll();
                            $isAdministrator = true;
                        }
                    }

                    if (!$isAdministrator) {
                        $keyName = $permissionModel->getKeyName();
                        $tree->check(
                            $roleModel::getPermissionId(array_column($roles, $keyName))->flatten()
                        );
                    }

                    return $tree->render();
                });
            }

            $show->field('created_at');
            $show->field('updated_at');

            /**
             * 按钮控制.
             */
            $show->disableDeleteButton();
            // @permissions
            if (!Admin::user()->can('user.update')) {
                $show->disableEditButton();
            }
        });
    }
}

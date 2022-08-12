<?php

namespace App\Admin\Controllers;

use App\Form;
use App\Models\RoleUser;
use App\Models\User;
use App\Models\Department;
use App\Support\LDAP;
use Dcat\Admin\Admin;
use Dcat\Admin\Form\Tools;
use Dcat\Admin\Http\Controllers\AuthController as BaseAuthController;
use Dcat\Admin\Http\JsonResponse;
use Dcat\Admin\Http\Repositories\Administrator;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use Adldap\Laravel\Facades\Adldap;

/**
 * @property string password
 */
class AuthController extends BaseAuthController
{
    protected $view = 'login';

    /**
     * Update user setting.
     *
     * @return JsonResponse|Response
     */
    public function putSetting(): JsonResponse|Response
    {
        if (config('admin.demo')) {
            abort(401, '演示模式下不允许修改');
        }
        $form = $this->settingForm();

        if (!$this->validateCredentialsWhenUpdatingPassword()) {
            $form->responseValidationMessages('old_password', trans('admin.old_password_error'));
        }

        return $form->update(Admin::user()->getKey());
    }

    /**
     * Model-form for user setting.
     *
     * @return Form
     */
    protected function settingForm(): Form
    {
        return new Form(new Administrator(), function (Form $form) {
            $form->action(admin_url('auth/setting'));

            $form->disableCreatingCheck();
            $form->disableEditingCheck();
            $form->disableViewCheck();

            $form->tools(function (Tools $tools) {
                $tools->disableView();
                $tools->disableDelete();
            });

            $form->display('username', trans('admin.username'));
            $form->text('name', trans('admin.name'))->required();
            $form->image('avatar', trans('admin.avatar'))->autoUpload();

            $form->password('old_password', trans('admin.old_password'));

            $form->password('password', trans('admin.password'))
                ->minLength(5)
                ->maxLength(20)
                ->customFormat(function ($v) {
                    if ($v == $this->password) {
                        return;
                    }

                    return $v;
                });
            $form->password('password_confirmation', trans('admin.password_confirmation'))->same('password');

            $form->ignore(['password_confirmation', 'old_password']);

            $form->saving(function (Form $form) {
                if ($form->password && $form->model()->password != $form->password) {
                    $form->password = bcrypt($form->password);
                }

                if (!$form->password) {
                    $form->deleteInput('password');
                }
            });

            $form->saved(function (Form $form) {
                return $form
                    ->response()
                    ->success(trans('admin.update_succeeded'))
                    ->redirect('auth/setting');
            });
        });
    }

    /**
     * Handle a login request.
     *
     * @param Request $request
     *
     * @return RedirectResponse|JsonResponse|Response
     */
    public function postLogin(Request $request): RedirectResponse|JsonResponse|Response
    {
        $username = request('username');
        $password = request('password');

        /**
         * LDAP验证处理.
         */
        if (admin_setting('ad_enabled') && admin_setting('ad_login')) {

            $ldap = new LDAP();
            try {

                // 搜索账户信息.
                $samaccountname_search = Adldap::search()->where('samaccountname', '=', $username)->get();
                
                if (count($samaccountname_search) > 0) {

                    // 获取账户信息.
                    $user = json_decode($samaccountname_search, true)[0];

                    // 获取带域账号.
                    $user_userprincipalname = $user['userprincipalname'][0];

                    // 验证账户.
                    if ($ldap->auth($user_userprincipalname, $password)) {

                        // 获取账户的指定信息.
                        $user_account = $user['samaccountname'][0];
                        $user_name = $user['cn'][0];
                        $user_dns = $user['distinguishedname'][0];
                        $user_dn_array = explode(',', $user_dns);
                        $user_dn_up = $user_dn_array[1];

                        // 默认写入的部门ID为0，也就是根部门.
                        $department_id = 0;

                        // 如果用户有所属部门.
                        if (str_contains($user_dn_up, 'OU=')) {
                            $user_dn_department = explode('=', $user_dn_up)[1];
                            $department = Department::where('name', $user_dn_department)->first();
                            if (!empty($department)) {
                                $department_id = $department->id;
                            }
                        }

                        $admin_user = User::where('username', $user_account)->first();

                        // 本地账户不存在，则新建账户.
                        if (empty($admin_user)) {
                            if ($username == admin_setting('ad_bind_administrator')) {
                                $role_id = 1;
                            } else {
                                $role_id = 2;
                            }

                            $admin_user = new User();
                            $admin_user->username = $user_account;
                            $admin_user->password = bcrypt($password);
                            $admin_user->name = $user_name;
                            $admin_user->department_id = $department_id;
                            $admin_user->ad_tag = '1';
                            $admin_user->save();

                            $admin_role_user = RoleUser::where('user_id', $admin_user->id)
                                ->where('role_id', $role_id)
                                ->first();
                            if (empty($admin_role_user)) {
                                $admin_role_user = new RoleUser();
                            }
                            $admin_role_user->role_id = $role_id;
                            $admin_role_user->user_id = $admin_user->id;
                            $admin_role_user->save();
                        }

                        // 更新密码.
                        $admin_user->password = bcrypt($password);

                        // 保存信息.
                        $admin_user->save();
                    }
                }

            } catch (Exception $exception) {
                dd($exception->getMessage());
                // 如果LDAP服务器连接出现异常，这里可以做异常处理的逻辑
                // 暂时没有任何逻辑，因此只需要抛出异常即可
            }
        }

        $credentials = $request->only([$this->username(), 'password']);
        //$credentials = [$username, $password];
        $remember = (bool)$request->input('remember', false);

        /** @var \Illuminate\Validation\Validator $validator */
        $validator = Validator::make($credentials, [
            $this->username() => 'required|string|exists:admin_users,username,status,1',
            'password' => 'required|string',
        ], [
            'exists' => '该账户已停用，请联系管理员！',
        ]);

        if ($validator->fails()) {
            return $this->validationErrorsResponse($validator);
        }

        if ($this->guard()->attempt($credentials, $remember)) {
            return $this->sendLoginResponse($request);
        }

        return $this->validationErrorsResponse([
            $this->username() => $this->getFailedLoginMessage(),
        ]);
    }
}
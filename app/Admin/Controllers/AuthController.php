<?php

namespace App\Admin\Controllers;

use App\Models\AdminRoleUser;
use App\Models\User;
use App\Support\LDAP;
use Dcat\Admin\Admin;
use Dcat\Admin\Http\Controllers\AuthController as BaseAuthController;
use Dcat\Admin\Http\JsonResponse;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends BaseAuthController
{
    protected $view = 'login';

    /**
     * Update user setting.
     *
     * @return JsonResponse|Response
     */
    public function putSetting()
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
     * Handle a login request.
     *
     * @param Request $request
     *
     * @return mixed
     */
    public function postLogin(Request $request)
    {
        $username = $request->username;
        $password = $request->password;

        /**
         * LDAP验证处理.
         */
        if (admin_setting('ad_enabled') && admin_setting('ad_login')) {
            $ldap = new LDAP();

            try {
                if ($ldap->auth($username, $password)) {
                    $admin_user = User::where('username', $username)->first();
                    if (empty($admin_user)) {
                        $admin_user = new User();
                    }
                    $admin_user->username = $username;
                    $admin_user->password = bcrypt($password);
                    $admin_user->name = $username;
                    $admin_user->save();

                    if ($username == admin_setting('ad_bind_administrator')) {
                        $role_id = 1;
                    } else {
                        $role_id = 2;
                    }
                    $admin_role_user = AdminRoleUser::where('user_id', $admin_user->id)
                        ->where('role_id', $role_id)
                        ->first();
                    if (empty($admin_role_user)) {
                        $admin_role_user = new AdminRoleUser();
                    }
                    $admin_role_user->role_id = $role_id;
                    $admin_role_user->user_id = $admin_user->id;
                    $admin_role_user->save();
                }
            } catch (Exception $exception) {
                // 如果LDAP服务器连接出现异常，这里可以做异常处理的逻辑
                // 暂时没有任何逻辑，因此只需要抛出异常即可
            }
        }

        $credentials = $request->only([$this->username(), 'password']);
        $remember = (bool) $request->input('remember', false);

        /** @var \Illuminate\Validation\Validator $validator */
        $validator = Validator::make($credentials, [
            $this->username() => 'required',
            'password'        => 'required',
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

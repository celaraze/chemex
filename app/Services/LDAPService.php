<?php

namespace App\Services;

use Adldap\Laravel\Facades\Adldap;
use App\Models\Department;
use App\Models\ImportLog;
use App\Models\ImportLogDetail;
use App\Models\User;
use Dcat\Admin\Admin;
use Exception;

class LDAPService
{
    /**
     * 获取AD中全部的OU，并且带上层级.
     *
     * @param $mode
     *
     * @return int[]
     */
    public static function importUserDepartments($mode): array
    {
        $success = 0;
        $fail = 0;

        $import_log = new ImportLog();
        $import_log->item = get_class(new Department());
        $import_log->operator = Admin::user()->id;
        $import_log->save();

        try {
            // 如果模式是复写，先执行清空表
            if ($mode == 'rewrite') {
                Department::truncate();
            }
            $ous = Adldap::search()->ous()->get();
            $ous = json_decode($ous, true);
            // 遍历所有的OU
            foreach ($ous as $ou) {
                // 单个OU的名字
                $ou_name = $ou['name'][0];
                // 单个OU的层级
                $ou_level = $ou['distinguishedname'][0];
                // 把层级数组化
                $ou_level_array = explode(',', $ou_level);
                // 取层级数组的第二个元素
                $ou_level_up = $ou_level_array[1];
                // 默认父级部门ID为0，即为根
                $parent_department_id = 0;
                // 如果DN中，下标为1存在OU=，就是说当前的部门有父级部门
                if (str_contains($ou_level_up, 'OU=')) {
                    $parent_ou_name = str_replace('OU=', '', $ou_level_up);
                    if ($ou_name != $parent_ou_name) {
                        $parent_department = Department::where('name', $parent_ou_name)
                            ->where('ad_tag', 1)
                            ->first();
                        if (empty($parent_department)) {
                            $parent_department = new Department();
                            $parent_department->name = $parent_ou_name;
                            $parent_department->ad_tag = 1;
                            $parent_department->save();
                        }
                        $parent_department_id = $parent_department->id;
                    }
                }
                $department = Department::where('name', $ou_name)
                    ->where('ad_tag', 1)
                    ->first();
                if (empty($department)) {
                    $department = new Department();
                    $department->name = $ou_name;
                    $department->parent_id = $parent_department_id;
                    $department->ad_tag = 1;
                } else {
                    $department->parent_id = $parent_department_id;
                }
                $department->save();
                $success++;
                ImportLogDetail::query()->create([
                    'log_id' => $import_log->id,
                    'status' => 1,
                    'log' => $ou_name . '：导入成功！'
                ]);
            }
        } catch (Exception $exception) {
            $fail++;
            // 导入日志写入
            ImportLogDetail::query()->create([
                'log_id' => $import_log->id,
                'log' => '未知：导入失败，' . $exception->getMessage()
            ]);
        }
        return [$success, $fail];
    }

    /**
     * 获取AD中全部的User，并且自动写入部门.
     *
     * @param $mode
     *
     * @return int[]|string
     */
    public static function importUsers($mode): array|string
    {
        $success = 0;
        $fail = 0;

        $import_log = new ImportLog();
        $import_log->item = get_class(new User());
        $import_log->operator = Admin::user()->id;
        $import_log->save();

        try {
            // 如果模式是复写，先执行清空表
            if ($mode == 'rewrite') {
                User::truncate();
            }

            $users = Adldap::search()->users()->get();
            $users = json_decode($users, true);
            foreach ($users as $user) {
                $user_account = $user['samaccountname'][0];
                $user_name = $user['cn'][0];
                $user_dns = $user['distinguishedname'][0];
                $user_dn_array = explode(',', $user_dns);
                $user_dn_up = $user_dn_array[1];
                // 默认写入的部门ID为0，也就是根部门
                $department_id = 0;
                // 如果用户有所属部门
                if (str_contains($user_dn_up, 'OU=')) {
                    $user_dn_department = explode('=', $user_dn_up)[1];
                    $department = Department::where('name', $user_dn_department)->first();
                    if (!empty($department)) {
                        $department_id = $department->id;
                    }
                }
                $user = User::withTrashed()->where('username', $user_account)->first();
                if (empty($user)) {
                    $user = new User();
                    $user->username = $user_account;
                    $user->password = bcrypt($user->username);
                    $user->name = $user_name;
                    $user->department_id = $department_id;
                    $user->ad_tag = 1;
                    $user->save();
                    $success++;
                    ImportLogDetail::query()->create([
                        'log_id' => $import_log->id,
                        'status' => 1,
                        'log' => $user_name . '：导入成功！'
                    ]);
                }
            }
        } catch (Exception $exception) {
            $fail++;
            // 导入日志写入
            ImportLogDetail::query()->create([
                'log_id' => $import_log->id,
                'log' => '未知：导入失败，' . $exception->getMessage()
            ]);
        }

        return [$success, $fail];
    }
}

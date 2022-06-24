<?php

namespace App\Services;

use App\Models\User;

class UserService
{
    /**
     * 删除用户.
     *
     * @param $user_id
     */
    public static function userDelete($user_id)
    {
        $user = User::where('id', $user_id)->first();
        if (!empty($user)) {
            $user->delete();
        }
    }

    /**
     * 删除配件（强制）.
     *
     * @param $user_id
     */
    public static function userForceDelete($user_id)
    {
        $user = User::where('id', $user_id)
            ->withTrashed()
            ->first();
        if (!empty($user)) {
            $user->forceDelete();
        }
    }
}

<?php

namespace App\Services;

use App\Models\DeviceTrack;
use App\Models\User;

class UserService
{
    /**
     * 删除用户.
     *
     * @param $user_id
     */
    public static function deleteUser($user_id)
    {
        $user = User::where('id', $user_id)->first();
        $device_tracks = DeviceTrack::where('user_id', $user_id)
            ->get();

        foreach ($device_tracks as $device_track) {
            $device_track->delete();
        }

        $user->delete();
    }
}

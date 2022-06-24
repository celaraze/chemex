<?php

namespace App\Services;

use App\Models\User;

class NotificationService
{
    /**
     * 盘点完成或者中止后删除对应的通知（软删除）.
     *
     * @param $check_id
     */
    public static function deleteNotificationWhenCheckFinishedOrCancelled($check_id)
    {
        $user = User::where('id', auth('admin')->id())->first();
        $notifications = $user->notifications;
        foreach ($notifications as $notification) {
            $notification_array = json_decode($notification, true)['data'];
            if (isset($notification_array['check_record_id']) && $notification_array['check_record_id'] == $check_id) {
                $notification->read_at = now();
                $notification->deleted_at = now();
                $notification->save();
            }
        }
    }
}

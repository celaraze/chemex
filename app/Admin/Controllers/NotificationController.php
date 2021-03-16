<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;

class NotificationController extends Controller
{
    /**
     * 标记全部通知为已读.
     *
     * @return RedirectResponse
     */
    public function readAll(): RedirectResponse
    {
        $user = User::where('id', auth('admin')->id())->first();
        if (!empty($user)) {
            foreach ($user->unreadNotifications as $notification) {
                $notification->markAsRead();
            }

            return Redirect::back();
        }
    }

    /**
     * 标记一个通知为已读.
     *
     * @param $id
     *
     * @return RedirectResponse
     */
    public function read($id): RedirectResponse
    {
        $notification = Notification::where('id', $id)->first();
        if (!empty($notification)) {
            $notification->read_at = now();
            $notification->save();
            $data = json_decode($notification->data, true);

            return Redirect::to($data['url']);
        }
    }
}

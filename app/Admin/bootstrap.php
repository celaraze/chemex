<?php

/**
 * Dcat-admin - admin builder based on Laravel.
 *
 * @author jqh <https://github.com/jqhph>
 *
 * Bootstraper for Admin.
 *
 * Here you can remove builtin form field:
 *
 * extend custom field:
 * Dcat\Admin\Form::extend('php', PHPEditor::class);
 * Dcat\Admin\Grid\Column::extend('php', PHPEditor::class);
 * Dcat\Admin\Grid\Filter::extend('php', PHPEditor::class);
 *
 * Or require js and css assets:
 * Admin::css('/packages/prettydocs/css/styles.css');
 * Admin::js('/packages/prettydocs/js/main.js');
 */

use App\Models\User;
use App\Services\ConfigService;
use Dcat\Admin\Layout\Navbar;

ConfigService::init();

// 获取当前用户的通知
$user = User::where('id', auth('admin')->id())->first();
$notifications = [];
if (!empty($user)) {
    $notifications = $user->unreadNotifications;
    $notifications = json_decode($notifications, true);
}

Admin::navbar(function (Navbar $navbar) use ($notifications) {
    $navbar->left(view('nav_left'));
    $navbar->right(view('nav_right')->with('notifications', $notifications));
});

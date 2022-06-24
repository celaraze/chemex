<?php

namespace App\Models;

use Dcat\Admin\Models\Menu as BaseMenu;
use Dcat\Admin\Traits\HasDateTimeFormatter;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Menu extends BaseMenu
{
    use HasFactory;
    use HasDateTimeFormatter;

    protected static function booted()
    {
        // 保存回调，demo模式下不允许修改管理员信息
        static::saving(function () {
            if (config('admin.demo')) {
                abort(401, '演示模式下不允许修改');
            }
        });
    }
}

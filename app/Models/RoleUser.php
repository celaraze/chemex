<?php

namespace App\Models;

use Dcat\Admin\Traits\HasDateTimeFormatter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static where(string $key, string $value)
 *
 * @property int user_id
 * @property int role_id
 */
class RoleUser extends Model
{
    use HasFactory;
    use HasDateTimeFormatter;

    protected $table = 'admin_role_users';
}

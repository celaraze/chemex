<?php

namespace App\Models;

use Dcat\Admin\Traits\HasDateTimeFormatter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @method static where(string $key, string $value)
 *
 * @property int id
 * @property int user_id
 * @property string check_item
 * @property string end_time
 */
class CheckRecord extends Model
{
    use HasDateTimeFormatter;
    use SoftDeletes;

    protected $table = 'check_records';

    /**
     * 盘点记录有一个用户.
     *
     * @return HasOne
     */
    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}

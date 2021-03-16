<?php

namespace App\Models;

use DateTime;
use Dcat\Admin\Traits\HasDateTimeFormatter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @method static where(string $key, string $value)
 *
 * @property int device_id
 * @property int user_id
 * @property DateTime|null lend_time
 * @property string|null lend_description
 * @property DateTime|null plan_return_time
 */
class DeviceTrack extends Model
{
    use HasDateTimeFormatter;
    use SoftDeletes;

    protected $table = 'device_tracks';

    /**
     * 设备追踪有一个设备记录.
     *
     * @return BelongsTo
     */
    public function device(): BelongsTo
    {
        return $this->belongsTo(DeviceRecord::class, 'device_id', 'id');
    }

    /**
     * 设备追踪有一个使用者（用户）.
     *
     * @return HasOne
     */
    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}

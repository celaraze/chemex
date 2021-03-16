<?php

namespace App\Models;

use Dcat\Admin\Traits\HasDateTimeFormatter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @method static where(string $key, string $value)
 *
 * @property int software_id
 * @property int device_id
 */
class SoftwareTrack extends Model
{
    use HasDateTimeFormatter;
    use SoftDeletes;

    protected $table = 'software_tracks';

    /**
     * 软件追踪有一个软件记录.
     *
     * @return HasOne
     */
    public function software(): HasOne
    {
        return $this->hasOne(SoftwareRecord::class, 'id', 'software_id');
    }

    /**
     * 软件追踪有一个设备.
     *
     * @return HasOne
     */
    public function device(): HasOne
    {
        return $this->hasOne(DeviceRecord::class, 'id', 'device_id');
    }
}

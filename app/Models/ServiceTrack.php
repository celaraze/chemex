<?php

namespace App\Models;

use Dcat\Admin\Traits\HasDateTimeFormatter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @method static where(string $key, string $value)
 *
 * @property int service_id
 * @property int device_id
 */
class ServiceTrack extends Model
{
    use HasDateTimeFormatter;
    use SoftDeletes;

    protected $table = 'service_tracks';

    /**
     * 服务程序追踪有一个服务程序.
     *
     * @return HasOne
     */
    public function service(): HasOne
    {
        return $this->hasOne(ServiceRecord::class, 'id', 'service_id');
    }

    /**
     * 服务程序追踪有一个设备记录.
     *
     * @return HasOne
     */
    public function device(): HasOne
    {
        return $this->hasOne(DeviceRecord::class, 'id', 'device_id');
    }
}

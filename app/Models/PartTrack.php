<?php

namespace App\Models;

use Dcat\Admin\Traits\HasDateTimeFormatter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @method static where(string $key, string $value)
 *
 * @property int part_id
 * @property int device_id
 */
class PartTrack extends Model
{
    use HasDateTimeFormatter;
    use SoftDeletes;

    protected $table = 'part_tracks';

    /**
     * 配件追踪有一个配件记录.
     *
     * @return HasOne
     */
    public function part(): HasOne
    {
        return $this->hasOne(PartRecord::class, 'id', 'part_id');
    }

    /**
     * 配件追踪有一个设备记录.
     *
     * @return HasOne
     */
    public function device(): HasOne
    {
        return $this->hasOne(DeviceRecord::class, 'id', 'device_id');
    }
}

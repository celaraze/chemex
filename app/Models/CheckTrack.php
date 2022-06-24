<?php

namespace App\Models;

use Dcat\Admin\Traits\HasDateTimeFormatter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @method static where(string $key, string $value)
 *
 * @property int check_id
 * @property int item_id
 * @property int status
 * @property string checker
 * @property string $check_item
 */
class CheckTrack extends Model
{
    use HasDateTimeFormatter;
    use SoftDeletes;

    protected $table = 'check_tracks';

    /**
     * 盘点追踪有一个负责人.
     *
     * @return HasOne
     */
    public function checker(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'checker');
    }

    /**
     * 盘点追踪有一个盘点任务.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function check(): BelongsTo
    {
        return $this->belongsTo(CheckRecord::class, 'check_id', 'id');
    }

    /**
     * 多态所属的模型
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function item(): MorphTo
    {
        return $this->morphTo(__FUNCTION__, 'check_item', 'item_id');
    }
}

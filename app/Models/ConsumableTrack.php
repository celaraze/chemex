<?php

namespace App\Models;

use Dcat\Admin\Traits\HasDateTimeFormatter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Date;

/**
 * @method static where(string $key, string $value1, string $value2 = null)
 *
 * @property int consumable_id
 * @property string operator
 * @property float number
 * @property float change
 * @property Date|null purchased
 * @property Date|null expired
 */
class ConsumableTrack extends Model
{
    use HasDateTimeFormatter;
    use SoftDeletes;

    protected $table = 'consumable_tracks';

    public function consumable(): HasOne
    {
        return $this->hasOne(ConsumableRecord::class, 'id', 'consumable_id');
    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}

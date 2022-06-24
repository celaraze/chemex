<?php

namespace App\Models;

use Dcat\Admin\Traits\HasDateTimeFormatter;
use Dcat\Admin\Traits\ModelTree;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @method static where(string $key, string $value1, string $value2 = null)
 * @method static pluck(string $string, string $string1)
 * @property int id
 */
class ConsumableRecord extends Model
{
    use HasDateTimeFormatter;
    use SoftDeletes;
    use ModelTree;

    /**
     * 需要被包括进排序字段的字段，一般来说是虚拟出来的关联字段.
     *
     * @var string[]
     */
    public array $sortIncludeColumns = [
        'category.name',
        'vendor.name',
    ];
    /**
     * 需要被排除出排序字段的字段，一般来说是关联字段的原始字段.
     *
     * @var string[]
     */
    public array $sortExceptColumns = [
        'category_id',
        'vendor_id',
        'deleted_at',
    ];
    protected $table = 'consumable_records';

    /**
     * 模型事件
     */
    protected static function booting()
    {
        static::saving(function ($model) {
            if (!empty($model->deleted_at)) {
                abort(401, 'you can not do that.');
            }
        });
    }

    public function category(): HasOne
    {
        return $this->hasOne(ConsumableCategory::class, 'id', 'category_id');
    }

    public function vendor(): HasOne
    {
        return $this->hasOne(VendorRecord::class, 'id', 'vendor_id');
    }

    /**
     * 耗材总数.
     *
     * @return float
     */
    public function allCounts(): float
    {
        $consumable_track = $this->track()->first();
        if (empty($consumable_track)) {
            return 0;
        } else {
            return $consumable_track->number;
        }
    }

    /**
     * 耗材记录有一个履历.
     *
     * @return HasOne
     */
    public function track(): HasOne
    {
        return $this->hasOne(ConsumableTrack::class, 'consumable_id', 'id');
    }

    /**
     * 删除方法.
     * 这是里为了兼容数据删除和字段删除.
     */
    public function delete()
    {
        $this->where($this->primaryKey, $this->getKey())->delete();
        try {
            return parent::delete();
        } catch (Exception $e) {

        }
    }

    /**
     * 强制删除方法.
     * 这里为了兼容数据强制删除和字段强制删除.
     *
     * @return bool|null
     */
    public function forceDelete()
    {
        $this->where($this->primaryKey, $this->getKey())->forceDelete();
        try {
            return parent::forceDelete();
        } catch (Exception $exception) {

        }
    }
}

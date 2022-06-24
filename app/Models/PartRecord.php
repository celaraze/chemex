<?php

namespace App\Models;

use Dcat\Admin\Traits\HasDateTimeFormatter;
use Dcat\Admin\Traits\ModelTree;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @method static where(string $key, string $value, string $value = null)
 * @method static whereBetween(string $string, array $array)
 * @method static count()
 *
 * @property float price
 * @property string purchased
 * @property string expired
 * @property string specification
 * @property int category_id
 * @property int vendor_id
 * @property string name
 * @property string description
 * @property string sn
 * @property string asset_number
 * @property int id
 */
class PartRecord extends Model
{
    use HasFactory;
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
        'depreciation.name',
        'expiration_left_days',
    ];

    /**
     * 需要被排除出排序字段的字段，一般来说是关联字段的原始字段.
     *
     * @var string[]
     */
    public array $sortExceptColumns = [
        'category_id',
        'vendor_id',
        'depreciation_rule_id',
        'deleted_at',
    ];

    protected $table = 'part_records';

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

    /**
     * 配件记录有一个分类.
     *
     * @return HasOne
     */
    public function category(): HasOne
    {
        return $this->hasOne(PartCategory::class, 'id', 'category_id');
    }

    /**
     * 配件记录有一个厂商.
     *
     * @return HasOne
     */
    public function vendor(): HasOne
    {
        return $this->hasOne(VendorRecord::class, 'id', 'vendor_id');
    }

    /**
     * 配件记录在远处有一个设备.
     *
     * @return HasOneThrough
     */
    public function device(): HasOneThrough
    {
        return $this->hasOneThrough(
            DeviceRecord::class,  // 远程表
            PartTrack::class,   // 中间表
            'part_id',    // 中间表对主表的关联字段
            'id',   // 远程表对中间表的关联字段
            'id',   // 主表对中间表的关联字段
            'device_id'
        ); // 中间表对远程表的关联字段
    }

    /**
     * 配件分类有一个折旧规则.
     *
     * @return HasOne
     */
    public function depreciation(): HasOne
    {
        return $this->hasOne(DepreciationRule::class, 'id', 'depreciation_rule_id');
    }

    /**
     * 配件有一个归属
     * @return HasOne
     */
    public function track(): HasOne
    {
        return $this->hasOne(PartTrack::class, 'part_id', 'id');
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

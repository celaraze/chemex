<?php

namespace App\Models;

use Dcat\Admin\Traits\HasDateTimeFormatter;
use Dcat\Admin\Traits\ModelTree;
use Exception;
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
 * @property int purchased_channel_id
 * @property string asset_number
 */
class PartRecord extends Model
{
    use HasDateTimeFormatter;
    use SoftDeletes;
    /**
     * 这里需要给个别名，否则delete方法将会重复
     * 和下面的delete方法重写打配合调整优先级.
     */
    use ModelTree {
        ModelTree::delete as traitDelete;
    }

    /**
     * 需要被包括进排序字段的字段，一般来说是虚拟出来的关联字段.
     *
     * @var string[]
     */
    public $sortIncludeColumns = [
        'category.name',
        'vendor.name',
        'channel.name',
        'depreciation.name',
        'expiration_left_days',
    ];

    /**
     * 需要被排除出排序字段的字段，一般来说是关联字段的原始字段.
     *
     * @var string[]
     */
    public $sortExceptColumns = [
        'category_id',
        'vendor_id',
        'purchased_channel_id',
        'depreciation_rule_id',
        'deleted_at',
    ];

    protected $table = 'part_records';

    /**
     * 复写这个是为了让delete方法的优先级满足：
     * 子类>trait>父类
     * 这个是因为字段管理中删除动作的需要
     *
     * @throws Exception
     *
     * @return bool|null
     */
    public function delete(): ?bool
    {
        return parent::delete();
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
     * 配件记录有一个购入途径.
     *
     * @return HasOne
     */
    public function channel(): HasOne
    {
        return $this->hasOne(PurchasedChannel::class, 'id', 'purchased_channel_id');
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
}

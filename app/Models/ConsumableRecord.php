<?php

namespace App\Models;

use Dcat\Admin\Traits\HasDateTimeFormatter;
use Dcat\Admin\Traits\ModelTree;
use Exception;
use Illuminate\Database\Eloquent\HigherOrderBuilderProxy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @method static where(string $key, string $value1, string $value2 = null)
 * @method static pluck(string $string, string $string1)
 */
class ConsumableRecord extends Model
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
    ];

    /**
     * 需要被排除出排序字段的字段，一般来说是关联字段的原始字段.
     *
     * @var string[]
     */
    public $sortExceptColumns = [
        'category_id',
        'vendor_id',
        'deleted_at',
    ];
    protected $table = 'consumable_records';

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
     * @return HigherOrderBuilderProxy|int|mixed
     */
    public function allCounts()
    {
        $consumable_track = $this->track()->first();
        if (empty($consumable_track)) {
            return 0;
        } else {
            return $consumable_track->number;
        }
    }

    public function track(): HasMany
    {
        return $this->hasMany(ConsumableTrack::class, 'consumable_id', 'id');
    }
}

<?php

namespace App\Models;

use Dcat\Admin\Traits\HasDateTimeFormatter;
use Dcat\Admin\Traits\ModelTree;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @method static where(string $key, string $value1, string $value2 = null)
 * @method static pluck(string $text, string $id)
 *
 * @property int id
 * @property string name
 * @property string|null description
 * @property string|null depreciation_rule_id
 * @property string|null parent_id
 */
class DeviceCategory extends Model
{
    use HasDateTimeFormatter;
    use SoftDeletes;
    use ModelTree;

    protected $table = 'device_categories';

    protected $titleColumn = 'name';

    /**
     * 设备分类有一个父级分类.
     *
     * @return HasOne
     */
    public function parent(): HasOne
    {
        return $this->hasOne(self::class, 'id', 'parent_id');
    }

    /**
     * 设备分类有一个折旧规则.
     *
     * @return HasOne
     */
    public function depreciation(): HasOne
    {
        return $this->hasOne(DepreciationRule::class, 'id', 'depreciation_rule_id');
    }

    /**
     * 如果数据库内现存数据是空的，那么对这个字段访问修饰，返回0
     * 因为模型树排序一定要有parent_id的值
     *
     * @param $parent_id
     *
     * @return int
     */
    public function getParentIdAttribute($parent_id): int
    {
        if (empty($parent_id)) {
            return 0;
        }

        return $parent_id;
    }
}

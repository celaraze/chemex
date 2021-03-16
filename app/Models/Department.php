<?php

namespace App\Models;

use Dcat\Admin\Traits\HasDateTimeFormatter;
use Dcat\Admin\Traits\ModelTree;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @method static where(string $key, string $value1, string $value2 = null)
 * @method static truncate()
 * @method static pluck(string $text, string $id)
 *
 * @property string name
 * @property string|null description
 * @property int parent_id
 * @property int ad_tag
 * @property int id
 */
class Department extends Model
{
    use HasDateTimeFormatter;
    use SoftDeletes;
    use ModelTree;

    protected $table = 'departments';

    protected $titleColumn = 'name';

    /**
     * 组织部门有一个父组织部门.
     *
     * @return HasOne
     */
    public function parent(): HasOne
    {
        return $this->hasOne(self::class, 'id', 'parent_id');
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

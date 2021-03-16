<?php

namespace App\Models;

use DateTime;
use Dcat\Admin\Traits\HasDateTimeFormatter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @method static where(string $key, string $value, string $value = null)
 * @method static whereBetween(string $string, array $array)
 *
 * @property int id
 * @property string name
 * @property DateTime start
 * @property DateTime|null end
 * @property string|null priority
 * @property string|null description
 * @property int|null user_id
 * @property string|null tags
 * @property string|null done_description
 * @property string|null emoji
 * @property int parent_id
 * @property string title
 * @property int order
 */
class TodoRecord extends Model
{
    use HasDateTimeFormatter;
    use SoftDeletes;

    protected $table = 'todo_records';

    /**
     * 修改器
     * 将标签的数组转为字符串.
     *
     * @param $value
     */
    public function setTagsAttribute($value)
    {
        $this->attributes['tags'] = implode(',', $value);
    }

    /**
     * 待办记录有一个负责人.
     *
     * @return HasOne
     */
    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}

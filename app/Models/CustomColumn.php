<?php

namespace App\Models;

use Dcat\Admin\Traits\HasDateTimeFormatter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property string table_name
 * @property string name
 * @property string type
 * @property int must
 * @property string nick_name
 * @property string|null select_options
 *
 * @method static find(int $int)
 * @method static where(string $key, string $value1, string $value2 = null)
 */
class CustomColumn extends Model
{
    use HasDateTimeFormatter;
    use SoftDeletes;

    protected $table = 'custom_columns';

    /**
     * 自定义字段如果是选项类型的值获取转换.
     *
     * @param $select_options
     * @return array
     */
    public function getSelectOptionsAttribute($select_options): array
    {
        return array_values(json_decode($select_options, true) ?: []);
    }

    /**
     * 自定义字段如果是选项类型的值写入转换.
     *
     * @param $select_options
     */
    public function setSelectOptionsAttribute($select_options)
    {
        $this->attributes['select_options'] = json_encode(array_values($select_options));
    }
}

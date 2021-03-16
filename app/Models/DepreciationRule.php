<?php

namespace App\Models;

use Dcat\Admin\Traits\HasDateTimeFormatter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @method static where(string $key, string $value, string $value = null)
 * @method static pluck(string $text, string $id)
 */
class DepreciationRule extends Model
{
    use HasDateTimeFormatter;
    use SoftDeletes;

    protected $table = 'depreciation_rules';

    /**
     * 对规则字段读取做数据类型转换，json字符串解析为数组.
     *
     * @param $rules
     *
     * @return array
     */
    public function getRulesAttribute($rules): array
    {
        return array_values(json_decode($rules, true) ?: []);
    }

    /**
     * 对规则字段写入做数据类型转换，数组转为json字符串.
     *
     * @param $rules
     */
    public function setRulesAttribute($rules)
    {
        $this->attributes['rules'] = json_encode(array_values($rules));
    }
}

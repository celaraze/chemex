<?php

namespace App\Models;

use Dcat\Admin\Traits\HasDateTimeFormatter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @method static where(string $key, string $value1, string $value2 = null)
 * @method static pluck(string $text, string $id)
 *
 * @property string name
 * @property string description
 * @property string location
 */
class VendorRecord extends Model
{
    use HasDateTimeFormatter;
    use SoftDeletes;

    protected $table = 'vendor_records';

    /**
     * 对联系人字段读取做数据类型转换，json字符串解析为数组.
     *
     * @param $contacts
     *
     * @return array
     */
    public function getContactsAttribute($contacts): array
    {
        return array_values(json_decode($contacts, true) ?: []);
    }

    /**
     * 对联系人字段写入做数据类型转换，数组转为json字符串.
     *
     * @param $contacts
     */
    public function setContactsAttribute($contacts)
    {
        $this->attributes['contacts'] = json_encode(array_values($contacts));
    }
}

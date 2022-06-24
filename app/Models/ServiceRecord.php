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
 * @method static where(string $key, string $value)
 * @method static whereBetween(string $string, array $array)
 * @method static count()
 * @property int id
 */
class ServiceRecord extends Model
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

    ];

    /**
     * 需要被排除出排序字段的字段，一般来说是关联字段的原始字段.
     *
     * @var string[]
     */
    public array $sortExceptColumns = [
        'deleted_at',
    ];

    protected $table = 'service_records';

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
     * 服务程序在远处有一个设备记录.
     *
     * @return HasOneThrough
     */
    public function device(): HasOneThrough
    {
        return $this->hasOneThrough(
            DeviceRecord::class,  // 远程表
            ServiceTrack::class,   // 中间表
            'service_id',    // 中间表对主表的关联字段
            'id',   // 远程表对中间表的关联字段
            'id',   // 主表对中间表的关联字段
            'device_id'
        ); // 中间表对远程表的关联字段
    }

    /**
     * 服务有一个归属.
     * @return HasOne
     */
    public function track(): HasOne
    {
        return $this->hasOne(ServiceTrack::class, 'service_id', 'id');
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

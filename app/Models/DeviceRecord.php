<?php

namespace App\Models;

use Dcat\Admin\Traits\HasDateTimeFormatter;
use Dcat\Admin\Traits\ModelTree;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Translation\Translator;

/**
 * @method static where(string $key, string $value, string $value = null)
 * @method static whereBetween(string $string, array $array)
 * @method static count()
 * @method static pluck(string $string, string $string1)
 * @method static find(mixed $id)
 *
 * @property string description
 * @property int category_id
 * @property int vendor_id
 * @property string sn
 * @property string mac
 * @property string ip
 * @property float price
 * @property string purchased
 * @property string expired
 * @property string asset_number
 * @property int id
 * @property PartRecord part
 * @property SoftwareRecord software
 * @property ServiceRecord service
 * @property string title
 * @property int parent_id
 * @property int order
 * @property string field
 */
class DeviceRecord extends Model
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
        'user.name',
        'user.department.name',
        'expiration_left_days',
        'depreciation.name',
        'qrcode',
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

    protected $table = 'device_records';

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
     * 设备记录有一个分类.
     *
     * @return HasOne
     */
    public function category(): HasOne
    {
        return $this->hasOne(DeviceCategory::class, 'id', 'category_id');
    }

    /**
     * 设备记录有一个厂商.
     *
     * @return HasOne
     */
    public function vendor(): HasOne
    {
        return $this->hasOne(VendorRecord::class, 'id', 'vendor_id');
    }

    /**
     * 设备记录在远处有很多配件.
     *
     * @return HasManyThrough
     */
    public function part(): HasManyThrough
    {
        return $this->hasManyThrough(
            PartRecord::class,  // 远程表
            PartTrack::class,   // 中间表
            'device_id',    // 中间表对主表的关联字段
            'id',   // 远程表对中间表的关联字段
            'id',   // 主表对中间表的关联字段
            'part_id'
        ); // 中间表对远程表的关联字段
    }

    /**
     * 设备记录在远处有很多软件.
     *
     * @return HasManyThrough
     */
    public function software(): HasManyThrough
    {
        return $this->hasManyThrough(
            SoftwareRecord::class,  // 远程表
            SoftwareTrack::class,  // 中间表
            'device_id',    // 中间表对主表的关联字段
            'id',   // 远程表对中间表的关联字段
            'id',   // 主表对中间表的关联字段
            'software_id'
        ); // 中间表对远程表的关联字段
    }

    /**
     * 设备记录在远处有很多服务程序.
     *
     * @return HasManyThrough
     */
    public function service(): HasManyThrough
    {
        return $this->hasManyThrough(
            ServiceRecord::class,  // 远程表
            ServiceTrack::class,  // 中间表
            'device_id',    // 中间表对主表的关联字段
            'id',   // 远程表对中间表的关联字段
            'id',   // 主表对中间表的关联字段
            'service_id'
        ); // 中间表对远程表的关联字段
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
     * 设备当前使用者.
     *
     * @return string
     */
    public function userName(): string
    {
        $user = $this->admin_user()->first();
        if (empty($user)) {
            $name = '闲置';
        } else {
            $name = $user->name;
        }

        return $name;
    }

    /**
     * 设备记录在远处有一个使用者（用户）.
     *
     * @return HasOneThrough
     */
    public function admin_user(): HasOneThrough
    {
        return $this->hasOneThrough(
            User::class,  // 远程表
            DeviceTrack::class,   // 中间表
            'device_id',    // 中间表对主表的关联字段
            'id',   // 远程表对中间表的关联字段
            'id',   // 主表对中间表的关联字段
            'user_id'
        ); // 中间表对远程表的关联字段
    }

    /**
     * 返回设备状态.
     *
     * @return array|string|\Illuminate\Translation\Translator|\Illuminate\Contracts\Foundation\Application|null
     */
    public function status(): array|string|Translator|Application|null
    {
        if ($this->isLend()) {
            return ["<span class='badge badge-primary'>" . trans('main.lend') . "</span>", trans('main.lend')];
        }

        $user = $this->admin_user()->first();
        if (!empty($user)) {
            return ["<span class='badge badge-success'>" . trans('main.using') . "</span>", trans('main.using')];
        }

        if (!empty($this->expired) && (time() > strtotime($this->expired))) {
            return ["<span class='badge badge-danger'>" . trans('main.dead') . "</span>", trans('main.dead')];
        }

        return ["<span class='badge badge-info'>" . trans('main.idle') . "</span>", trans('main.idle')];
    }

    /**
     * 判断设备是否借用状态
     *
     * @return bool
     */
    public function isLend(): bool
    {
        $result = $this->track()->value('lend_time');
        if ($result == null) {
            return false;
        }

        return true;
    }

    /**
     * 设备有很多归属记录.
     *
     * @return HasMany
     */
    public function track(): HasMany
    {
        return $this->hasMany(DeviceTrack::class, 'device_id', 'id');
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
        } catch (Exception $exception) {

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

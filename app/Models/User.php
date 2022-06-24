<?php

namespace App\Models;

use DateTime;
use Dcat\Admin\Models\Administrator;
use Dcat\Admin\Traits\HasDateTimeFormatter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * @method static where(string $key, string $value1, string $value2 = null)
 * @method static pluck(string $string, string $string1)
 * @method static count()
 * @method static truncate()
 * @method static find($id)
 *
 * @property int id
 * @property string username
 * @property string password
 * @property string name
 * @property int department_id
 * @property string gender
 * @property null|string title
 * @property null|string mobile
 * @property null|string email
 * @property int ad_tag
 * @property DeviceRecord device
 * @property DateTime|null deleted_at
 */
class User extends Administrator implements JWTSubject
{
    use HasFactory;
    use HasDateTimeFormatter;
    use Notifiable;
    use SoftDeletes;

    /**
     * 用户有一个组织部门记录.
     *
     * @return HasOne
     */
    public function department(): HasOne
    {
        return $this->hasOne(Department::class, 'id', 'department_id');
    }

    /**
     * 用户有很多个角色追踪.
     *
     * @return HasMany
     */
    public function roleUser(): HasMany
    {
        return $this->hasMany(RoleUser::class, 'user_id', 'id');
    }

    /**
     * 用户的资产总价值
     *
     * @return int
     */
    public function itemsPrice(): int
    {
        $price = 0;
        foreach ($this->device as $device) {
            $price += $device->price;
            foreach ($device->part as $part) {
                $price += $part->price;
            }
            foreach ($device->software as $software) {
                $price += $software->price;
            }
            foreach ($device->service as $service) {
                $price += $service->price;
            }
        }

        return $price;
    }

    /**
     * 用户的设备数量
     *
     * @return int
     */
    public function deviceCounts(): int
    {
        return $this->device()->count();
    }

    /**
     * 设备记录在远处有一个使用者（用户）.
     *
     * @return HasManyThrough
     */
    public function device(): HasManyThrough
    {
        return $this->hasManyThrough(
            DeviceRecord::class,  // 远程表
            DeviceTrack::class,   // 中间表
            'user_id',    // 中间表对主表的关联字段
            'id',   // 远程表对中间表的关联字段
            'id',   // 主表对中间表的关联字段
            'device_id'
        ); // 中间表对远程表的关联字段
    }

    // Rest omitted for brevity

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier(): mixed
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims(): array
    {
        return [];
    }
}

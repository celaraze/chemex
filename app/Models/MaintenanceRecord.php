<?php

namespace App\Models;

use Dcat\Admin\Traits\HasDateTimeFormatter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @method static where(string $key, string $value)
 * @method static whereBetween(string $string, array $array)
 * @method static count()
 *
 * @property string ng_description
 * @property string ng_time
 * @property int status
 * @property string $asset_number
 */
class MaintenanceRecord extends Model
{
    use HasDateTimeFormatter;
    use SoftDeletes;

    protected $table = 'maintenance_records';
}

<?php

namespace App\Models;

use Dcat\Admin\Traits\HasDateTimeFormatter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static where(string $key, string $value1, string $value2 = null)
 *
 * @property string table_name
 * @property string name
 * @property int order
 */
class ColumnSort extends Model
{
    use HasFactory;
    use HasDateTimeFormatter;

    protected $table = 'column_sorts';
}

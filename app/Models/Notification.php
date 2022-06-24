<?php

namespace App\Models;

use Dcat\Admin\Traits\HasDateTimeFormatter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @method static where(string $string, $id)
 */
class Notification extends Model
{
    use HasFactory;
    use HasDateTimeFormatter;
    use SoftDeletes;

    protected $table = 'notifications';
}

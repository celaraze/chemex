<?php

namespace App\Models;

use Dcat\Admin\Traits\HasDateTimeFormatter;
use Illuminate\Database\Eloquent\Model;

class ImportLogDetail extends Model
{
    use HasDateTimeFormatter;

    protected $table = 'import_log_details';

    protected $fillable = [
        'log_id',
        'status',
        'log'
    ];

}

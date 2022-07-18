<?php

namespace Spatie\EloquentSortable\Test;

use Illuminate\Database\Eloquent\Model;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;

class Dummy extends Model implements Sortable
{
    use SortableTrait;

    public $timestamps = false;
    protected $table = 'dummies';
    protected $guarded = [];
}

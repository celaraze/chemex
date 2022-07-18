<?php

namespace Spatie\EloquentSortable\Test;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;

class DummyWithSoftDeletes extends Model implements Sortable
{
    use SoftDeletes;
    use SortableTrait;

    public $timestamps = false;
    protected $table = 'dummies';
    protected $guarded = [];
}

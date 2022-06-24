<?php

namespace App\Admin\Repositories;

use App\Models\ChartRecord as Model;
use Dcat\Admin\Repositories\EloquentRepository;

class ChartRecord extends EloquentRepository
{
    /**
     * Model.
     *
     * @var string
     */
    protected $eloquentClass = Model::class;
}

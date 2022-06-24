<?php

namespace App\Admin\Repositories;

use App\Models\ConsumableCategory as Model;
use Dcat\Admin\Repositories\EloquentRepository;

class ConsumableCategory extends EloquentRepository
{
    /**
     * Model.
     *
     * @var string
     */
    protected $eloquentClass = Model::class;
}

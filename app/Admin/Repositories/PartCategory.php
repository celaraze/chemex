<?php

namespace App\Admin\Repositories;

use App\Models\PartCategory as Model;
use Dcat\Admin\Repositories\EloquentRepository;

class PartCategory extends EloquentRepository
{
    /**
     * Model.
     *
     * @var string
     */
    protected $eloquentClass = Model::class;
}

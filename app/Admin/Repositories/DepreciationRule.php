<?php

namespace App\Admin\Repositories;

use App\Models\DepreciationRule as Model;
use Dcat\Admin\Repositories\EloquentRepository;

class DepreciationRule extends EloquentRepository
{
    /**
     * Model.
     *
     * @var string
     */
    protected $eloquentClass = Model::class;
}

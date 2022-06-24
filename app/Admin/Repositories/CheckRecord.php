<?php

namespace App\Admin\Repositories;

use App\Models\CheckRecord as Model;
use Dcat\Admin\Repositories\EloquentRepository;

class CheckRecord extends EloquentRepository
{
    /**
     * Model.
     *
     * @var string
     */
    protected $eloquentClass = Model::class;
}

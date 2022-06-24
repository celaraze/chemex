<?php

namespace App\Admin\Repositories;

use App\Models\CustomColumn as Model;
use Dcat\Admin\Repositories\EloquentRepository;

class CustomColumn extends EloquentRepository
{
    /**
     * Model.
     *
     * @var string
     */
    protected $eloquentClass = Model::class;
}

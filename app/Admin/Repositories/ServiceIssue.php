<?php

namespace App\Admin\Repositories;

use App\Models\ServiceIssue as Model;
use Dcat\Admin\Repositories\EloquentRepository;

class ServiceIssue extends EloquentRepository
{
    /**
     * Model.
     *
     * @var string
     */
    protected $eloquentClass = Model::class;
}

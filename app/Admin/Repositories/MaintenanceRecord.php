<?php

namespace App\Admin\Repositories;

use App\Models\MaintenanceRecord as Model;
use Dcat\Admin\Repositories\EloquentRepository;

class MaintenanceRecord extends EloquentRepository
{
    /**
     * Model.
     *
     * @var string
     */
    protected $eloquentClass = Model::class;
}

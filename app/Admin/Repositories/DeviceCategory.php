<?php

namespace App\Admin\Repositories;

use App\Models\DeviceCategory as Model;
use Dcat\Admin\Repositories\EloquentRepository;

class DeviceCategory extends EloquentRepository
{
    /**
     * Model.
     *
     * @var string
     */
    protected $eloquentClass = Model::class;
}

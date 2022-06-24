<?php

namespace App\Admin\Repositories;

use App\Models\DeviceTrack as Model;
use Dcat\Admin\Repositories\EloquentRepository;

class DeviceTrack extends EloquentRepository
{
    /**
     * Model.
     *
     * @var string
     */
    protected $eloquentClass = Model::class;
}

<?php

namespace App\Admin\Repositories;

use App\Models\ServiceTrack as Model;
use Dcat\Admin\Repositories\EloquentRepository;

class ServiceTrack extends EloquentRepository
{
    /**
     * Model.
     *
     * @var string
     */
    protected $eloquentClass = Model::class;
}

<?php

namespace App\Admin\Repositories;

use App\Models\SoftwareTrack as Model;
use Dcat\Admin\Repositories\EloquentRepository;

class SoftwareTrack extends EloquentRepository
{
    /**
     * Model.
     *
     * @var string
     */
    protected $eloquentClass = Model::class;
}

<?php

namespace App\Admin\Repositories;

use App\Models\CheckTrack as Model;
use Dcat\Admin\Repositories\EloquentRepository;

class CheckTrack extends EloquentRepository
{
    /**
     * Model.
     *
     * @var string
     */
    protected $eloquentClass = Model::class;
}

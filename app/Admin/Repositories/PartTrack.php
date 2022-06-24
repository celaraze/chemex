<?php

namespace App\Admin\Repositories;

use App\Models\PartTrack as Model;
use Dcat\Admin\Repositories\EloquentRepository;

class PartTrack extends EloquentRepository
{
    /**
     * Model.
     *
     * @var string
     */
    protected $eloquentClass = Model::class;
}

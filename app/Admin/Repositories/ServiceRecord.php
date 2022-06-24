<?php

namespace App\Admin\Repositories;

use App\Models\ServiceRecord as Model;
use App\Traits\RepositoryHasSortColumns;
use Dcat\Admin\Repositories\EloquentRepository;

class ServiceRecord extends EloquentRepository
{
    use RepositoryHasSortColumns;

    /**
     * Model.
     *
     * @var string
     */
    protected $eloquentClass = Model::class;
}

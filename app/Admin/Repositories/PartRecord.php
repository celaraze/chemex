<?php

namespace App\Admin\Repositories;

use App\Models\PartRecord as Model;
use App\Traits\RepositoryHasSortColumns;
use Dcat\Admin\Repositories\EloquentRepository;

class PartRecord extends EloquentRepository
{
    use RepositoryHasSortColumns;

    /**
     * Model.
     *
     * @var string
     */
    protected $eloquentClass = Model::class;
}

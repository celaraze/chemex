<?php

namespace App\Admin\Repositories;

use App\Models\ConsumableRecord as Model;
use App\Traits\RepositoryHasSortColumns;
use Dcat\Admin\Repositories\EloquentRepository;

class ConsumableRecord extends EloquentRepository
{
    use RepositoryHasSortColumns;

    /**
     * Model.
     *
     * @var string
     */
    protected $eloquentClass = Model::class;
}

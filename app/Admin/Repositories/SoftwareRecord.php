<?php

namespace App\Admin\Repositories;

use App\Models\SoftwareRecord as Model;
use App\Traits\RepositoryHasSortColumns;
use Dcat\Admin\Repositories\EloquentRepository;

class SoftwareRecord extends EloquentRepository
{
    use RepositoryHasSortColumns;

    /**
     * Model.
     *
     * @var string
     */
    protected $eloquentClass = Model::class;
}

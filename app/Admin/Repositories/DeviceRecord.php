<?php

namespace App\Admin\Repositories;

use App\Models\DeviceRecord as Model;
use App\Traits\RepositoryHasSortColumns;
use Dcat\Admin\Repositories\EloquentRepository;

class DeviceRecord extends EloquentRepository
{
    use RepositoryHasSortColumns;

    /**
     * Model.
     *
     * @var string
     */
    protected $eloquentClass = Model::class;
}

<?php

namespace App\Admin\Repositories;

use App\Models\ImportLog as Model;
use Dcat\Admin\Repositories\EloquentRepository;

class ImportLog extends EloquentRepository
{
    /**
     * Model.
     *
     * @var string
     */
    protected $eloquentClass = Model::class;
}

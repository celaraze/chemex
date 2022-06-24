<?php

namespace App\Admin\Repositories;

use App\Models\ImportLogDetail as Model;
use Dcat\Admin\Repositories\EloquentRepository;

class ImportLogDetail extends EloquentRepository
{
    /**
     * Model.
     *
     * @var string
     */
    protected $eloquentClass = Model::class;
}

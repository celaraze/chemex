<?php

namespace App\Admin\Repositories;

use App\Models\SoftwareCategory as Model;
use Dcat\Admin\Repositories\EloquentRepository;

class SoftwareCategory extends EloquentRepository
{
    /**
     * Model.
     *
     * @var string
     */
    protected $eloquentClass = Model::class;
}

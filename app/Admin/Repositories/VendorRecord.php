<?php

namespace App\Admin\Repositories;

use App\Models\VendorRecord as Model;
use Dcat\Admin\Repositories\EloquentRepository;

class VendorRecord extends EloquentRepository
{
    /**
     * Model.
     *
     * @var string
     */
    protected $eloquentClass = Model::class;
}

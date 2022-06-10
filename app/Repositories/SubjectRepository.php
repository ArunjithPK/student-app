<?php

namespace App\Repositories;

use App\Models\Subjects;

class SubjectRepository
{

    protected $model;

    /**
     * Create a new Model instance.
     *
     * @param  Models\Subjects $model
     */
    public function __construct(Subjects $model)
    {
        $this->model = $model;
    }

    /**
     * get all
     * @return object
     */
    public function getAll()
    {
        return $this->model->all();
    }
}

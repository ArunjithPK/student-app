<?php

namespace App\Repositories;

use App\Models\Teachers;

class TeacherRepository
{

    protected $model;

    /**
     * Create a new Model instance.
     *
     * @param  Models\Teachers $model
     */
    public function __construct(Teachers $model)
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

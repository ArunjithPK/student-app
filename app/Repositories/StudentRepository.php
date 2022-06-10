<?php

namespace App\Repositories;

use App\Models\Students;

class StudentRepository
{

    protected $model;

    /**
     * Create a new Model instance.
     *
     * @param  Models\Students $model
     */
    public function __construct(Students $model)
    {
        $this->model = $model;
    }


    /**
     * created/update student.
     * @param  $request
     * @return object
     */
    public function store($request)
    {
        return $this->model->updateOrCreate(['id' => $request['id']], $request);
    }


    /**
     * Remove student
     * @param $id
     * @return object
     */
    public function destroy($id)
    {
        return $this->model->find($id)->delete();
    }

    /**
     * Get single student details
     *
     * @param $id
     * @return object
     */
    public function getById($id)
    {
        return $this->model->select('id','name','gender','dob','reporting_teacher_id')->find($id);
    }

    /**
     * get all
     * @return object
     */

    public function getAll()
    {
        return $this->model->with(["ReportingTeacher"=>function($query){
            return $query->select('id','name');
        }])
        ->select('id','name','gender','dob','reporting_teacher_id')
        ->get();
    }

    public function getStudentsArray()
    {
        return $this->model->all()->pluck('name','id')->toArray();
    }
}

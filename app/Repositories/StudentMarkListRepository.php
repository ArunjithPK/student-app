<?php

namespace App\Repositories;

use App\Models\StudentMarkLists;

use function PHPUnit\Framework\isNull;

class StudentMarkListRepository
{

    protected $model;

    /**
     * Create a new Model instance.
     *
     * @param  Models\StudentMarkLists $model
     */
    public function __construct(StudentMarkLists $model)
    {
        $this->model = $model;
    }


    /**
     * created/update .
     * @param  $request
     * @return object
     */
    public function store($request)
    {
        return $this->model->updateOrCreate(['id'=>$request['id']],$request);
    }

     /**
     * Get single details
     *
     * @param $id
     * @return object
     */
    public function getById($id)
    {
        return $this->model
        ->with('studentMarks')
        ->find($id);
    }

    /**
     * Remove
     * @param $id
     * @return object
     */
    public function destroy($id)
    {
        return $this->model->find($id)->delete();
    }

    /**
     * Remove
     * @param $student_id
     * @return object
     */
    public function destroyByStudent($student_id)
    {
        return $this->model->where('student_id',$student_id)->delete();
    }

    /**
     * isExists
     * @param $request
     * @return object
     */
    public function isExists($request)
    {
        // dd($request);
        return $this->model
        ->where('student_id',$request['student_id'])
        ->where('term',$request['term'])
        ->when(!empty($request['id']), function($query) use($request){
            return $query->where('id','!=',$request['id']);
        })
        ->exists();
    }

    public function getAll()
    {
        return $this->model
        ->select('id','student_id','term','created_at')
        ->with(["students"=>function($query){
            return $query->select('id','name');
        },"studentMarks"=>function($query){
            return $query->select('id','student_mark_list_id','subject_id','mark');
        },
        "studentMarks.subjects"=>function($query){
            return $query->select('id','name');
        }])
        ->get();
    }
}

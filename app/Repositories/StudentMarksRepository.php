<?php

namespace App\Repositories;

use App\Models\StudentMarks;

class StudentMarksRepository
{

    protected $model;

    /**
     * Create a new Model instance.
     *
     * @param  Models\StudentMarks $model
     */
    public function __construct(StudentMarks $model)
    {
        $this->model = $model;
    }


    /**
     * created .
     * @param  $request array
     * @return object
     */

    public function store($request,$markListId){
        $flag = false;
        foreach($request as $req){
            $req['student_mark_list_id'] = $markListId;
           $flag = $this->model->updateOrCreate([
                'student_mark_list_id' => $markListId,
                'subject_id'=>$req['subject_id']
            ],$req);
        }
        return  $flag;
    }
     /**
     * Remove
     * @param $student_id
     * @return object
     */
    public function destroyByStudent($student_id)
    {
        return $this->model
        ->whereHas('StudentMarkList',function($query) use($student_id){
            return $query->where('student_id',$student_id);
        })
        ->delete();
    }

  /**
     * Remove
     * @param $mark_list_id
     * @return object
     */
    public function destroyByMarkList($mark_list_id)
    {
        return $this->model
        ->where('student_mark_list_id',$mark_list_id)
        ->delete();
    }

}

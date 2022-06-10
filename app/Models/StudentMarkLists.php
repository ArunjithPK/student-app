<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentMarkLists extends Model
{
    use HasFactory, SoftDeletes;
    public $timestamps = true;

    protected $fillable = [
        'student_id',
        'term',
    ];

    public function students(){
        return $this->belongsTo('App\Models\Students', 'student_id');
    }

    public function studentMarks(){
        return $this->hasMany('App\Models\StudentMarks','student_mark_list_id','id');
    }



}

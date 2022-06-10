<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentMarks extends Model
{
    use HasFactory, SoftDeletes;
    public $timestamps = true;

    protected $fillable = [
        'student_mark_list_id',
        'subject_id',
        'mark',
    ];

    public function studentMarkList(){
        return $this->belongsTo('App\Models\StudentMarkLists','student_mark_list_id');
    }

    public function subjects(){
        return $this->belongsTo('App\Models\Subjects','subject_id');
    }
}

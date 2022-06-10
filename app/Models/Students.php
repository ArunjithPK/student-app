<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Students extends Model
{
    use HasFactory, SoftDeletes;
    public $timestamps = true;
    protected $fillable = [
        'name',
        'gender',
        'dob',
        'reporting_teacher_id',
    ];
    public function ReportingTeacher(){
        return $this->belongsTo('App\Models\Teachers', 'reporting_teacher_id', 'id');
    }
}

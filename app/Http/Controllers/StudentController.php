<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StudentRequest;
use App\Services\HelperService;
use App\Repositories\StudentRepository;
use App\Repositories\TeacherRepository;
use App\Repositories\StudentMarkListRepository;
use App\Repositories\StudentMarksRepository;

class StudentController extends Controller
{

    protected $studRepository;
    protected $teacherRepository;
    protected $studentMarkListRepository;
    protected $studentMarksRepository;

    /**
     * Create a new repocitory instance.
     *
     * @param  Repositories\StudentRepository $studentRepository
     * @param  Repositories\TeacherRepository $teacherRepository
     * @param  Repositories\StudentMarkListRepository $studentMarkListRepository
     * @param  Repositories\StudentMarksRepository $studentMarksRepository
     */
    public function __construct(
        StudentRepository $studentRepository,
        TeacherRepository $teacherRepository,
        StudentMarkListRepository $studentMarkListRepository,
        StudentMarksRepository $studentMarksRepository
    ){
        $this->studentRepository = $studentRepository;
        $this->teacherRepository = $teacherRepository;
        $this->studentMarkListRepository = $studentMarkListRepository;
        $this->studentMarksRepository = $studentMarksRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $teachers = $this->teacherRepository->getAll()->pluck('name', 'id')->toArray();
        return view('student.list', compact('teachers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StudentRequest\StudentRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StudentRequest $request)
    {
        try {
            $inputs = $request->all();
            $this->studentRepository->store($inputs);
            return response()->json(HelperService::returnTrueResponse());
        } catch (\Exception $e) {
            return response()->json(HelperService::returnFalseResponse($e));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return $this->studentRepository->getById($id);
    }

    public function getAll()
    {
        return datatables()->of($this->studentRepository->getAll())->addIndexColumn()->toJson();
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            \DB::beginTransaction();
            $this->studentMarksRepository->destroyByStudent($id);
            $this->studentMarkListRepository->destroyByStudent($id);
            $this->studentRepository->destroy($id);
            \DB::commit();
            return response()->json(HelperService::returnTrueResponse());
        } catch (\Exception $e) {
            \DB::rollBack();
            return response()->json(HelperService::returnFalseResponse($e));
        }
    }
}

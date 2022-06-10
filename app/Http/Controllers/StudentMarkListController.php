<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\HelperService;
use App\Repositories\StudentMarkListRepository;
use App\Repositories\SubjectRepository;
use App\Repositories\StudentRepository;
use App\Http\Requests\StudentMarkListRequest;
use App\Repositories\StudentMarksRepository;
use Carbon\Carbon;

class StudentMarkListController extends Controller
{

    protected $studentMarkListRepository;
    protected $subjectRepository;
    protected $studentRepository;
    protected $studentMarksRepository;

    /**
     * Create a new repocitory instance.
     *
     * @param  Repositories\StudentMarkListRepository $studentMarkListRepository
     * @param  Repositories\SubjectRepository $subjectRepository
     * @param  Repositories\StudentRepository $studentRepository
     * @param  Repositories\studentMarksRepository $studentMarksRepository
     */
    public function __construct(
        StudentMarkListRepository $studentMarkListRepository,
        SubjectRepository $subjectRepository,
        StudentRepository $studentRepository,
        studentMarksRepository $studentMarksRepository
    ) {
        $this->studentMarkListRepository = $studentMarkListRepository;
        $this->subjectRepository = $subjectRepository;
        $this->studentRepository = $studentRepository;
        $this->studentMarksRepository = $studentMarksRepository;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $subjects = $this->subjectRepository->getAll();
        $students = $this->studentRepository->getStudentsArray();
        $marklists = $this->dataFormat($this->studentMarkListRepository->getAll());
        return view('student.mark-list', compact('subjects', 'students', 'marklists'));
    }

     /**
     * Formatting resullt data
     *
     * @return \Illuminate\Http\Response
     */
    public function dataFormat($markLists)
    {
        $result = [];
        foreach ($markLists as $key => $markList) {
            $result[$key] = [
                'id' => $markList->id,
                'student_id' => $markList->student_id,
                'term' => $markList->term,
                'created_at' => Carbon::parse($markList->created_at)->format('M d, Y h:m A'),
                'student_name' => $markList->students->name,
                'total_marks' => $markList->studentMarks->sum('mark')
            ];
            $marks = [];
            foreach ($markList->studentMarks as $list) {
                $marks[$list->subject_id] = $list->mark;
            }
            $result[$key]['marks'] = $marks;
        }

        return $result;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StudentMarkListRequest $request)
    {
        $status = true;
        $exists = false;

        $markList = [
            'student_id' => $request->student_id,
            'term' => $request->term,
            'id' => $request->id
        ];

        $exists = $this->studentMarkListRepository->isExists($markList);
        //looping to set marks array
        $markset = $this->setMarkData($request->marks);
        if ($markset['status'] && $exists == false) {
            try {
                \DB::beginTransaction();
                $result = $this->studentMarkListRepository->store($markList);
                $this->studentMarksRepository->store($markset['inputs'], $result->id);
                \DB::commit();
                return response()->json(HelperService::returnTrueResponse($result));
            } catch (\Exception $e) {
                \DB::rollBack();
                return response()->json(HelperService::returnFalseResponse($e));
            }
        } else {
            if ($markset['status'] == false) {
                $message = 'Something went wrong. Reload and try again';
            }
            if ($exists == true) {
                $message = 'Data already exists';
            }
            return response()->json(HelperService::returnFalseResponse($message));
        }
    }

    public function setMarkData($marks)
    {
        $inputs = [];
        $status = true;
        $subjects = $this->subjectRepository->getAll();
        foreach ($marks as $key => $mark) {
            $subject = $subjects->where('id', $key);
            if (!$subject->isempty()) {
                $inputs[] = [
                    'mark' => $mark,
                    'subject_id' => $key
                ];
            } else {
                $status = false;
                break;
            }
        }
        return [
            'status' => $status,
            'inputs' => $inputs
        ];
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return $this->studentMarkListRepository->getById($id);
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
            $this->studentMarksRepository->destroyByMarkList($id);
            $this->studentMarkListRepository->destroy($id);
            \DB::commit();
            return response()->json(HelperService::returnTrueResponse());
        } catch (\Exception $e) {
            \DB::rollBack();
            return response()->json(HelperService::returnFalseResponse($e));
        }
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Students;
use App\Models\Course;

class ExportController extends Controller
{
    public function __construct()
    {

    }

    public function welcome()
    {
        return view('hello');
    }

    /**
     * View all students found in the database
     */
    public function viewStudents()
    {
        $students = Students::all();

        return view('view_students', compact(['students']));
    }

    public function export(Request $request)
    {
        $headers = array(
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=student.csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );

        switch($request->input('type')) {
            case 'student':
                $columns = array('id', 'Forname', 'Surname', 'Email');
                break;
            case 'all':
                $columns = array('id', 'Forname', 'Surname', 'Email', 'University', 'Course');
                break;
        }

        if(!empty($request->input('studentId'))) {
            $callback = $this->createCsv($request, $columns);
            return response()->stream($callback, 200, $headers);
        } else {
            return redirect()->back();
        }
    }

    public function createCsv($request, $columns)
    {
        $callback = function() use ($request,$columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            foreach ($request->input('studentId') as $studentId) {
                $student = Students::find($studentId);
                if ($student) {
                    switch($request->input('type')){
                        case 'student':
                            fputcsv($file, array($student->id, $student->firstname, $student->surname, $student->email));
                            break;
                        case 'all':
                            fputcsv($file, array($student->id, $student->firstname, $student->surname, $student->email, $student['course']['university'], $student['course']['course_name']));
                            break;
                    }
                }
            }
            fclose($file);
        };

        return $callback;
    }
}

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
        switch($request->input('type')) {
            case 'student':
                return $this->exportStudentsToCSV($request);
                break;
            case 'all':
                return $this->exportCourseAttendenceToCSV($request);
                break;
        }
    }

    /**
     * Exports all student data to a CSV file
     */
    public function exportStudentsToCSV($request)
    {
        if(!empty($request->input('studentId'))) {
            $headers = array(
                "Content-type" => "text/csv",
                "Content-Disposition" => "attachment; filename=student.csv",
                "Pragma" => "no-cache",
                "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                "Expires" => "0"
            );

            $columns = array('id', 'Forname', 'Surname', 'Email');
            $callback = function() use ($request,$columns) {
                $file = fopen('php://output', 'w');
                fputcsv($file, $columns);
                foreach ($request->input('studentId') as $studentId) {
                    $student = Students::find($studentId);
                    if ($student) {
                        fputcsv($file, array($student->id, $student->firstname, $student->surname, $student->email));
                    }
                }
                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        } else {
            return redirect()->back();
        }
    }

    /**
     * Exports the total amount of students that are taking each course to a CSV file
     */
    public function exportCourseAttendenceToCSV($request)
    {
        if(!empty($request->input('studentId'))) {
            $headers = array(
                "Content-type" => "text/csv",
                "Content-Disposition" => "attachment; filename=student.csv",
                "Pragma" => "no-cache",
                "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                "Expires" => "0"
            );

            $columns = array('id', 'Forname', 'Surname', 'Email', 'University', 'Course');
            $callback = function() use ($request,$columns) {
                $file = fopen('php://output', 'w');
                fputcsv($file, $columns);
                foreach ($request->input('studentId') as $studentId) {
                    $student = Students::find($studentId);
                    if ($student) {
                        fputcsv($file, array($student->id, $student->firstname, $student->surname, $student->email, $student['course']['university'], $student['course']['course_name']));
                    }
                }
                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        } else {
            return redirect()->back();
        }
    }
}

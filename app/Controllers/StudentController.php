<?php

namespace App\Controllers;

use App\Models\StudentModel;

class StudentController
{
    protected $studentModel;

    public function __construct(StudentModel $studentModel)
    {
        $this->studentModel = $studentModel;
    }

    public function getAllStudents()
    {
        $students = $this->studentModel->getAllStudents() ?? [];

        return $students;
    }

    public function addStudent()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $studentData = [
                'student_name' => $_POST['student_name'],
                'student_id' => $_POST['student_id'],
                'class' => $_POST['class']
            ];

            $result = $this->studentModel->addStudent($studentData);

            if ($result) {
                echo "Thêm sinh viên thành công.";
            } else {
                echo "Thêm sinh viên thất bại.";
            }
        }
    }
}

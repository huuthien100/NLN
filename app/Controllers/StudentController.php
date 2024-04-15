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

    public function getStudentById($id)
    {
        $student = $this->studentModel->getStudentById($id);
        return $student;
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
            header("Location: index.php?page=list_students");
            exit;
        } else {
            $_SESSION['error_message'] = "Sinh viên đã tồn tại.";
            header("Location: index.php?page=add_student");
            exit;
        }
    }
}

    public function updateStudent($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $studentData = [
                'student_name' => $_POST['student_name'],
                'student_id' => $_POST['student_id'],
                'class' => $_POST['class']
            ];

            $result = $this->studentModel->updateStudent($id, $studentData);

            if ($result) {
                echo "Cập nhật sinh viên thành công.";
            } else {
                echo "Cập nhật sinh viên thất bại.";
            }
        }
    }

    public function deleteStudent($id)
    {
        $this->studentModel->deleteStudent($id);

        echo "Sinh viên đã được xóa thành công.";
    }
}

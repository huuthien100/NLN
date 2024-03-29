<?php

use App\Controllers\AttendanceController;
use App\Controllers\StudentController;
use App\Models\AttendanceModel;
use App\Models\StudentModel;

require '../vendor/autoload.php';
require '../config/connect.php';
require '../app/Views/includes/sidebar.php';

$attendanceController = new AttendanceController(new AttendanceModel($pdo));
$studentController = new StudentController(new StudentModel($pdo));

if (isset($_GET['page'])) {
    $page = $_GET['page'];

    switch ($page) {
        case 'home':
            require_once __DIR__ . '/../../app/views/attendance/home.php';
            break;
        case 'add_student':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $studentController->addStudent();
                header("Location: index.php?page=list_students");
            } else {
                require_once __DIR__ . '../../app/views/manage/add_student.php';
            }
            break;
        case 'attendance':
            require_once __DIR__ . '../../app/views/attendance/attendance.php';
            break;
        case 'list_students':
            $students = $studentController->getAllStudents();
            require_once __DIR__ . '../../app/views/manage/list_students.php';
            break;
        case 'attendance_session':
            $students = $attendanceController->getAllAttendances();
            require_once __DIR__ . '../../app/views/attendance/attendance_session.php';
            break;
        default:
            require_once __DIR__ . '../../app/views/attendance/home.php';
            break;
    }
} else {
    require_once __DIR__ . '../../app/views/attendance/home.php';
}

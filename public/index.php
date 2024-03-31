<?php

use App\Controllers\AttendanceController;
use App\Controllers\StudentController;
use App\Controllers\UserController;
use App\Models\AttendanceModel;
use App\Models\StudentModel;
use App\Models\UserModel;

require '../vendor/autoload.php';
require 'config/connect.php';

session_start();

$attendanceController = new AttendanceController(new AttendanceModel($pdo));
$studentController = new StudentController(new StudentModel($pdo));
$userController = new UserController(new UserModel($pdo));

$isLoggedIn = isset($_SESSION['user_id']);
if ($isLoggedIn) {
    require '../app/Views/includes/sidebar.php';
}

if (isset($_GET['page'])) {
    $page = $_GET['page'];

    switch ($page) {
        case 'home':
            require_once __DIR__ . '../../app/views/attendance/home.php';
            break;
        case 'add_student':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $studentController->addStudent();
                header("Location: index.php?page=list_students");
                exit;
            } else {
                require_once __DIR__ . '../../app/views/manage/add_student.php';
            }
            break;
        case 'update_student':
            if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
                $student = $studentController->getStudentById($_GET['id']);
                if ($student) {
                    require_once __DIR__ . '../../app/views/manage/update_student.php';
                } else {
                    echo "Student not found.";
                }
            } elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
                $id = $_POST['id'];
                $studentController->updateStudent($id);
                header("Location: index.php?page=list_students");
                exit;
            } else {
                echo "Invalid request.";
            }
            break;
        case 'delete_student':
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
                $id = $_POST['id'];
                $studentController->deleteStudent($id);
                header("Location: index.php?page=list_students");
                exit;
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
            $attendances = $attendanceController->getAllAttendances();
            require_once __DIR__ . '../../app/views/attendance/attendance_session.php';
            break;
        case 'login':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $userController->login();
            } else {
                if ($userController->isLogged()) {
                    header('Location: index.php?page=home');
                    exit;
                }
                require_once __DIR__ . '/../app/views/auth/login.html';
            }
            break;
        case 'register':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $userController->register();
                header('Location: index.php?page=login');
                exit;
            } else {
                if ($userController->isLogged()) {
                    header('Location: index.php?page=home');
                    exit;
                }
                require_once __DIR__ . '/../app/views/auth/register.html';
            }
            break;
        case 'logout':
            $userController->logout();
            header("Location: index.php?page=login");
            exit;
        default:
            require_once __DIR__ . '../../app/views/attendance/home.php';
            break;
    }
} else {
    require_once  __DIR__ . '../../app/views/auth/login.html';
}


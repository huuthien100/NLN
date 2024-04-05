<?php

use App\Controllers\AttendanceController;
use App\Controllers\StudentController;
use App\Controllers\UserController;
use App\Models\AttendanceModel;
use App\Models\StudentModel;
use App\Models\UserModel;
use App\Controllers\ScheduleController;
use App\Models\ScheduleModel;

require '../vendor/autoload.php';
require 'config/connect.php';

session_start();

$user_id = $_SESSION['user_id'] ?? null;

$attendanceController = new AttendanceController(new AttendanceModel($pdo));
$studentController = new StudentController(new StudentModel($pdo, $user_id));
$userController = new UserController(new UserModel($pdo));
$scheduleController = new ScheduleController(new ScheduleModel($pdo));

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
                require_once __DIR__ . '../../app/views/students/add_student.php';
            }
            break;
        case 'update_student':
            if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
                $student = $studentController->getStudentById($_GET['id']);
                if ($student) {
                    require_once __DIR__ . '../../app/views/student/update_student.php';
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
        case 'list_students':
            $students = $studentController->getAllStudents();
            require_once __DIR__ . '../../app/views/students/list_students.php';
            break;
        case 'add_schedule':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $scheduleController->addSchedule();
                header("Location: index.php?page=list_schedules");
                exit;
            } else {
                require_once __DIR__ . '../../app/views/schedule/add_schedule.php';
            }
            break;
        case 'update_schedule':
            if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
                $schedule = $scheduleController->getScheduleById($_GET['id']);
                if ($schedule) {
                    require_once __DIR__ . '../../app/views/schedule/update_schedule.php';
                } else {
                    echo "Schedule not found.";
                }
            } elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
                $id = $_POST['id'];
                $scheduleController->updateSchedule($id);
                header("Location: index.php?page=list_schedules");
                exit;
            } else {
                echo "Invalid request.";
            }
            break;
        case 'delete_schedule':
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
                $id = $_POST['id'];
                $scheduleController->deleteSchedule($id);
                header("Location: index.php?page=list_schedules");
                exit;
            }
            break;
        case 'list_schedules':
            $schedules = $scheduleController->getAllSchedules($user_id);
            require_once __DIR__ . '../../app/views/schedule/list_schedules.php';
            break;
        case 'detail_schedule':
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
                $scheduleId = $_POST['id'];
                $schedule = $scheduleController->getScheduleById($scheduleId);
                $students = $scheduleController->getStudentsAttending($scheduleId);
                $attendances = $attendanceController->getAllAttendances($user_id);
                require_once __DIR__ . '../../app/views/schedule/detail_schedule.php';
            } else {
                echo "Invalid request.";
            }
            break;
        case 'add_student_to_schedule':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $schedule_id = $_POST['schedule_id'];
                $student_ids = $_POST['students'];
                $scheduleController->assignStudentsToSchedule();
                header("Location: index.php?page=list_schedules");
                exit;
            } else {
                $schedules = $scheduleController->getAllSchedules($user_id);
                $students = $studentController->getAllStudents();
                require_once __DIR__ . '../../app/views/schedule/add_student_to_schedule.php';
            }
            break;
        case 'attendance':
            require_once __DIR__ . '../../app/views/attendance/attendance.php';
            break;
        case 'attendance_session':
            $attendances = $attendanceController->getAllAttendances($user_id);
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
    $userController->login();
    require_once  __DIR__ . '../../app/views/auth/login.html';
}

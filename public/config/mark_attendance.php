<?php
session_start();

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    header('Location: login.php');
    exit();
}

require_once 'connect.php';
date_default_timezone_set('Asia/Ho_Chi_Minh');

$data = json_decode(file_get_contents("php://input"), true);

if (!$data || !isset($data['studentname']) || !isset($data['status']) || !isset($data['date'])) {
    http_response_code(400);
    echo json_encode(array("message" => "Invalid request data"));
    error_log("Invalid request data: " . json_encode($data), 3, "error.log");
    exit();
}

$studentname = $data['studentname'];
$status = $data['status'];
$date = $data['date'];
$timestamp = strtotime($date);
$new_date = date('Y-m-d H:i:s', $timestamp);

try {
    // 1. Lấy ID của sinh viên từ tên sinh viên
    $findStudentIdSql = "SELECT id FROM students WHERE student_name = :studentname";
    $findStudentIdStmt = $pdo->prepare($findStudentIdSql);
    $findStudentIdStmt->bindParam(':studentname', $studentname);
    $findStudentIdStmt->execute();
    $student = $findStudentIdStmt->fetch();

    if (!$student) {
        http_response_code(400);
        echo json_encode(array("message" => "Student $studentname not found."));
        error_log("Student $studentname not found.", 3, "error.log");
        exit();
    }

    // 2. Kiểm tra trong bảng schedules xem sinh viên có thuộc vào buổi học nào không
    $checkScheduleSql = "SELECT * FROM schedules WHERE FIND_IN_SET(:student_id, students_attending) > 0 AND user_id = :user_id AND start_time <= :date AND end_time > :date";
    $checkScheduleStmt = $pdo->prepare($checkScheduleSql);
    $checkScheduleStmt->bindParam(':student_id', $student['id']);
    $checkScheduleStmt->bindParam(':user_id', $user_id);
    $checkScheduleStmt->bindParam(':date', $new_date);
    $checkScheduleStmt->execute();
    $schedule = $checkScheduleStmt->fetch();

    if (!$schedule) {
        http_response_code(400);
        echo json_encode(array("message" => "Student is not scheduled for any class at $new_date."));
        error_log("Student is not scheduled for any class at $new_date.", 3, "error.log");
        exit();
    }

    // 3. Kiểm tra trong bảng attendances xem sinh viên đã được điểm danh trong bất kỳ buổi học nào hay chưa
    $checkAttendanceSql = "SELECT * FROM attendances WHERE student_id = :student_id AND schedule_id = :schedule_id";
    $checkAttendanceStmt = $pdo->prepare($checkAttendanceSql);
    $checkAttendanceStmt->bindParam(':student_id', $student['id']);
    $checkAttendanceStmt->bindParam(':schedule_id', $schedule['schedule_id']);
    $checkAttendanceStmt->execute();
    $existingAttendance = $checkAttendanceStmt->fetch();

    if ($existingAttendance) {
        http_response_code(400);
        echo json_encode(array("message" => "Student $studentname has already been marked attendance for this class."));
        error_log("Student $studentname has already been marked attendance for this class.", 3, "error.log");
        exit();
    }

    // 4. Tiến hành điểm danh và thêm thông tin điểm danh mới vào bảng attendances
    $insertAttendanceSql = "INSERT INTO attendances (schedule_id, student_id, status, date) VALUES (:schedule_id, :student_id, :status, :date)";
    $insertAttendanceStmt = $pdo->prepare($insertAttendanceSql);
    $insertAttendanceStmt->bindParam(':schedule_id', $schedule['schedule_id']);
    $insertAttendanceStmt->bindParam(':student_id', $student['id']);
    $insertAttendanceStmt->bindParam(':status', $status);
    $insertAttendanceStmt->bindParam(':date', $new_date);
    $insertAttendanceStmt->execute();

    http_response_code(200);
    echo json_encode(array("message" => "Attendance marked successfully for student: $studentname"));
} catch (PDOException $e) {
    $errorMessage = "Failed to mark attendance: " . $e->getMessage();
    error_log($errorMessage, 3, "error.log");
    http_response_code(500);
    echo json_encode(array("message" => $errorMessage));
}

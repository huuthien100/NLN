<?php
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

try {
    $latestAttendanceSql = "SELECT * FROM attendances WHERE student_id = (SELECT id FROM students WHERE student_name = :studentname) ORDER BY date DESC LIMIT 1";
    $latestAttendanceStmt = $pdo->prepare($latestAttendanceSql);
    $latestAttendanceStmt->bindParam(':studentname', $studentname);
    $latestAttendanceStmt->execute();
    $latestAttendance = $latestAttendanceStmt->fetch();

    if ($latestAttendance) {
        $lastAttendanceTime = strtotime($latestAttendance['date']);
        $currentTime = strtotime($date);
        $timeDiff = ($currentTime - $lastAttendanceTime) / 3600;

        if ($timeDiff < 1) {
            http_response_code(400);
            echo json_encode(array("message" => "Cannot mark attendance for student $studentname. Only one attendance allowed per hour."));
            error_log("Cannot mark attendance for student $studentname. Only one attendance allowed per hour.", 3, "error.log");
            exit();
        }
    }

    $date_vietnam = date('Y-m-d H:i:s', strtotime($date));

    $insertAttendanceSql = "INSERT INTO attendances (student_id, status, date) VALUES ((SELECT id FROM students WHERE student_name = :studentname), :status, :date)";
    $insertAttendanceStmt = $pdo->prepare($insertAttendanceSql);
    $insertAttendanceStmt->bindParam(':studentname', $studentname);
    $insertAttendanceStmt->bindParam(':status', $status);
    $insertAttendanceStmt->bindParam(':date', $date_vietnam);
    $insertAttendanceStmt->execute();

    http_response_code(200);
    echo json_encode(array("message" => "Attendance marked successfully for student: $studentname"));
} catch (PDOException $e) {
    $errorMessage = "Failed to mark attendance: " . $e->getMessage();
    error_log($errorMessage, 3, "error.log");
    http_response_code(500);
    echo json_encode(array("message" => $errorMessage));
}

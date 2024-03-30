<?php
require_once 'connect.php';
date_default_timezone_set('Asia/Ho_Chi_Minh');

$data = json_decode(file_get_contents("php://input"), true);

if (!$data || !isset($data['studentname']) || !isset($data['status']) || !isset($data['date'])) {
    http_response_code(400);
    echo json_encode(array("message" => "Invalid request data"));
    exit();
}

$studentname = $data['studentname'];
$status = $data['status'];
$date = $data['date'];

try {
    // Kiểm tra xem đã điểm danh trong ngày chưa
    $existingAttendanceSql = "SELECT * FROM students WHERE student_name = :studentname AND DATE(date) = CURDATE()";
    $existingAttendanceStmt = $pdo->prepare($existingAttendanceSql);
    $existingAttendanceStmt->bindParam(':studentname', $studentname);
    $existingAttendanceStmt->execute();
    $existingAttendance = $existingAttendanceStmt->fetch();

    if ($existingAttendance) {
        http_response_code(200);
        echo json_encode(array("message" => "Attendance already marked for student $studentname today"));
        exit();
    }

    $date_vietnam = date('Y-m-d H:i:s', strtotime($date));

    $updateSql = "UPDATE students SET status = :status, date = :date WHERE student_name = :studentname";
    $updateStmt = $pdo->prepare($updateSql);
    $updateStmt->bindParam(':studentname', $studentname);
    $updateStmt->bindParam(':status', $status);
    $updateStmt->bindParam(':date', $date_vietnam);
    $updateStmt->execute();

    http_response_code(200);
    echo json_encode(array("message" => "Student status and date updated successfully for student: $studentname"));
} catch (PDOException $e) {
    $errorMessage = "Failed to update student status and date: " . $e->getMessage();
    error_log($errorMessage, 3, "error.log");

    http_response_code(500);
    echo json_encode(array("message" => $errorMessage));
}
?>

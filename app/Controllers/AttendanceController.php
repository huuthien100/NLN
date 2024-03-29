<?php

namespace App\Controllers;

use App\Models\AttendanceModel;

class AttendanceController
{
    protected $attendanceModel;

    public function __construct(AttendanceModel $attendanceModel)
    {
        $this->attendanceModel = $attendanceModel;
    }

    public function markAttendance($student_id, $date, $status)
    {
        $result = $this->attendanceModel->markAttendance($student_id, $date, $status);

        if ($result) {
            echo "Điểm danh thành công!";
        } else {
            echo "Điểm danh không thành công!";
        }
    }

    public function findAttendanceById($id)
    {
        $attendance = $this->attendanceModel->findAttendanceById($id);

        if ($attendance) {
            return $attendance;
        } else {
            echo "Không tìm thấy điểm danh!";
        }
    }

    public function getAllAttendances()
    {
        $attendances = $this->attendanceModel->getAllAttendances();

        return $attendances;
    }
}

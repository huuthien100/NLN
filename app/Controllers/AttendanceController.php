<?php

namespace App\Controllers;

use App\Models\AttendanceModel;

class AttendanceController
{
    private $attendanceModel;

    public function __construct(AttendanceModel $attendanceModel)
    {
        $this->attendanceModel = $attendanceModel;
    }

    public function getAllAttendances()
    {
        $attendances = $this->attendanceModel->getAllAttendances();

        return $attendances;
    }
}

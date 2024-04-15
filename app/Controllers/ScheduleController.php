<?php

namespace App\Controllers;

use App\Models\ScheduleModel;

class ScheduleController
{
    protected $scheduleModel;

    public function __construct(ScheduleModel $scheduleModel)
    {
        $this->scheduleModel = $scheduleModel;
    }

    public function getAllSchedules($user_id)
    {
        $schedules = $this->scheduleModel->getAllSchedules($user_id) ?? [];
        return $schedules;
    }

    public function getScheduleById($id)
    {
        $schedule = $this->scheduleModel->getScheduleById($id);
        return $schedule;
    }

    public function addSchedule()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $scheduleData = [
                'user_id' => $_POST['user_id'],
                'start_time' => $_POST['start_time'],
                'end_time' => $_POST['end_time'],
                'day_of_week' => $_POST['day_of_week'],
                'course_name' => $_POST['course_name'],
                'classroom' => $_POST['classroom']
            ];

            $result = $this->scheduleModel->addSchedule($scheduleData);

            if ($result) {
                echo "Thêm thời khóa biểu thành công.";
            } else {
                echo "Thêm thời khóa biểu thất bại.";
            }
        }
    }

    public function updateSchedule()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $scheduleData = [
                'schedule_id' => $_POST['schedule_id'],
                'start_time' => $_POST['start_time'],
                'end_time' => $_POST['end_time'],
                'day_of_week' => $_POST['day_of_week'],
                'course_name' => $_POST['course_name'],
                'classroom' => $_POST['classroom']
            ];

            $result = $this->scheduleModel->updateSchedule($scheduleData);

            if ($result) {
                echo "Cập nhật thời khóa biểu thành công.";
            } else {
                echo "Cập nhật thời khóa biểu thất bại.";
            }
        }
    }

    public function deleteSchedule($id)
    {
        $result = $this->scheduleModel->deleteSchedule($id);

        if ($result) {
            echo "Thời khóa biểu đã được xóa thành công.";
        } else {
            echo "Xóa thời khóa biểu thất bại.";
        }
    }
    public function assignStudentsToSchedule()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $schedule_id = $_POST['schedule_id'];
            $student_ids = $_POST['students'] ?? [];

            $result = $this->scheduleModel->assignStudentsToSchedule($schedule_id, $student_ids);

            if ($result) {
                echo "Thêm học sinh vào buổi học thành công.";
            } else {
                echo "Đã xảy ra lỗi khi thêm học sinh vào buổi học.";
            }
        }
    }

    public function getStudentsAttending($schedule_id)
    {
        $students = $this->scheduleModel->getStudentsAttending($schedule_id);
        return $students;
    }

    public function removeStudentFromSchedule($scheduleId, $studentId)
    {
        $result = $this->scheduleModel->removeStudentFromSchedule($scheduleId, $studentId);

        if ($result) {
            header("Location: index.php?page=list_schedules");
            exit();
        } else {
            echo "Có lỗi xảy ra khi xóa sinh viên ra khỏi lịch học.";
        }
    }
}

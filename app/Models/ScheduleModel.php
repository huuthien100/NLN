<?php

namespace App\Models;

use PDO;
use PDOException;

class ScheduleModel
{
    protected $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function getAllSchedules($user_id)
    {
        $stmt = $this->db->prepare('SELECT * FROM schedules WHERE user_id = :user_id');
        $stmt->execute(['user_id' => $user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getScheduleById($id)
    {
        $stmt = $this->db->prepare('SELECT * FROM schedules WHERE schedule_id = :id');
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function addSchedule($scheduleData)
    {
        try {
            $stmt = $this->db->prepare('INSERT INTO schedules (user_id, start_time, end_time, day_of_week, course_name, classroom) VALUES (:user_id, :start_time, :end_time, :day_of_week, :course_name, :classroom)');

            return $stmt->execute([
                'user_id' => $scheduleData['user_id'],
                'start_time' => $scheduleData['start_time'],
                'end_time' => $scheduleData['end_time'],
                'day_of_week' => $scheduleData['day_of_week'],
                'course_name' => $scheduleData['course_name'],
                'classroom' => $scheduleData['classroom']
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function updateSchedule($id, $scheduleData)
    {
        try {
            $stmt = $this->db->prepare('UPDATE schedules SET user_id = :user_id, start_time = :start_time, end_time = :end_time, day_of_week = :day_of_week, course_name = :course_name, classroom = :classroom WHERE schedule_id = :id');
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':user_id', $scheduleData['user_id']);
            $stmt->bindParam(':start_time', $scheduleData['start_time']);
            $stmt->bindParam(':end_time', $scheduleData['end_time']);
            $stmt->bindParam(':day_of_week', $scheduleData['day_of_week']);
            $stmt->bindParam(':course_name', $scheduleData['course_name']);
            $stmt->bindParam(':classroom', $scheduleData['classroom']);

            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

    public function deleteSchedule($schedule_id)
    {
        $sql_delete_schedule = "DELETE FROM schedules WHERE schedule_id = :schedule_id";
        $stmt_delete_schedule = $this->db->prepare($sql_delete_schedule);
        $stmt_delete_schedule->bindParam(':schedule_id', $schedule_id);
        return $stmt_delete_schedule->execute();
    }

    public function assignStudentsToSchedule($schedule_id, $student_ids)
    {
        try {
            $stmt = $this->db->prepare('SELECT students_attending FROM schedules WHERE schedule_id = :schedule_id');
            $stmt->execute(['schedule_id' => $schedule_id]);
            $current_students = $stmt->fetch(PDO::FETCH_ASSOC)['students_attending'];

            $current_students_array = ($current_students === null) ? [] : explode(',', $current_students);

            $unique_student_ids = array_unique(array_merge($current_students_array, $student_ids));

            $imploded_student_ids = implode(',', $unique_student_ids);

            $stmt = $this->db->prepare('UPDATE schedules SET students_attending = :student_ids WHERE schedule_id = :schedule_id');
            $stmt->bindParam(':schedule_id', $schedule_id);
            $stmt->bindParam(':student_ids', $imploded_student_ids);
            $stmt->execute();

            return true;
        } catch (PDOException $e) {
            return false;
        }
    }


    public function getStudentsAttending($schedule_id)
    {
        try {
            $stmt = $this->db->prepare('SELECT students_attending FROM schedules WHERE schedule_id = :schedule_id');
            $stmt->execute(['schedule_id' => $schedule_id]);
            $student_ids_string = $stmt->fetch(PDO::FETCH_ASSOC)['students_attending'];

            $student_ids_array = explode(',', $student_ids_string);

            $placeholders = str_repeat('?,', count($student_ids_array) - 1) . '?';
            $sql = "SELECT * FROM students WHERE id IN ($placeholders)";

            $stmt = $this->db->prepare($sql);
            $stmt->execute($student_ids_array);
            $students = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $students;
        } catch (PDOException $e) {
            return [];
        }
    }

    public function removeStudentsFromSchedule($schedule_id, $student_id)
    {
        try {
            $stmt = $this->db->prepare('SELECT students_attending FROM schedules WHERE schedule_id = :schedule_id');
            $stmt->execute(['schedule_id' => $schedule_id]);
            $current_students = $stmt->fetch(PDO::FETCH_ASSOC)['students_attending'];

            if ($current_students !== null) {
                $current_students_array = explode(',', $current_students);

                $key = array_search($student_id, $current_students_array);
                if ($key !== false) {
                    unset($current_students_array[$key]);
                }
                $new_student_ids = implode(',', $current_students_array);

                $stmt = $this->db->prepare('UPDATE schedules SET students_attending = :new_student_ids WHERE schedule_id = :schedule_id');
                $stmt->bindParam(':schedule_id', $schedule_id);
                $stmt->bindParam(':new_student_ids', $new_student_ids);
                $stmt->execute();
            }
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }
}

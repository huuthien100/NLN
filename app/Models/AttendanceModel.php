<?php

namespace App\Models;

use PDO;

class AttendanceModel
{
    protected $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function getAllAttendances()
    {
        $stmt = $this->db->query('SELECT * FROM attendances');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findAttendanceById($id)
    {
        $stmt = $this->db->prepare('SELECT * FROM attendances WHERE id = :id');
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function markAttendance($student_id, $date, $status)
    {
        $stmt = $this->db->prepare('SELECT * FROM attendances WHERE student_id = :student_id AND date = :date');
        $stmt->execute(['student_id' => $student_id, 'date' => $date]);
        $attendance = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$attendance) {
            $stmt = $this->db->prepare('INSERT INTO attendances (student_id, date, status) VALUES (:student_id, :date, :status)');
            return $stmt->execute([
                'student_id' => $student_id,
                'date' => $date,
                'status' => $status
            ]);
        } else {
            $stmt = $this->db->prepare('UPDATE attendances SET status = :status WHERE student_id = :student_id AND date = :date');
            return $stmt->execute([
                'student_id' => $student_id,
                'date' => $date,
                'status' => $status
            ]);
        }
    }
}

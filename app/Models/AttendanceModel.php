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

    public function getAllAttendances($user_id)
    {
        $stmt = $this->db->prepare('SELECT students.*, attendances.date, attendances.status 
                                FROM students 
                                LEFT JOIN attendances ON students.id = attendances.student_id
                                WHERE students.user_id = :user_id');
        $stmt->execute(['user_id' => $user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

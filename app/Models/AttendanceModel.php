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
        $stmt = $this->db->query('SELECT students.*, attendances.date, attendances.status 
                              FROM students 
                              LEFT JOIN attendances ON students.id = attendances.student_id');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

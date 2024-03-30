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
        $stmt = $this->db->query('SELECT * FROM students');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findAttendanceById($id)
    {
        $stmt = $this->db->prepare('SELECT * FROM students WHERE id = :id');
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}

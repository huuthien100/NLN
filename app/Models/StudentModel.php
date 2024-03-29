<?php

namespace App\Models;

use PDO;

class StudentModel
{
    protected $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function getAllStudents()
    {
        $stmt = $this->db->query('SELECT * FROM students');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findStudentById($id)
    {
        $stmt = $this->db->prepare('SELECT * FROM students WHERE id = :id');
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function addStudent($studentData)
    {
        $stmt = $this->db->prepare('INSERT INTO students (student_name, student_id, class, image_path) VALUES (:student_name, :student_id, :class, :image_path)');

        $newFileName = $studentData['student_id'] . '.jpg';
        $studentImageDirectory = "img/studentImage/";
        $imgUrl = $studentImageDirectory . $newFileName;

        if (!is_dir($studentImageDirectory)) {
            mkdir($studentImageDirectory, 0777, true);
        }

        $studentData['image_path'] = $imgUrl;

        move_uploaded_file($_FILES['image_path']['tmp_name'], $studentImageDirectory . $newFileName);

        return $stmt->execute([
            'student_name' => $studentData['student_name'],
            'student_id' => $studentData['student_id'],
            'class' => $studentData['class'],
            'image_path' => $studentData['image_path']
        ]);
    }

    public function updateStudent($id, $studentData)
    {
        $stmt = $this->db->prepare('UPDATE students SET student_name = :student_name, student_id = :student_id, class = :class WHERE id = :id');
        return $stmt->execute([
            'id' => $id,
            'student_name' => $studentData['student_name'],
            'student_id' => $studentData['student_id'],
            'class' => $studentData['class']
        ]);
    }

    public function deleteStudent($id)
    {
        $stmt = $this->db->prepare('DELETE FROM students WHERE id = :id');
        return $stmt->execute(['id' => $id]);
    }


}

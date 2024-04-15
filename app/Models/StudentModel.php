<?php

namespace App\Models;

use PDO;
use PDOException;

class StudentModel
{
    protected $db;
    protected $session;

    public function __construct(PDO $db, $session)
    {
        $this->db = $db;
        $this->session = $session;
    }

    public function getAllStudents()
    {
        $user_id = $_SESSION['user_id'];

        $stmt = $this->db->prepare('SELECT * FROM students WHERE user_id = :user_id');
        $stmt->execute(['user_id' => $user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getStudentById($id)
    {
        $user_id = $_SESSION['user_id'];

        $stmt = $this->db->prepare('SELECT * FROM students WHERE id = :id AND user_id = :user_id');
        $stmt->execute(['id' => $id, 'user_id' => $user_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function addStudent($studentData)
    {
        $user_id = $_SESSION['user_id'];

        try {
            $stmt = $this->db->prepare('INSERT INTO students (student_name, student_id, class, image_path, user_id) VALUES (:student_name, :student_id, :class, :image_path, :user_id)');

            $newFileName = $studentData['student_id'] . '.jpg';
            $studentImageDirectory = "img/studentImage/";
            $imgUrl = $studentImageDirectory . $newFileName;

            if (!is_dir($studentImageDirectory)) {
                mkdir($studentImageDirectory, 0777, true);
            }

            $studentData['image_path'] = $imgUrl;

            move_uploaded_file($_FILES['image_path']['tmp_name'], $studentImageDirectory . $newFileName);

            $stmt->execute([
                'student_name' => $studentData['student_name'],
                'student_id' => $studentData['student_id'],
                'class' => $studentData['class'],
                'image_path' => $studentData['image_path'],
                'user_id' => $user_id
            ]);

            return true;
        } catch (PDOException $e) {
            if ($e->errorInfo[1] === 1062) {
                echo "<script>alert('MSSV đã tồn tại trong cơ sở dữ liệu.');</script>";
            } else {
                echo "<script>alert('Có lỗi xảy ra khi thêm sinh viên.');</script>";
            }
            return false;
        }
    }

    public function updateStudent($id, $studentData)
    {
        try {
            $user_id = $_SESSION['user_id'];

            $newFileName = $studentData['student_id'] . '.jpg';
            $studentImageDirectory = "img/studentImage/";
            $imgUrl = $studentImageDirectory . $newFileName;

            if (!is_dir($studentImageDirectory)) {
                mkdir($studentImageDirectory, 0777, true);
            }

            $studentData['image_path'] = $imgUrl;

            move_uploaded_file($_FILES['image_path']['tmp_name'], $studentImageDirectory . $newFileName);

            $stmt = $this->db->prepare('UPDATE students SET student_name = :student_name, student_id = :student_id, class = :class, image_path = :image_path WHERE id = :id AND user_id = :user_id');
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':student_name', $studentData['student_name']);
            $stmt->bindParam(':student_id', $studentData['student_id']);
            $stmt->bindParam(':class', $studentData['class']);
            $stmt->bindParam(':image_path', $studentData['image_path']);
            $stmt->bindParam(':user_id', $user_id);

            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }


    public function deleteStudent($student_id)
    {
        $user_id = $_SESSION['user_id'];

        $sql_select_image_path = "SELECT image_path FROM students WHERE id = :student_id AND user_id = :user_id";
        $stmt_select_image_path = $this->db->prepare($sql_select_image_path);
        $stmt_select_image_path->bindParam(':student_id', $student_id);
        $stmt_select_image_path->bindParam(':user_id', $user_id);
        $stmt_select_image_path->execute();
        $image_path = $stmt_select_image_path->fetchColumn();

        if (!empty($image_path) && file_exists($image_path)) {
            unlink($image_path);
        }

        $sql_delete_attendances = "DELETE FROM attendances WHERE student_id = :student_id";
        $stmt_delete_attendances = $this->db->prepare($sql_delete_attendances);
        $stmt_delete_attendances->bindParam(':student_id', $student_id);
        $stmt_delete_attendances->execute();

        $sql_delete_student = "DELETE FROM students WHERE id = :student_id AND user_id = :user_id";
        $stmt_delete_student = $this->db->prepare($sql_delete_student);
        $stmt_delete_student->bindParam(':student_id', $student_id);
        $stmt_delete_student->bindParam(':user_id', $user_id);
        $stmt_delete_student->execute();
    }
}

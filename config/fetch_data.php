<?php
require_once 'connect.php';

try {
    $sql = "SELECT image_path, student_name AS label FROM students"; 
    $stmt = $pdo->query($sql);
    
    $data = array();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $data[] = array(
            'image_path' => $row['image_path'],
            'student_name' => $row['label']
        );
    }

    header('Content-Type: application/json');
    echo json_encode($data);
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>

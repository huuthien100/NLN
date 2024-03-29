<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ứng dụng Điểm danh</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <?php if (isset($_GET['page']) && $_GET['page'] === 'attendance') : ?>
        <link rel="stylesheet" href="css/camera.css">
    <?php else : ?>
        <link rel="stylesheet" href="css/home.css">
    <?php endif; ?>

</head>

<body>
    <nav class="col-md-2 d-none d-md-block sidebar">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link" href="index.php?page=add_student">THÊM SINH VIÊN</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="index.php?page=attendance">ĐIỂM DANH</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="index.php?page=list_students">DANH SÁCH SINH VIÊN</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="index.php?page=attendance_session">BUỔI ĐIỂM DANH</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="index.php?page=logout">LOGOUT</a>
            </li>
        </ul>
    </nav>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="img/favicon.ico" type="image/x-icon">
    <title>Ứng dụng Điểm danh</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <?php if (isset($_GET['page']) && $_GET['page'] === 'attendance') : ?>
        <link rel="stylesheet" href="css/camera.css">
    <?php else : ?>
        <link rel="stylesheet" href="css/home.css">
    <?php endif; ?>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
</head>

<body>
    <nav class="col-md-2 d-none d-md-block sidebar">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link" href="index.php?page=attendance">ĐIỂM DANH</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="index.php?page=add_student">THÊM SINH VIÊN</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="index.php?page=add_schedule">THÊM LỊCH HỌC</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="index.php?page=add_student_to_schedule">THÊM SV VÀO TKB</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="index.php?page=list_students">DANH SÁCH SINH VIÊN</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="index.php?page=list_schedules">THỜI KHÓA BIỂU</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="index.php?page=logout">ĐĂNG XUẤT</a>
            </li>
        </ul>
    </nav>

    <?php if (basename($_SERVER['PHP_SELF']) !== 'login.php') : ?>
    <?php endif; ?>
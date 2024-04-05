<?php
if (isset($schedule) && isset($students)) :
?>

<main class="content">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <h1>Chi Tiết Thời Khóa Biểu</h1>
        <div>
            <a href="index.php?page=list_schedules" class="btn btn-danger">Quay Lại</a>
        </div>
    </div>

    <div>
        <h2>Thông tin lịch học</h2>
        <div class="row">
            <div class="col-md-6">
                <p><strong>Thời Gian:</strong> <?= $schedule['start_time'] ?> - <?= $schedule['end_time'] ?></p>
                <p><strong>Ngày Trong Tuần:</strong> <?= $schedule['day_of_week'] ?></p>
            </div>
            <div class="col-md-6">
                <p><strong>Mã HP:</strong> <?= $schedule['course_name'] ?></p>
                <p><strong>Phòng Học:</strong> <?= $schedule['classroom'] ?></p>
            </div>
        </div>
    </div>

    <div id="studentsTableContainer">
        <h2>Danh Sách Sinh Viên Tham Dự</h2>
        <?php if (!empty($students)) : ?>
        <form action="index.php?page=detail_schedule" method="POST">
            <table id="studentsTable" class="table">
                <thead>
                    <tr>
                        <th scope="col">Họ Tên</th>
                        <th scope="col">MSSV</th>
                        <th scope="col">Lớp</th>
                        <th scope="col">Hình Ảnh</th>
                        <th scope="col">Điểm Danh</th>
                        <th scope="col">Xóa</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($students as $student) : ?>
                    <tr>
                        <td><?= $student['student_name'] ?></td>
                        <td><?= $student['student_id'] ?></td>
                        <td><?= $student['class'] ?></td>
                        <td>
                            <?php if (isset($student['image_path']) && !empty($student['image_path'])) : ?>
                            <img src="<?= $student['image_path'] ?>" alt="<?= $student['student_name'] ?>"
                                class="img-thumbnail" style="max-width: 100px;">
                            <?php else : ?>
                            <span>Không có hình ảnh</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php 
                                $attendance_time = '';
                                $status = '';
                                foreach ($attendances as $attendance) {
                                    if ($attendance['student_id'] == $student['student_id'] && strtotime($attendance['date']) >= strtotime($schedule['start_time']) && strtotime($attendance['date']) <= strtotime($schedule['end_time'])) {
                                        $attendance_time = $attendance['date'];
                                        $status = $attendance['status'];
                                        break;
                                    }
                                }
                                if ($attendance_time !== '' && $status !== '') {
                                    echo $attendance_time . ' - ';
                                    echo $status == 'Checked' ? '<span style="color: green; font-weight: bold;">V</span>' : $status;
                                } else {
                                    echo 'Chưa điểm danh';
                                }
                            ?>
                        </td>
                        <td>
                            <input type="checkbox" name="remove_students[]"
                                value="<?= $student['student_id'] ?>">
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <button type="submit" class="btn btn-danger">Xóa Sinh Viên</button>
        </form>
        <?php else : ?>
        <p>Không có sinh viên nào tham dự.</p>
        <?php endif; ?>
    </div>

</main>

<?php
else :
    echo "Dữ liệu không hợp lệ.";
endif;
?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script type="text/javascript" charset="utf8"
    src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">

<script>
$(document).ready(function() {
    $('#studentsTable').DataTable({
        "language": {
            "sProcessing": "Đang xử lý...",
            "sLengthMenu": "Hiển thị _MENU_ mục",
            "sZeroRecords": "Không tìm thấy dữ liệu",
            "sInfo": "Hiển thị từ _START_ đến _END_ của _TOTAL_ mục",
            "sInfoEmpty": "Hiển thị từ 0 đến 0 của 0 mục",
            "sInfoFiltered": "(được lọc từ _MAX_ mục)",
            "sInfoPostFix": "",
            "sSearch": "Tìm:",
            "sUrl": "",
            "oPaginate": {
                "sFirst": "Đầu",
                "sPrevious": "Trước",
                "sNext": "Tiếp",
                "sLast": "Cuối"
            },
            "sEmptyTable": "Không có dữ liệu trong bảng",
            "sLoadingRecords": "Đang tải...",
            "oAria": {
                "sSortAscending": ": Sắp xếp cột tăng dần",
                "sSortDescending": ": Sắp xếp cột giảm dần"
            }
        }
    });
});
</script>

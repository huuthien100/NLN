<?php
$weekdays = array(
    1 => "Chủ Nhật",
    2 => "Thứ Hai",
    3 => "Thứ Ba",
    4 => "Thứ Tư",
    5 => "Thứ Năm",
    6 => "Thứ Sáu",
    7 => "Thứ Bảy"
);
?>
<main class="content">
    <h1>Thêm Sinh Viên Vào Buổi Học</h1>
    <hr>
    <form action="index.php?page=add_student_to_schedule" method="POST">
        <div class="form-group">
            <label for="schedule_id">Chọn Buổi Học</label>
            <select class="form-control" id="schedule_id" name="schedule_id">
                <?php foreach ($schedules as $schedule) : ?>
                    <?php
                    $start_time = strtotime($schedule['start_time']);
                    $time_of_day = ($start_time < strtotime('12:00:00')) ? 'Buổi Sáng' : 'Buổi Chiều';
                    ?>
                    <option value="<?= $schedule['schedule_id'] ?>">
                        <?= $schedule['course_name'] ?> - <?= $weekdays[$schedule['day_of_week']] ?> (<?= $time_of_day ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="students">Chọn Sinh Viên</label><br>
            <table id="studentTable" class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Họ và Tên</th>
                        <th>MSSV</th>
                        <th>Chọn</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($students as $index => $student) : ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><?= $student['student_name'] ?></td>
                            <td><?= $student['student_id'] ?></td>
                            <td><input class="form-check-input" type="checkbox" id="student_<?= $student['id'] ?>" name="students[]" value="<?= $student['id'] ?>"></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <button type="submit" class="btn btn-primary">Thêm</button>
    </form>
</main>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdn.datatables.net/1.11.6/js/jquery.dataTables.min.js"></script>
<script>
    var $j = jQuery.noConflict();
    $j(document).ready(function() {
        $j('#studentTable').DataTable();
    });
</script>

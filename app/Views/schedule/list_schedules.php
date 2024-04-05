<main class="content">
    <h1>Danh Sách Thời Khóa Biểu</h1>
    <table id="scheduleTable" class="table">
        <thead>
            <tr>
                <th scope="col">Thời Gian</th>
                <th scope="col">Ngày Trong Tuần</th>
                <th scope="col">Mã HP</th>
                <th scope="col">Phòng Học</th>
                <th scope="col">Hành Động</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($schedules)) : ?>
                <?php foreach ($schedules as $schedule) : ?>
                    <tr class="data-row">
                        <td><?= $schedule['start_time'] ?> - <?= $schedule['end_time'] ?></td>
                        <td><?= $schedule['day_of_week'] ?></td>
                        <td><?= $schedule['course_name'] ?></td>
                        <td><?= $schedule['classroom'] ?></td>
                        <td>
                            <div class="btn-group" role="group">
                                <form action="index.php?page=detail_schedule" method="POST">
                                    <input type="hidden" name="id" value="<?= $schedule['schedule_id'] ?>">
                                    <button type="submit" class="btn btn-primary mr-2">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </form>
                                <form action="update_schedule.php" method="GET">
                                    <input type="hidden" name="id" value="<?= $schedule['schedule_id'] ?>">
                                    <button type="submit" class="btn btn-primary mr-2">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </form>
                                <form id="deleteForm<?= $schedule['schedule_id'] ?>" action="index.php?page=delete_schedule" method="POST">
                                    <input type="hidden" name="id" value="<?= $schedule['schedule_id'] ?>">
                                    <button type="button" onclick="confirmDelete(<?= $schedule['schedule_id'] ?>)" class="btn btn-danger">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>

                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="5">Không có thời khóa biểu nào.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</main>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<script>
    $(document).ready(function() {
        $('#scheduleTable').DataTable({
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

    function confirmDelete(scheduleId) {
        if (confirm('Bạn có chắc muốn xóa buổi học này không?')) {
            document.getElementById('deleteForm' + scheduleId).submit();
        }
    }

    function viewStudentDetails(scheduleId) {
        window.location.href = 'student_details.php?schedule_id=' + scheduleId;
    }
</script>
<main class="content">
    <h1>Danh sách điểm danh</h1>
    <table id="attendanceTable" class="table">
        <thead>
            <tr>
                <th scope="col">Họ tên</th>
                <th scope="col">Mã sinh viên</th>
                <th scope="col">Lớp</th>
                <th scope="col">Hình ảnh</th>
                <th scope="col">Ngày</th>
                <th scope="col">Trạng Thái</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($attendances)) : ?>
                <?php foreach ($attendances as $attendance) : ?>
                    <tr>
                        <td><?php echo $attendance['student_name']; ?></td>
                        <td><?php echo $attendance['student_id']; ?></td>
                        <td><?php echo $attendance['class']; ?></td>
                        <td>
                            <?php if (isset($attendance['image_path']) && !empty($attendance['image_path'])) : ?>
                                <img src="<?= $attendance['image_path'] ?>" alt="<?= $attendance['student_name'] ?>" class="img-thumbnail" style="max-width: 100px;">
                            <?php else : ?>
                                <span>Không có hình ảnh</span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo $attendance['date']; ?></td>
                        <td>
                            <?php if (isset($attendance['status']) && !empty($attendance['status'])) : ?>
                                <?php if ($attendance['status'] == 'Checked') : ?>
                                    <span style="color: green; font-weight: bold; margin-left:30%">V</span>
                                <?php else : ?>
                                    <?php echo $attendance['status']; ?>
                                <?php endif; ?>
                            <?php else : ?>
                                <span style="color: red; font-weight: bold; margin-left:30%">X</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="6" class="text-center">Không có dữ liệu điểm danh.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</main>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>

<script>
    $(document).ready(function() {
        $('#attendanceTable').DataTable({
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
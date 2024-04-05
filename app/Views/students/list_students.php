<main class="content">
    <h1>Danh sách sinh viên</h1>
    <table id="studentTable" class="table">
        <thead>
            <tr>
                <th scope="col">Họ và tên</th>
                <th scope="col">MSSV</th>
                <th scope="col">Lớp</th>
                <th scope="col">Hình ảnh</th>
                <th scope="col">Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($students)) : ?>
                <?php foreach ($students as $student) : ?>
                    <tr>
                        <td><?= $student['student_name'] ?></td>
                        <td><?= $student['student_id'] ?></td>
                        <td><?= $student['class'] ?></td>
                        <td>
                            <?php if (isset($student['image_path']) && !empty($student['image_path'])) : ?>
                                <img src="<?= $student['image_path'] ?>" alt="<?= $student['student_name'] ?>" class="img-thumbnail" style="max-width: 100px;">
                            <?php else : ?>
                                <span>Không có hình ảnh</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <form action="index.php" method="GET" class="mr-2">
                                    <input type="hidden" name="page" value="update_student">
                                    <input type="hidden" name="id" value="<?= $student['id'] ?>">
                                    <button type="submit" class="btn btn-primary edit-btn">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </form>
                                <form id="deleteForm<?= $student['id'] ?>" action="index.php?page=delete_student" method="POST">
                                    <input type="hidden" name="id" value="<?= $student['id'] ?>">
                                    <button type="button" onclick="confirmDelete(<?= $student['id'] ?>)" class="btn btn-danger delete-btn">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="5">Không có sinh viên nào.</td>
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
        $('#studentTable').DataTable({
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
    })

    function confirmDelete(studentId) {
        if (confirm('Việc xóa thông tin sinh viên sẽ đồng thời xóa dữ liệu điểm danh, bạn có chắc muốn xóa không?')) {
            document.getElementById('deleteForm' + studentId).submit();
        }
    }
</script>

<?php
if (isset($_GET['id'])) {
    $student_id = $_GET['id'];
    $student = $studentController->getStudentById($student_id);
    if (isset($student)) {
?>
        <main class="content">
            <h1>Cập nhật thông tin sinh viên</h1>
            <form action="index.php?page=update_student" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="student_name">Họ và Tên</label>
                    <input type="text" class="form-control" id="student_name" name="student_name" placeholder="Nhập họ và tên" value="<?= $student['student_name'] ?>">
                </div>
                <div class="form-group">
                    <label for="student_id">MSSV</label>
                    <input type="text" class="form-control" id="student_id" name="student_id" placeholder="Nhập MSSV" value="<?= $student['student_id'] ?>">
                </div>
                <div class="form-group">
                    <label for="class">Lớp</label>
                    <input type="text" class="form-control" id="class" name="class" placeholder="Nhập lớp" value="<?= $student['class'] ?>">
                </div>
                <div class="form-group">
                    <label for="image_path">Hình ảnh</label>
                    <input type="file" class="form-control-file" id="image_path" name="image_path" onchange="previewImage(this)">
                    <hr>
                    <?php if (!empty($student['image_path'])) : ?>
                        <img id="current_image" src="<?= $student['image_path'] ?>" alt="Hình ảnh hiện tại" style="max-width: 100px;">
                    <?php else : ?>
                        <span>Không có hình ảnh</span>
                    <?php endif; ?>
                    <img id="preview" src="#" alt="Ảnh xem trước" style="display: none; max-width: 100px;">
                </div>
                <input type="hidden" name="id" value="<?= $student_id ?>">
                <button type="submit" class="btn btn-primary">Cập nhật</button>
            </form>
        </main>

        <script>
            function previewImage(input) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        $('#preview').attr('src', e.target.result).show();
                        $('#current_image').hide();
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            }
        </script>


        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        </body>

        </html>
<?php
    } else {
        echo "Không tìm thấy thông tin sinh viên.";
    }
} else {
    echo "Thiếu ID của sinh viên.";
}
?>
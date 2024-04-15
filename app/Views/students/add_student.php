<main class="content">
    <h1>Thêm Thông Tin Sinh Viên</h1>
    <hr>
    <?php
    if (isset($_SESSION['error_message'])) {
        echo '<div class="alert alert-danger" role="alert">' . $_SESSION['error_message'] . '</div>';
        unset($_SESSION['error_message']);
    }
    ?>
    <form action="index.php?page=add_student" method="POST" enctype="multipart/form-data" id="addStudentForm">
        <div class="form-group">
            <label for="student_name">Họ và Tên</label>
            <input type="text" class="form-control" id="student_name" name="student_name" placeholder="Nhập họ và tên">
        </div>
        <div class="form-group">
            <label for="student_id">MSSV</label>
            <input type="text" class="form-control" id="student_id" name="student_id" placeholder="Nhập MSSV">
        </div>
        <div class="form-group">
            <label for="class">Lớp</label>
            <input type="text" class="form-control" id="class" name="class" placeholder="Nhập lớp">
        </div>
        <div class="form-group">
            <label for="image_path">Hình ảnh</label>
            <input type="file" class="form-control-file" id="image_path" name="image_path" onchange="previewImage(this)">
            <hr>
            <img id="preview" src="#" alt="Ảnh xem trước" style="display: none; max-width: 100px;">
        </div>
        <button type="submit" class="btn btn-primary">Thêm</button>
    </form>
</main>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    $(document).ready(function() {
        $('#addStudentForm').submit(function(e) {
            var studentName = $('#student_name').val();
            var studentId = $('#student_id').val();
            var studentClass = $('#class').val();
            var imageFile = $('#image_path')[0].files[0];

            var studentNameRegex = /^[^\d!@#$%^&*()_+={}\[\]|\\:;\"'<>,.?\/]+$/;
            var studentIdRegex = /^[a-zA-Z]\d{7}$/;
            var studentClassRegex = /^[a-zA-Z0-9\s]+$/;

            if (studentName === '' || studentId === '' || studentClass === '' || !imageFile) {
                alert('Vui lòng nhập đầy đủ thông tin.');
                e.preventDefault();
            } else if (!studentName.match(studentNameRegex)) {
                alert('Họ và tên chỉ được chứa ký tự chữ và dấu cách.');
                e.preventDefault();
            } else if (!studentId.match(studentIdRegex)) {
                alert('MSSV phải có 8 chữ số.');
                e.preventDefault();
            } else if (!studentClass.match(studentClassRegex)) {
                alert('Lớp chỉ được chứa ký tự chữ, số và dấu cách.');
                e.preventDefault();
            }
        });
    });

    function previewImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#preview').attr('src', e.target.result).show();
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>



<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
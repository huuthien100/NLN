<main class="content">
    <h1>Thêm Thông Tin Buổi Học</h1>
    <hr>
    <form action="index.php?page=add_schedule" method="POST" id="addScheduleForm">
        <input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id']; ?>">

        <div class="form-group">
            <label for="start_time">Thời Gian Bắt Đầu</label>
            <input type="datetime-local" class="form-control" id="start_time" name="start_time">
        </div>
        <div class="form-group">
            <label for="end_time">Thời Gian Kết Thúc</label>
            <input type="datetime-local" class="form-control" id="end_time" name="end_time">
        </div>
        <div class="form-group">
            <label for="day_of_week">Ngày Trong Tuần</label>
            <select class="form-control" id="day_of_week" name="day_of_week">
                <option value="2">Thứ Hai</option>
                <option value="3">Thứ Ba</option>
                <option value="4">Thứ Tư</option>
                <option value="5">Thứ Năm</option>
                <option value="6">Thứ Sáu</option>
                <option value="7">Thứ Bảy</option>
                <option value="1">Chủ Nhật</option>
            </select>
        </div>
        <div class="form-group">
            <label for="course_name">Tên Khóa Học</label>
            <input type="text" class="form-control" id="course_name" name="course_name" placeholder="Nhập tên khóa học">
        </div>
        <div class="form-group">
            <label for="classroom">Phòng Học</label>
            <input type="text" class="form-control" id="classroom" name="classroom" placeholder="Nhập phòng học">
        </div>
        <button type="submit" class="btn btn-primary">Thêm</button>
    </form>
</main>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    $(document).ready(function() {
        $('#addScheduleForm').submit(function(e) {
            var startTime = new Date($('#start_time').val());
            var endTime = new Date($('#end_time').val());
            var dayOfWeek = $('#day_of_week').val();
            var courseName = $('#course_name').val();
            var classroom = $('#classroom').val();

            var dayOfWeekRegex = /^[1-7]$/;

            var courseNameRegex = /^(?=.*[a-zA-Z])(?=.*\d)[a-zA-Z\d]{5,}$/;

            var classroomRegex = /^[A-Za-z]+\d+\/\d+$/;

            if (startTime == '' || endTime == '' || courseName == '' || classroom == '') {
                alert('Vui lòng điền đầy đủ thông tin.');
                return false;
            } else if (!courseName.match(courseNameRegex)) {
                alert('Tên khóa học phải có ít nhất 5 ký tự và chứa cả chữ và số.');
                return false;
            } else if (!classroom.match(classroomRegex)) {
                alert('Phòng học phải có dạng "B1/101".');
                return false;
            } else if (startTime >= endTime) {
                alert('Thời gian bắt đầu phải lớn hơn thời gian kết thúc.');
                return false;
            }
            return true;
        });
    });
</script>

</body>

</html>

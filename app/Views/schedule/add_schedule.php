<main class="content">
    <h1>Thêm Thông Tin Thời Khóa Biểu</h1>
    <form action="index.php?page=add_schedule" method="POST">
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
            <input type="text" class="form-control" id="day_of_week" name="day_of_week" placeholder="Nhập ngày trong tuần">
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
</body>

</html>

<main class="content">
    <h1>Gán Học Sinh cho Buổi Học</h1>
    <form action="index.php?page=add_student_to_schedule" method="POST">
        <div class="form-group">
            <label for="schedule_id">Chọn Buổi Học</label>
            <select class="form-control" id="schedule_id" name="schedule_id">
                <?php foreach ($schedules as $schedule) : ?>
                    <option value="<?= $schedule['schedule_id'] ?>"><?= $schedule['course_name'] ?> - <?= $schedule['day_of_week'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="students">Chọn Học Sinh</label><br>
            <?php foreach ($students as $student) : ?>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="student_<?= $student['id'] ?>" name="students[]" value="<?= $student['id'] ?>">
                    <label class="form-check-label" for="student_<?= $student['id'] ?>">
                        <?= $student['student_name'] ?> - <?= $student['student_id'] ?>
                    </label>
                </div>
            <?php endforeach; ?>
        </div>
        <button type="submit" class="btn btn-primary">Gán Học Sinh</button>
    </form>
</main>

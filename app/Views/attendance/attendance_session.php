<main class="content">
    <h1>Danh sách điểm danh</h1>
    <table class="table">
        <thead>
            <tr>
                <th scope="col">Mã Sinh Viên</th>
                <th scope="col">Ngày</th>
                <th scope="col">Trạng Thái</th>
            </tr>
        </thead>
        <tbody>
        <?php if (!empty($attendances)) : ?>
                <?php foreach ($attendances as $attendance) : ?>
                    <tr>
                        <td><?php echo $attendance['student_id']; ?></td>
                        <td><?php echo $attendance['date']; ?></td>
                        <td><?php echo $attendance['status']; ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="3" class="text-center">Không có dữ liệu điểm danh.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</main>

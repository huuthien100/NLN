<main class="content">
    <h1>Danh sách sinh viên</h1>
    <table class="table">
        <thead>
            <tr>
                <th scope="col">Họ và tên</th>
                <th scope="col">MSSV</th>
                <th scope="col">Lớp</th>
                <th scope="col">Hình ảnh</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($students)): ?>
                <?php foreach ($students as $student): ?>
                    <tr>
                        <td><?= $student['student_name'] ?></td>
                        <td><?= $student['student_id'] ?></td>
                        <td><?= $student['class'] ?></td>
                        <td>
                            <?php if (isset($student['image_path']) && !empty($student['image_path'])): ?>
                                <img src="<?= $student['image_path'] ?>" alt="<?= $student['student_name'] ?>" class="img-thumbnail" style="max-width: 100px;">
                            <?php else: ?>
                                <span>Không có hình ảnh</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4">Không có sinh viên nào.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</main>

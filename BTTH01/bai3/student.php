<?php
$filename = '65HTTT_Danh_sach_diem_danh.csv';
$students = [];

if (file_exists($filename) && ($handle = fopen($filename, "r")) !== FALSE) {
    // Đọc dòng đầu tiên (Tiêu đề) - Bỏ qua không lưu vào danh sách sinh viên
    fgetcsv($handle); 

    // Đọc các dòng dữ liệu còn lại
    while (($row = fgetcsv($handle)) !== FALSE) {
        $students[] = $row;
    }
    fclose($handle);
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Danh Sách Sinh Viên</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-4">

    <h2 class="text-center text-primary mb-4">🎓 Danh Sách Sinh Viên</h2>

    <?php if (empty($students)): ?>
        <div class="alert alert-danger">Không tìm thấy file hoặc file rỗng!</div>
    <?php else: ?>
        
        <div class="alert alert-info">
            Số lượng: <strong><?= count($students) ?></strong> sinh viên.
        </div>

        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>STT</th>
                    <th>Mã SV</th>
                    <th>Mật khẩu</th>
                    <th>Họ đệm</th>
                    <th>Tên</th>
                    <th>Lớp</th>
                    <th>Email</th>
                    <th>Mã HP</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($students as $index => $row): ?>
                    <tr>
                        <td><?= $index + 1 ?></td>
                        <td><?= $row[0] ?></td> <td><?= $row[1] ?></td> <td><?= $row[2] ?></td> <td><b><?= $row[3] ?></b></td> <td><?= $row[4] ?></td> <td><?= $row[5] ?></td> <td><?= $row[6] ?></td> </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

    <?php endif; ?>

</body>
</html>
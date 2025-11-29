<?php
// 1. KẾT NỐI DATABASE
require 'db.php'; 

$students = [];

try {
    // 2. LẤY DỮ LIỆU TỪ SQL
    $stmt = $conn->query("SELECT * FROM students");
    $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    echo "Lỗi kết nối: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Danh Sách Sinh Viên (SQL)</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-4">

    <h2 class="text-center text-primary mb-4">🎓 Danh Sách Sinh Viên (Từ CSDL)</h2>

    <?php if (empty($students)): ?>
        <div class="alert alert-warning">
            Chưa có dữ liệu trong Database. <br>
            Vui lòng Import file CSV vào bảng <b>students</b> trước.
        </div>
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
                        
                        <td><?= $row['username'] ?></td> 
                        <td><?= $row['password'] ?></td> 
                        <td><?= $row['lastname'] ?></td> 
                        <td><b><?= $row['firstname'] ?></b></td> 
                        <td><?= $row['lopsinhhoat'] ?></td> 
                        <td><?= $row['email'] ?></td> 
                        <td><?= $row['course_id'] ?></td> 
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

    <?php endif; ?>

</body>
</html>
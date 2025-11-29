<?php
require 'data-flowers.php';
$success = $_GET['success'] ?? "";
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản trị hoa – CRUD</title>
    <style>
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 10px; }
        img { width: 100px; border-radius: 6px; }
        .btn {
            display: inline-block; 
            padding: 6px 12px;
            background: #2196F3;
            color: #fff;
            text-decoration: none;
            border-radius: 4px;
            margin-right: 10px; 
            font-size: 14px;
        }
        .btn-danger { background:#e91e63; }
        .navbar { display:flex; justify-content:space-between; padding:10px; background:#f4f4f4; margin-bottom:20px; }
    </style>
</head>
<body>

<div class="navbar">
    <div><strong>Quản lý Hoa</strong></div>
    <div>
        <a href="index.php">Trang khách</a>
        <a href="create.php" class="btn">+ Thêm hoa</a>
    </div>
</div>

<div class="container">
    <h1>Dashboard</h1>
    
    <?php if ($success == "created"): ?>
        <div style="padding:10px; background:#d1e7dd; color:#0f5132; border-radius:4px;">
            Thêm hoa mới thành công! (giả lập).
        </div>
    <?php endif; ?>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Ảnh</th>
                <th>Tên hoa</th>
                <th>Mô tả</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
        <?php if (!empty($flowers)): ?>
            <?php foreach ($flowers as $index => $flower): ?>
                <tr>
                    <td><?= $index + 1 ?></td>
                    <td><img src="<?= $flower["image"] ?>"></td>
                    <td><?= $flower["name"] ?></td>
                    <td><?= $flower["description"] ?></td>
                    <td>
                        <a class="btn" href="edit.php?id=<?= $index ?>">Sửa</a>
                        <a class="btn btn-danger" href="delete.php?id=<?= $index ?>">Xóa</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="5">Chưa có hoa nào.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>

</body>
</html>

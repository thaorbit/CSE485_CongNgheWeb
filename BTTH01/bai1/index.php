<?php
require 'data-flowers.php';
$success = $_GET['success'] ?? "";
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Danh sách hoa – Người dùng khách</title>
    <style>
        body { font-family: Arial; margin: 20px; }
        .flower { display: flex; margin-bottom: 20px; }
        .flower img { width: 200px; height: auto; margin-right: 20px; border-radius: 8px; }
        h2 { margin-bottom: 5px; }
    </style>
</head>
<body>

<div class="container">
    <h1>Những loại hoa đẹp dịp xuân hè</h1>

    <?php if (!empty($flowers)): ?>
        <?php foreach ($flowers as $flower): ?>
            <div class="flower">
                <img src="<?= $flower["image"] ?>" alt="">
                <div>
                    <h2><?= $flower["name"] ?></h2>
                    <p><?= $flower["description"] ?></p>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Không có hoa để hiển thị.</p>
    <?php endif; ?>
</div>

</body>
</html>

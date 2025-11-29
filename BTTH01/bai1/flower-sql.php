<?php
require "db.php";

// Lấy dữ liệu từ bảng flowers
$stmt = $conn->query("SELECT * FROM flowers");
$flowers = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tạp Chí Các Loài Hoa</title>
    <style>
        /* 1. Cấu hình chung cho trang */
        body {
            font-family: 'Merriweather', 'Times New Roman', serif; /* Font có chân giống báo giấy */
            background-color: #f9f9f9; /* Màu nền xám nhẹ dịu mắt */
            margin: 0;
            padding: 20px;
            color: #333;
        }

        /* 2. Khung chứa nội dung chính (Container) */
        .container {
            max-width: 800px; /* Độ rộng giống khổ báo */
            margin: 0 auto; /* Canh giữa màn hình */
            background-color: #fff;
            padding: 40px;
            box-shadow: 0 0 15px rgba(0,0,0,0.05); /* Đổ bóng nhẹ */
        }

        h1.page-title {
            text-align: center;
            text-transform: uppercase;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
            margin-bottom: 40px;
            letter-spacing: 1px;
        }

        /* 3. Cấu trúc từng bài viết (News Item) */
        .news-item {
            display: flex; /* Dùng Flexbox để chia cột: Ảnh - Chữ */
            margin-bottom: 30px;
            border-bottom: 1px solid #e0e0e0; /* Đường gạch ngang mờ */
            padding-bottom: 30px;
            gap: 20px; /* Khoảng cách giữa ảnh và chữ */
        }

        /* Xử lý ảnh */
        .news-thumb {
            flex: 0 0 240px; /* Chiều rộng cố định của ảnh là 240px */
        }

        .news-thumb img {
            width: 100%;
            height: 160px;
            object-fit: cover; /* Cắt ảnh vừa khung, không bị méo */
            border-radius: 4px;
            transition: transform 0.3s ease; /* Hiệu ứng khi di chuột */
        }

        .news-thumb img:hover {
            transform: scale(1.05); /* Phóng to nhẹ khi di chuột */
        }

        /* Xử lý nội dung chữ */
        .news-content {
            flex: 1; /* Chiếm phần còn lại */
            display: flex;
            flex-direction: column;
        }

        .news-title {
            font-size: 22px;
            font-weight: bold;
            margin-top: 0;
            margin-bottom: 10px;
            color: #2c3e50;
        }

        .news-title:hover {
            color: #b71c1c; /* Đổi màu đỏ khi di chuột giống link báo */
            cursor: pointer;
        }

        .news-desc {
            font-size: 16px;
            line-height: 1.6; /* Khoảng cách dòng thoáng dễ đọc */
            color: #555;
            margin: 0;
            
            /* Giới hạn số dòng hiển thị (nếu mô tả quá dài) */
            display: -webkit-box;
            -webkit-line-clamp: 3; 
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .read-more {
            margin-top: auto;
            color: #007bff;
            font-size: 14px;
            text-decoration: none;
            font-weight: bold;
        }

        /* Responsive: Khi màn hình nhỏ (điện thoại) thì ảnh lên trên, chữ xuống dưới */
        @media (max-width: 600px) {
            .news-item {
                flex-direction: column;
            }
            .news-thumb {
                flex: auto;
            }
            .news-thumb img {
                height: auto;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <h1 class="page-title">Tạp Chí Hương Sắc Mùa Xuân</h1>

    <?php foreach ($flowers as $f): ?>
        <article class="news-item">
            <div class="news-thumb">
                <img src="<?= htmlspecialchars($f["image"]) ?>" alt="<?= htmlspecialchars($f["name"]) ?>">
            </div>

            <div class="news-content">
                <h2 class="news-title"><?= htmlspecialchars($f["name"]) ?></h2>
                <div class="news-desc">
                    <?= nl2br(htmlspecialchars($f["description"])) ?>
                </div>
                <a href="#" class="read-more">Xem chi tiết &rarr;</a>
            </div>
        </article>
    <?php endforeach; ?>
</div>

</body>
</html>
<?php
// --- 1. KẾT NỐI DATABASE ---
require 'db.php';

$questions = [];

try {
    // --- 2. LẤY DỮ LIỆU TỪ SQL ---
    $stmt = $conn->query("SELECT * FROM questions");
    $dbData = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // --- 3. CHUYỂN ĐỔI DỮ LIỆU (ADAPTER) ---
    // Biến đổi dữ liệu từ Database về đúng cấu trúc mảng mà code cũ của bạn đang dùng
    foreach ($dbData as $row) {
        $current = [];

        // Lấy nội dung câu hỏi (Lưu ý: kiểm tra tên cột trong DB của bạn là 'question' hay 'question_content')
        $current["question"] = $row['question']; 

        // Giải mã JSON cột options thành mảng ({"A":"Text"} -> ["A"=>"Text"])
        $current["options"] = json_decode($row['options'], true);

        // Tách chuỗi đáp án đúng ("A, C" -> ["A", "C"])
        $current["correct"] = array_map("trim", explode(",", $row['answer']));

        // Tự động xác định loại Radio hay Checkbox
        $current["type"] = (count($current["correct"]) > 1) ? "checkbox" : "radio";

        // Thêm vào danh sách câu hỏi
        $questions[] = $current;
    }

} catch (Exception $e) {
    echo "Lỗi: " . $e->getMessage();
}

$submitted = ($_SERVER["REQUEST_METHOD"] === "POST");
$score = 0;
$results = []; // Mảng lưu kết quả đã xử lý để dùng lại ở dưới

if ($submitted) {
    $userRaw = $_POST["answer"] ?? [];

    foreach ($questions as $i => $q) {
        // 1. Lấy dữ liệu và chuẩn hóa (Làm 1 lần ở đây)
        $picked = $userRaw[$i] ?? [];
        if (!is_array($picked)) $picked = [$picked];

        // 2. Sắp xếp (Làm 1 lần ở đây)
        sort($picked);
        $correct = $q["correct"];
        sort($correct);

        // 3. So sánh và lưu kết quả
        $isCorrect = ($picked == $correct);
        if ($isCorrect) $score++;

        // 4. Lưu lại vào mảng $results để tí nữa HTML dùng
        $results[$i] = [
            'userAns' => $picked,   // Đáp án người dùng đã chuẩn hóa và sắp xếp
            'isCorrect' => $isCorrect // Trạng thái đúng sai
        ];
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Quiz Database</title>
    <style>
        body { font-family: Arial; background:#f0f2f5; padding:20px; }
        .box { background:#fff; padding:15px; margin-bottom:15px; border-radius:8px; }
        .correct { color:green; font-weight:bold; }
        .wrong { color:red; }
    </style>
</head>
<body>

<h2 style="text-align:center;">Bài Thi Trắc Nghiệm (MySQL)</h2>

<?php if (empty($questions)): ?>
    <div style="text-align:center; color:red;">
        Chưa có dữ liệu. Vui lòng kiểm tra kết nối Database hoặc thêm câu hỏi vào bảng <b>questions</b>.
    </div>
<?php endif; ?>

<?php if ($submitted): ?>
    <div class="box">
        <h3>Kết quả: <?= $score ?>/<?= count($questions) ?></h3>
        <a href="questions-sql.php">Làm lại</a>
    </div>
<?php endif; ?>

<form method="post">
    <?php foreach ($questions as $i => $q): ?>
        
        <?php 
            // Lấy lại dữ liệu đã xử lý ở trên (Không tính toán lại nữa)
            // Nếu chưa nộp bài thì mặc định là mảng rỗng
            $userAns = $submitted ? $results[$i]['userAns'] : [];
            
            // Tạo tên input (Cái này thuộc về hiển thị nên để ở đây ok)
            $inputName = "answer[$i]" . ($q['type'] == 'checkbox' ? '[]' : '');
        ?>

        <div class="box">
            <h3>Câu <?= $i+1 ?>: <?= htmlspecialchars($q["question"]) ?></h3>

            <?php foreach ($q["options"] as $key => $text): ?>
                <?php 
                    // Kiểm tra checked: Dùng biến $userAns đã lấy từ $results
                    $isChosen = in_array($key, $userAns) ? "checked" : "";
                ?>
                <label>
                    <input type="<?= $q["type"] ?>" name="<?= $inputName ?>" value="<?= $key ?>" <?= $isChosen ?>>
                    <b><?= $key ?>.</b> <?= htmlspecialchars($text) ?>
                </label><br>
            <?php endforeach; ?>

            <?php if ($submitted): ?>
                <?php if ($results[$i]['isCorrect']): ?>
                    <div class="correct">✔ Chính xác</div>
                <?php else: ?>
                    <div class="wrong">✘ Sai — Đáp án đúng: <?= implode(", ", $q["correct"]) ?></div>
                <?php endif; ?>
            <?php endif; ?>
        </div>

    <?php endforeach; ?>

    <?php if (!$submitted && !empty($questions)): ?>
        <button type="submit" style="padding:10px 20px;">Nộp bài</button>
    <?php endif; ?>
</form>

</body>
</html>
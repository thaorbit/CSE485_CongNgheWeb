<?php
$questions = [];            
$file = "Quiz.txt";      

if (file_exists($file)) {
    //đọc toàn bộ file và trả về mảng các dòng ($lines).
    $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    //FILE_IGNORE_NEW_LINES loại bỏ ký tự xuống dòng \n ở mỗi phần tử.
    //FILE_SKIP_EMPTY_LINES bỏ qua dòng trắng (nếu trong file có dòng rỗng).
    $current = [];             // Câu hỏi đang đọc

    foreach ($lines as $line) {
        $line = trim($line);

        // Nếu dòng bắt đầu bằng "ANSWER:" => kết thúc 1 câu hỏi
        if (strpos($line, "ANSWER:") === 0) {
            $answers = substr($line, 7);           // Lấy phần sau ANSWER:
            $current["correct"] = array_map("trim", explode(",", $answers));    //tách chuỗi

            // Radio = 1 đáp án, Checkbox = nhiều đáp án
            $current["type"] = (count($current["correct"]) > 1) ? "checkbox" : "radio";

            $questions[] = $current;               // Lưu câu hỏi
            $current = [];                         // Reset để đọc câu tiếp theo
        }
        // Nếu dòng kiểu A. B. C. D. => lựa chọn
        elseif (isset($line[1]) && $line[1] == ".") {
            $key = $line[0];                       // A / B / C / D
            $text = trim(substr($line, 2));        // lấy phần sau A. (bỏ 2 ký tự: A và .), trim để bỏ khoảng trắng.
            $current["options"][$key] = $text;  //lưu lựa chọn vào mảng options theo key 'A' => 'Nội dung...'.
        }
        // Nếu không phải đáp án hoặc lựa chọn => là tiêu đề câu hỏi
        else {
            // Nếu là dòng đầu tiên của câu hỏi
            if (empty($current)) {
                $current = [
                    "question" => $line, // Lưu dòng chữ này làm tiêu đề câu hỏi
                    "options"  => [],   // Tạo sẵn chỗ để chứa A, B, C, D sau này
                    "correct"  => []    // Tạo sẵn chỗ chứa đáp án đúng
                ];
            }
            // Nếu cái này là đoạn văn nối tiếp của câu hỏi dài
            else {
                $current["question"] .= " " . $line; //Nối thêm (.=) dòng mới vào đuôi câu hỏi cũ.
            }
        }
    }
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
    <title>Quiz</title>
    <style>
        body { font-family: Arial; background:#f0f2f5; padding:20px; }
        .box { background:#fff; padding:15px; margin-bottom:15px; border-radius:8px; }
        .correct { color:green; font-weight:bold; }
        .wrong { color:red; }
    </style>
</head>
<body>

<h2 style="text-align:center;">Bài Thi Trắc Nghiệm</h2>

<?php if ($submitted): ?>
    <div class="box">
        <h3>Kết quả: <?= $score ?>/<?= count($questions) ?></h3>
        <a href="questions.php">Làm lại</a>
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
            <h3>Câu <?= $i+1 ?>: <?= $q["question"] ?></h3>

            <?php foreach ($q["options"] as $key => $text): ?>
                <?php 
                    // Kiểm tra checked: Dùng biến $userAns đã lấy từ $results
                    $isChosen = in_array($key, $userAns) ? "checked" : "";
                ?>
                <label>
                    <input type="<?= $q["type"] ?>" name="<?= $inputName ?>" value="<?= $key ?>" <?= $isChosen ?>>
                    <b><?= $key ?>.</b> <?= $text ?>
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

    <?php if (!$submitted): ?>
        <button type="submit" style="padding:10px 20px;">Nộp bài</button>
    <?php endif; ?>
</form>

</body>
</html>
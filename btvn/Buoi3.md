handle_login.php 

```
<?php 
// TODO 1: Khởi động session (Luôn ở dòng đầu tiên)
session_start(); 

// TODO 2: Kiểm tra xem người dùng đã nhấn nút "Đăng nhập" chưa
if (isset($_POST['username'])) { 

    // TODO 3: Lấy dữ liệu 'username' và 'password' từ $_POST 
    $user = $_POST['username']; 
    $pass = $_POST['password']; 

    // TODO 4: (Giả lập) Kiểm tra logic đăng nhập 
    if ($user == 'thao nguyen' && $pass == '1469') { 
         
        // TODO 5: Nếu thành công, lưu tên username vào SESSION 
        $_SESSION['user'] = $user; 
         
        // TODO 6: Chuyển hướng người dùng sang trang "chào mừng" 
        header('Location: welcome.php'); 
        exit; // Luôn gọi exit sau header
         
    } else { 
        // Nếu thất bại, chuyển hướng về login.html
        header('Location: login.html?error=1'); 
        exit; 
    } 

} else {
    // TODO 7: Nếu truy cập trực tiếp không qua POST, đá về login.html
    header('Location: login.html');
    exit;
}
?>
```
welcome.php 

```
<?php 
// KHOI DONG SESSION
session_start();

// Kiểm tra xem người dùng đã đăng nhập chưa
if (isset($_SESSION['user'])) {
    
    // Lấy tên người dùng từ session
    $loggedInUser = $_SESSION['user'];

    // Hiển thị thông báo
    echo "Chào mừng trở lại, $loggedInUser!"; 
    echo "<br>"; 
    echo "Bạn đã đăng nhập thành công."; 
    echo "<br><br>"; 

    // TODO 5: Link đăng xuất tạm thời về login.html
    echo '<a href="login.html">Đăng xuất</a>'; 

} else { 
    // TODO 6: Nếu chưa đăng nhập, đá về login.html
    header('Location: login.html'); 
    exit; 
} 
?>
```
![ảnh kết quả](b3-KQ.png)


Câu hỏi của tôi là: Trong đoạn mã login ở bài làm, mật khẩu đang được kiểm tra bằng cách so sánh trực tiếp với chuỗi plaintext. Nếu triển khai trong thực tế, cách làm này gây ra những rủi ro bảo mật nào, và cần thay thế bằng giải pháp nào để đảm bảo an toàn thông tin người dùng?

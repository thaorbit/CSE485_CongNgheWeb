<?php
// db.php
$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "btth01";
$port = "3306"; 

try {
    // Chuỗi kết nối có thêm port
    $conn = new PDO("mysql:host=$servername;port=$port;dbname=$dbname;charset=utf8mb4", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Lỗi kết nối CSDL: " . $e->getMessage());
}
?>
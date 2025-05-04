<?php
$host = "localhost";
$username = "root";
$password = ""; // Mặc định XAMPP không có password
$database = "db"; // ⚠️ THAY bằng tên database của bạn

// Kết nối
$conn = mysqli_connect($host, $username, $password, $database);

// Kiểm tra kết nối
if (!$conn) {
    die("Kết nối CSDL thất bại: " . mysqli_connect_error());
}
?>
<?php
// config.php

$host = "localhost";
$user = "root";
$password = "";
$database = "daotao"; // Đổi tên CSDL tại đây

$conn = new mysqli($host, $user, $password, $database);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}
?>

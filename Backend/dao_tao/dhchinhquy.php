<?php
$conn = new mysqli("localhost", "root", "", "phq");
$conn->set_charset("utf8");

if ($conn->connect_error) {
    die("Lỗi kết nối: " . $conn->connect_error);
}

if (isset($_POST['muc'], $_POST['tieude'], $_POST['noidung'], $_FILES['image'])) {
    $muc = $_POST['muc'];
    $tieude = $_POST['tieude'];
    $noidung = $_POST['noidung'];

    // Upload hình ảnh nếu có
    $filename = '';
    if (!empty($_FILES['image']['name'])) {
        $upload_dir = "uploads/";
        if (!is_dir($upload_dir)) mkdir($upload_dir);
        
        $filename = time() . "_" . basename($_FILES['image']['name']);
        $target_file = $upload_dir . $filename;
        move_uploaded_file($_FILES['image']['tmp_name'], $target_file);
    }

    // Lưu đường dẫn vào CSDL
    $stmt = $conn->prepare("INSERT INTO noidung_dhchinhquy (muc, tieude, noidung, hinhanh) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $muc, $tieude, $noidung, $filename);
    $stmt->execute();
    $stmt->close();

    echo "✅ Nội dung đã được lưu. <a href='admindhchinhquy.php'>Quay lại</a>";
} else {
    echo "⚠️ Thiếu dữ liệu gửi đi. <a href='admindhchinhquy.php'>Thử lại</a>";
}

// Đóng kết nối
$conn->close();
?>
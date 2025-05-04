<?php
$conn = new mysqli("localhost", "root", "", "phq");
$conn->set_charset("utf8");

if ($conn->connect_error) {
    die("Lỗi kết nối: " . $conn->connect_error);
}

if (isset($_POST['muc'], $_POST['tieude'], $_POST['noidung'])) {
    $muc = $_POST['muc'];
    $tieude = $_POST['tieude'];
    $noidung = $_POST['noidung'];

    $stmt = $conn->prepare("INSERT INTO noidung_hequocte (muc, tieude, noidung) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $muc, $tieude, $noidung);
    $stmt->execute();
    $stmt->close();

    echo "✅ Nội dung đã được lưu. <a href='adminhequocte.php'>Quay lại</a>";
} else {
    echo "⚠️ Thiếu dữ liệu gửi đi. <a href='adminhequocte.php'>Thử lại</a>";
}
?>

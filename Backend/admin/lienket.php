<?php
// Khởi tạo kết nối cơ sở dữ liệu
$conn = new mysqli("localhost", "root", "", "phq");
$conn->set_charset("utf8");

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối cơ sở dữ liệu thất bại: " . $conn->connect_error);
}

// Truy vấn để lấy dữ liệu từ bảng contact_form
$result = $conn->query("SELECT * FROM contact_form ORDER BY created_at DESC");

if (!$result) {
    die("Lỗi truy vấn: " . $conn->error);
}

// Hàm xác định trang hiện tại để làm nổi bật menu item
function isActive($page, $currentPage) {
    return $currentPage === $page ? 'active' : '';
}

?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Trang Liên hệ</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
        }
        .menu-item.active {
            background-color: #4285f4;
            color: white;
        }
    </style>
</head>
<body>

    <!-- Menu item "Liên hệ" -->
    <div class="menu-item <?php echo isActive('lienket', 'lienket'); ?>" onclick="navigateTo('index.php?page=lienket')">
        <div class="menu-icon"><i class="fas fa-book"></i></div>
        <div>Liên hệ</div>
    </div>

    <!-- Nội dung trang Liên hệ -->
    <h1>Danh sách liên hệ</h1>

    <table>
        <tr>
            <th>Họ tên</th>
            <th>Điện thoại</th>
            <th>Email</th>
            <th>Chương trình</th>
            <th>Ghi chú</th>
            <th>Thời gian</th>
        </tr>
        <?php if ($result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['fullname']) ?></td>
                    <td><?= htmlspecialchars($row['phone']) ?></td>
                    <td><?= htmlspecialchars($row['email']) ?></td>
                    <td><?= htmlspecialchars($row['train_program']) ?></td>
                    <td><?= htmlspecialchars($row['note']) ?></td>
                    <td><?= date('d/m/Y H:i:s', strtotime($row['created_at'])) ?></td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="6">Không có dữ liệu nào.</td></tr>
        <?php endif; ?>
    </table>

</body>
</html>

<?php
$conn->close();
?>

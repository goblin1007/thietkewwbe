<?php
// Kết nối đến cơ sở dữ liệu
$conn = new mysqli("localhost", "root", "", "phq");
$conn->set_charset("utf8");

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Lấy loại chương trình từ query string
$type = $_GET['type'] ?? '';

// Lấy dữ liệu từ bảng tương ứng
if ($type == 'phuong_thuc') {
    $result = $conn->query("SELECT * FROM noidung_phuongthuc");
} elseif ($type == 'de_an') {
    $result = $conn->query("SELECT * FROM noidung_dean");
} elseif ($type == 'ban_tin') {
    $result = $conn->query("SELECT * FROM noidung_bantin");
} else {
    die("Loại chương trình không hợp lệ.");
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Tuyển sinh đại học</title>
  <style>
    body {
  font-family: 'Segoe UI', Tahoma, sans-serif;
  background-color: #f2f2f2; /* xám nhẹ */
  margin: 0;
  padding: 0;
}

    h1 {
      text-align: center;
      color: #003366;
      margin-bottom: 40px;
    }
    .post-item {
  background: #fff;
  border-radius: 10px;
  box-shadow: 0 2px 10px rgba(0,0,0,0.05);
  padding: 20px;
  margin: 25px auto; /* căn giữa + khoảng cách dưới */
  display: flex;
  gap: 20px;
  align-items: flex-start;
  max-width: 1000px;  /* 👈 giới hạn chiều rộng */
  width: 100%;
}

    .post-image img {
      width: 150px;
      height: auto;
      border-radius: 8px;
    }
    .post-content {
      flex: 1;
    }
    
    .toggle-title {
      font-size: 20px;
      font-weight: bold;
      color: #0056b3;
      cursor: pointer;
      margin: 0;
    }
    .toggle-title span {
      font-size: 16px;
      margin-left: 10px;
    }
    .post-details {
      max-height: 0;
      overflow: hidden;
      transition: max-height 0.5s ease;
      margin-top: 10px;
    }
    .post-meta {
      font-size: 14px;
      color: #666;
      margin-bottom: 10px;
    }
    .breadcrumb {
      margin: 20px 40px;
      font-size: 14px;
      color: #0056b3; /* theo màu tiêu đề phụ */
      font-weight: bold;
      text-transform: uppercase;
    }
    .breadcrumb a {
      color: #0056b3;
      text-decoration: none;
    }
    .breadcrumb a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>
  <div class="container">
    <?php
    $breadcrumb = [
        'phuong_thuc' => 'Phương thức tuyển sinh đại học chính quy',
        'de_an'       => 'Đề án tuyển sinh',
        'ban_tin'     => 'Bản tin tuyển sinh',
    ];
    ?>
    <div class="breadcrumb">
      <a href="user.php">Trang chủ</a> &gt;
      <a href="user.php">Tuyển sinh</a> &gt;
      <span><?= $breadcrumb[$type] ?? 'Tuyển sinh' ?></span>
    </div>

    <h1>Tuyển sinh đại học</h1>

    <div class="details-section">
      <?php while ($row = $result->fetch_assoc()): ?>
        <div class="post-item">
          <div class="post-image">
          <img src="backendts/uploads/<?= htmlspecialchars($row['hinhanh']); ?>" alt="Hình ảnh">
          </div>
          <div class="post-content">
            <h2 class="toggle-title">
              <?= $row['tieude']; ?> <span>▼</span>
            </h2>
            <div class="post-details">
              <div class="post-meta">🕒 <?= $row['ngaydang']; ?></div>
              <p><?= $row['noidung']; ?></p>
            </div>
          </div>
        </div>
      <?php endwhile; ?>
    </div>

    <!-- Nút quay lại trang trước -->
    <button onclick="window.history.back()" style="margin-top: 20px; margin-left: 80px; padding: 10px 20px; background-color: #0056b3; color: white; border: none; border-radius: 5px; cursor: pointer;">Quay lại</button>

  </div>
  
  <script>
  document.addEventListener("DOMContentLoaded", function () {
    const titles = document.querySelectorAll(".toggle-title");

    titles.forEach(title => {
      title.addEventListener("click", () => {
        const details = title.nextElementSibling;
        const arrow = title.querySelector("span");

        const isOpen = details.style.maxHeight && details.style.maxHeight !== "0px";

        if (isOpen) {
          // Đóng lại nếu đang mở
          details.style.maxHeight = null;
          arrow.textContent = "▼";
        } else {
          // Đóng tất cả trước
          document.querySelectorAll(".post-details").forEach(d => d.style.maxHeight = null);
          document.querySelectorAll(".toggle-title span").forEach(a => a.textContent = "▼");

          // Mở cái vừa click
          details.style.maxHeight = details.scrollHeight + "px";
          arrow.textContent = "▲";
        }
      });
    });
  });
</script>

</body>
</html>

<?php $conn->close(); ?> 
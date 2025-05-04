<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
// Kết nối đến cơ sở dữ liệu
$conn = new mysqli("localhost", "root", "", "daotao");
$conn->set_charset("utf8");

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Lấy loại chương trình từ query string
$type = $_GET['type'] ?? '';

// Lấy dữ liệu từ bảng tương ứng
if ($type == 'dai_hoc_chinh_quy') {
    $result = $conn->query("SELECT * FROM noidung_dhchinhquy");
} elseif ($type == 'sau_dai_hoc') {
    $result = $conn->query("SELECT * FROM noidung_saudaihoc");
} elseif ($type == 'he_quoc_te') {
    $result = $conn->query("SELECT * FROM noidung_hequocte");
} elseif ($type == 'he_tu_xa') {
    $result = $conn->query("SELECT * FROM noidung_hetuxa");
} else {
    die("Loại chương trình không hợp lệ.");
}
?>

  <title>Chương trình đào tạo</title>
  <?php include("../headerweb.php"); ?>
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, sans-serif;
      background-color: #f9f9f9;
      
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
      margin-bottom: 25px;
      display: flex;
      gap: 20px;
      align-items: flex-start;
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
        'dai_hoc_chinh_quy' => 'Đại học chính quy',
        'sau_dai_hoc'       => 'Sau đại học',
        'he_quoc_te'        => 'Đào tạo quốc tế',
        'he_tu_xa'          => 'Hệ từ xa'
    ];
    ?>
    <div class="breadcrumb">
      <a href="/web/frontend/trang chu/trangchu.php">Trang chủ</a> &gt;
      <a href="user.php">Đào tạo</a> &gt;
      <span><?= $breadcrumb[$type] ?? 'Chương trình' ?></span>
    </div>

    <h1>Chương trình đào tạo</h1>

    <div class="details-section">
      <?php while ($row = $result->fetch_assoc()): ?>
        <div class="post-item">
          <div class="post-image">
            <img src="viu/uploads/<?= $row['hinhanh']; ?>" alt="Hình ảnh">
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
    <button onclick="window.history.back()" style="margin-top: 20px; padding: 10px 20px; background-color: #0056b3; color: white; border: none; border-radius: 5px; cursor: pointer;">Quay lại</button>

  </div>
  
  <script src="details.js"></script>
</body>
<?php include("../footer_web.php"); ?>

<?php $conn->close(); ?>

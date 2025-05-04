<?php
// Kết nối CSDL
$conn = new mysqli('localhost', 'root', '', 'phq');
$id = $_GET['id'];
$sql = "SELECT * FROM baiviet WHERE id = $id";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
?>
<div class="breadcrumb"  style="display: flex;
    margin-bottom: 30px;
    font-size: 18px;
    font-weight: bold;
    color: #1F4F9F;">
        <a href="../trang chu/trangchu.php"style=" text-decoration: none;
    color: #1F4F9F;
    transition: color 0.3s ease;">TRANG CHỦ</a>
        <span class="separator">›</span>
        <span class="current">TIN TỨC VÀ SỰ KIỆN </span>
    </div>

    <title><?= $row['tieu_de'] ?></title>

    <link rel="stylesheet" href="news_detail.css">
</head>
<body>

    <h1><?= htmlspecialchars($row['tieu_de']) ?></h1>
    <div class="meta">
        Ngày đăng: <?= date("d/m/Y", strtotime($row['ngay_dang'])) ?> |
        <?= htmlspecialchars($row['chuyenmuc']) ?> |
        
    </div>

    <img src="uploads/<?= $row['hinhanh'] ?>" alt="<?= $row['tieu_de'] ?>">

    <h2><?= htmlspecialchars($row['mo_ta_ngan']) ?></h2>
    <div>
        <?= nl2br($row['noidung']) ?>
    </div>



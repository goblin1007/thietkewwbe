<?php
// Kết nối database
$host = '127.0.0.1';
$dbname = 'phq';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Không thể kết nối đến database: " . $e->getMessage());
}

// Lấy dữ liệu từ database
function getNewsItems($pdo, $type) {
    $sql = "SELECT * FROM baiviet WHERE theloai = :type ORDER BY ngay_dang DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':type' => $type]);

    $newsItems = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $newsItems[] = [
            'title' => $row['tieu_de'],
            'image' => 'uploads/' . $row['hinhanh'],
            'link' => 'news_detail.php?id=' . $row['id'],
            'category' => strtoupper($row['chuyenmuc']),
            'date' => date('d/m/Y', strtotime($row['ngay_dang'])),
            'hot' => (bool)$row['hot']
        ];
    }
    return $newsItems;
}

// Danh mục
$news_categories = [
    'highlight' => [
        'title' => 'Tin tức nổi bật',
        'items' => getNewsItems($pdo, 'tin_tuc')
    ],
    'general' => [
        'title' => 'Sự kiện nổi bật',
        'items' => getNewsItems($pdo, 'su_kien')
    ]
];

// Xác định danh mục đang được chọn
$active_category = $_GET['category'] ?? 'highlight';
if (!array_key_exists($active_category, $news_categories)) {
    $active_category = 'highlight';
}
?>

<?php include("../headerweb.php"); ?>
<link rel="stylesheet" href="/web/frontend/tin_tuc/user.css"?v=222>

<div class="container">
    <div class="breadcrumb">
        <a href="../trang chu/trangchu.php">TRANG CHỦ</a>
        <span class="separator">›</span>
        <span class="current">TIN TỨC VÀ SỰ KIỆN</span>
    </div>

    <div class="tabs">
        <?php foreach ($news_categories as $key => $category): ?>
            <a href="?category=<?= $key ?>" class="tab <?= ($active_category === $key) ? 'active' : '' ?>">
                <?= $category['title'] ?>
            </a>
        <?php endforeach; ?>
    </div>

    <h2 class="news-heading"><?= $news_categories[$active_category]['title'] ?></h2>

    <div class="news-grid">
        <?php foreach ($news_categories[$active_category]['items'] as $item): ?>
            <a href="<?= $item['link'] ?>" class="news-item">
                <div class="news-image-container">
                    <img src="<?= $item['image'] ?>" alt="<?= htmlspecialchars($item['title']) ?>" class="news-image">
                    <?php if ($item['hot']): ?>
                        <span class="hot-label">HOT</span>
                    <?php endif; ?>
                </div>
                <div class="news-content">
                    <h3 class="news-title"><?= htmlspecialchars($item['title']) ?></h3>
                    <div class="news-meta">
                        <div class="news-category"><?= $item['category'] ?></div>
                        <div class="news-date"><?= $item['date'] ?></div>
                    </div>
                </div>
            </a>
        <?php endforeach; ?>
    </div>
</div>

<?php include("../footer_web.php"); ?>

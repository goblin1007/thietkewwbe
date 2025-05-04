<?php

// Xử lý tìm kiếm
$search_query = "";
$search_results = [];

if (isset($_GET['search'])) {
    $search_query = trim($_GET['search']);
    if (!empty($search_query)) {
        $sql = "SELECT * FROM majors WHERE id LIKE '%$search_query%' OR name LIKE '%$search_query%'";
        $result = mysqli_query($conn, $sql);
        $search_results = mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
}
?>
    <link rel="stylesheet" href="style.css"> <!-- Nhúng CSS tại đây -->
<div class="sidebar">
    <h2>Admin</h2>

    <div class="menu">
        <h3>Quản lý đào tạo</h3>
            <li class="has-submenu">
                <a href="admindhchinhquy.php">Đại học chính quy</a>
            </li>
            
            <li class="has-submenu">
                <a href="adminsaudh.php">Sau đại học</a>
            </li>

            <li class="has-submenu">
                <a href="adminhequocte.php">Đào tạo quốc tế</a>
            </li>

            <li class="has-submenu">
                <a href="adminhetuxa.php">Hệ từ xa</a>
            </li>
    </div>

    <!-- Kết quả tìm kiếm -->
    <?php if (!empty($search_results)): ?>
        <div class="search-results">
            <h3>Kết quả tìm kiếm:</h3>
            <ul>
                <?php foreach ($search_results as $row): ?>
                    <li><a href="detail.php?id=<?= $row['id']; ?>"><?= htmlspecialchars($row['name']); ?></a></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php elseif (isset($_GET['search']) && empty($search_results)): ?>
        <p>🔍 Không tìm thấy kết quả nào.</p>
    <?php endif; ?>
</div>

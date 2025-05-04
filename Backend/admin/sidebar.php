<?php
// sidebar.php
// Bạn có thể thêm logic PHP ở đây nếu cần, ví dụ kiểm tra quyền user, làm nổi bật menu đang active,...
$currentPage = basename($_SERVER['PHP_SELF']) . (isset($_GET['page']) ? '?page=' . $_GET['page'] : '');

// Hàm kiểm tra active (ví dụ đơn giản)
function isActive($pageName, $currentPage) {
    // So sánh trang hiện tại với tên trang của menu item
    // Cần logic phức tạp hơn nếu có nhiều cấp hoặc tham số khác nhau
    if (strpos($currentPage, 'page=' . $pageName) !== false) {
        return 'active';
    }
    // Trường hợp trang chủ (không có ?page=...)
    if ($pageName == 'dashboard' && strpos($currentPage, 'page=') === false && basename($_SERVER['PHP_SELF']) == 'index.php') {
         return 'active';
    }
    return '';
}
?>
<div class="sidebar">
    <div class="logo-container">
        <img src="https://tmu.edu.vn/template_dhtm/images/logo-sm.png" alt="Logo trường" class="logo">
        <div class="logo-text">TRƯỜNG ĐẠI HỌC THƯƠNG MẠI</div>
    </div>

    <!-- Sử dụng index.php với tham số 'page' để điều hướng -->
    <div class="menu-item <?php echo isActive('dashboard', $currentPage); ?>" onclick="navigateTo('index.php?page=dashboard')">
        <div class="menu-icon"><i class="fas fa-home"></i></div>
        <div>Trang chủ</div>
    </div>
    <div class="menu-item <?php echo isActive('tintuc', $currentPage); ?>" onclick="navigateTo('index.php?page=tintuc')">
        <div class="menu-icon"><i class="fas fa-newspaper"></i></div>
        <div>Tin tức & Sự kiện</div>
    </div>
    <div class="menu-item <?php echo isActive('tuyensinh', $currentPage); ?>" onclick="navigateTo('index.php?page=tuyensinh')">
        <div class="menu-icon"><i class="fas fa-graduation-cap"></i></div>
        <div>Tuyển sinh</div>
    </div>
    <div class="menu-item <?php echo isActive('daotao', $currentPage); ?>" onclick="navigateTo('index.php?page=daotao')">
        <div class="menu-icon"><i class="fas fa-book"></i></div>
        <div>Đào tạo</div>
    
</div>
<div class="menu-item <?php echo isActive('lienhe', $currentPage); ?>" onclick="navigateTo('index.php?page=lienhe')">
    <div class="menu-icon"><i class="fas fa-envelope"></i></div>
    <div>Liên hệ</div>
</div>

<script>
    function navigateTo(url) {
        // Chuyển hướng người dùng đến URL đã chỉ định
        window.location.href = url;
    }
</script>


    <div class="menu-item" onclick="handleLogout()">
        <div class="menu-icon"><i class="fas fa-sign-out-alt"></i></div>
        <div>Đăng xuất</div>
    </div>

    <script>
        // JavaScript để điều hướng khi click menu item
        function navigateTo(url) {
            window.location.href = url;
        }

        // Giữ lại hàm logout của bạn
        function handleLogout() {
            if(confirm('Bạn có chắc chắn muốn đăng xuất không?')) {
                // Đảm bảo đường dẫn đến logout.php là chính xác từ index.php
                window.location.href = 'admin/logout.php';
            }
        }

        // Hàm activeMenuItem có thể không cần thiết nữa nếu dùng class 'active' từ PHP
        // Hoặc bạn có thể giữ lại nếu muốn hiệu ứng JS thay vì load lại trang hoàn toàn
        // function activateMenuItem(element) {
        //     document.querySelectorAll('.menu-item').forEach(item => item.classList.remove('active'));
        //     element.classList.add('active');
        // }
    </script>
</div>
<?php
session_start(); // Bắt đầu session nếu cần quản lý đăng nhập

// --- KIỂM TRA ĐĂNG NHẬP ---
/*
if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
    header('Location: admin/login.php'); // Hoặc trang login của bạn
    exit;
}
*/

// --- ROUTING ---
$page = isset($_GET['page']) ? trim($_GET['page']) : 'dashboard';
$is_edit_page = isset($_GET['id']); // Kiểm tra xem có phải trang edit không

// --- XÁC ĐỊNH FILE CONTENT VÀ TIÊU ĐỀ TRANG ---
$contentFile = '';
$pageTitle = 'Dashboard'; // Tiêu đề mặc định

switch ($page) {
    case 'dashboard':
        $contentFile = 'admin/giaodien.php';
        $pageTitle = 'Bảng điều khiển';
        break;

    case 'login': // Thêm case cho trang login
        $contentFile = 'admin/login.php';
        $pageTitle = 'Đăng nhập Quản trị';
        break; // Không cần break nếu show_main_layout đã false

    // --- Tin tức ---
    case 'tintuc':
    case 'tintuc_create':
    case 'tintuc_edit':
        $contentFile = 'bai_viet/baivietlist.php';
        if ($page === 'tintuc_edit') { $pageTitle = 'Chỉnh sửa Bài viết'; }
        elseif ($page === 'tintuc_create') { $pageTitle = 'Thêm Bài viết mới'; }
        else { $pageTitle = 'Quản lý Tin tức & Sự kiện'; }
        break;

    // --- Tuyển sinh ---
    case 'tuyensinh':
        $contentFile = 'tuyensinh/backendts/indexts.php';
        $pageTitle = 'Quản lý Tuyển sinh';
        break;
    case 'tuyensinh_phuongthuc':
    case 'tuyensinh_phuongthuc_edit':
        $contentFile = 'tuyensinh/backendts/phuongthuc.php';
        $pageTitle = $is_edit_page ? 'Sửa Phương thức TS' : 'Phương thức Tuyển sinh';
        break;
     case 'tuyensinh_dean':
     case 'tuyensinh_dean_edit':
        $contentFile = 'tuyensinh/backendts/dean.php';
        $pageTitle = $is_edit_page ? 'Sửa Đề án TS' : 'Đề án Tuyển sinh';
        break;
     case 'tuyensinh_bantin':
     case 'tuyensinh_bantin_edit':
         $contentFile = 'tuyensinh/backendts/bantin.php';
         $pageTitle = $is_edit_page ? 'Sửa Bản tin TS' : 'Bản tin Tuyển sinh';
        break;

    // --- Đào tạo ---
    case 'daotao':
        $contentFile = 'dao_tao/index.php';
        $pageTitle = 'Quản lý Đào tạo';
        break;
    case 'daotao_chinhquy':
    case 'daotao_chinhquy_edit':
        $contentFile = 'dao_tao/admindhchinhquy.php';
        $pageTitle = $is_edit_page ? 'Sửa ĐH Chính quy' : 'Đại học Chính quy';
        break;
    case 'daotao_quocte':
    case 'daotao_quocte_edit':
        $contentFile = 'dao_tao/adminhequocte.php';
        $pageTitle = $is_edit_page ? 'Sửa Hệ Quốc tế' : 'Hệ Quốc tế';
        break;
    case 'daotao_tuxa':
    case 'daotao_tuxa_edit':
        $contentFile = 'dao_tao/adminhetuxa.php';
        $pageTitle = $is_edit_page ? 'Sửa Hệ Từ xa' : 'Hệ Từ xa';
        break;
    case 'daotao_saudh':
    case 'daotao_saudh_edit':
        $contentFile = 'dao_tao/adminsaudh.php';
        $pageTitle = $is_edit_page ? 'Sửa Sau Đại học' : 'Sau Đại học';
        break;
            // --- Liên hệ ---
    case 'lienhe':
        $contentFile = 'pages/lienhe.php';
        $pageTitle = 'Liên hệ';
        break;

    default:
        $contentFile = 'admin/404.php'; // Hiển thị trang 404 nếu không khớp route nào
        $pageTitle = 'Không tìm thấy trang';
        // Hoặc nếu muốn về dashboard:
        // $contentFile = 'admin/giaodien.php'; $pageTitle = 'Bảng điều khiển'; $page = 'dashboard';
        break;
}

// --- DANH SÁCH CÁC TRANG CẦN EDITOR ---
$pages_need_tinymce = [
    'tintuc', 'tintuc_create', 'tintuc_edit',
    'tuyensinh_phuongthuc', 'tuyensinh_phuongthuc_edit',
    'tuyensinh_dean', 'tuyensinh_dean_edit',
    'tuyensinh_bantin', 'tuyensinh_bantin_edit',
    'daotao_chinhquy', 'daotao_chinhquy_edit',
    'daotao_quocte', 'daotao_quocte_edit',
    'daotao_tuxa', 'daotao_tuxa_edit',
    'daotao_saudh', 'daotao_saudh_edit'
];
$currentPageNeedsEditor = in_array($page, $pages_need_tinymce);

?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Trị - <?php echo htmlspecialchars($pageTitle); ?> - Trường ĐH Thương Mại</title>

    <!-- CSS Chung -->
    <link href="admin/css/giaodien.css" rel="stylesheet">

    <!-- Load CSS Module nếu có -->
    <?php
        $module_css = '';
        if (strpos($page, 'tuyensinh') === 0 && file_exists('tuyensinh/backendts/style.css')) {
            // $module_css = 'tuyensinh/backendts/style.css';
        } elseif (strpos($page, 'tintuc') === 0 && file_exists('bai_viet/style.css')) {
            $module_css = 'bai_viet/style.css';
        } elseif (strpos($page, 'daotao') === 0 && file_exists('dao_tao/style.css')) {
            $module_css = 'dao_tao/style.css';
        }
        if ($module_css) { echo '<link href="' . htmlspecialchars($module_css) . '" rel="stylesheet">'; }
    ?>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- Load thư viện TinyMCE JS chỉ khi cần thiết -->
    <?php if ($currentPageNeedsEditor): ?>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/5.10.1/tinymce.min.js" referrerpolicy="origin"></script>
    <?php endif; ?>

</head>
<body>
    <div class="container">
        <?php
        // --- SIDEBAR ---
        include 'admin/sidebar.php'; // Include sidebar chung
        ?>

        <div class="main-content">
            <?php
            // --- CONTENT ---
            // Include file nội dung đã xác định
            if (!empty($contentFile) && file_exists($contentFile)) {
                 // Tạo file admin/placeholder.php và admin/404.php nếu chưa có
                 if (basename($contentFile) === 'placeholder.php' && isset($placeholder_message)) {
                      include $contentFile; // Truyền biến cho placeholder nếu cần
                  } elseif(basename($contentFile) === '404.php'){
                      include $contentFile; // Include trang 404
                  }
                  else {
                      include $contentFile; // Include file module chính
                  }
            } else {
                // Hiển thị trang 404 nếu file không tồn tại
                if (file_exists('admin/404.php')) {
                    include 'admin/404.php';
                } else {
                    echo "<h2>Lỗi: Không tìm thấy nội dung trang.</h2>";
                    echo "<p>Đường dẫn dự kiến không tồn tại: " . htmlspecialchars($contentFile) . "</p>";
                }
            }
            ?>
        </div> <!-- // main-content -->
    </div> <!-- // container -->

    <!-- --- FOOTER & COMMON SCRIPTS --- -->
    <script>
        // Các hàm JS dùng chung trên nhiều trang
        function toggleUserDropdown() {
            const dropdown = document.getElementById('userDropdown');
            if (dropdown) { dropdown.classList.toggle('active'); }
        }
        window.addEventListener('click', function(event) {
             const userMenu = document.querySelector('.user-menu');
             const dropdown = document.getElementById('userDropdown');
             if (userMenu && dropdown && dropdown.classList.contains('active') && !userMenu.contains(event.target)) {
                 dropdown.classList.remove('active');
             }
        });
        function showDetailModal(title, content) { alert(`Chi tiết ${title}:\n${content}`); }
        function activateTab(element) { /* ... */ }
        function filterContent(value) { console.log("Lọc:", value); }
        function sortContent(value) { console.log("Sắp xếp:", value); }
        function navigateTo(url) { window.location.href = url; } // Đặt ở đây nếu dùng nhiều nơi

        // --- Khởi tạo TinyMCE ---
        document.addEventListener('DOMContentLoaded', function() {
            // Xác định xem trang này có cần editor không (truyền từ PHP)
            const needsEditor = <?php echo json_encode($currentPageNeedsEditor); ?>;

            if (needsEditor) { // Chỉ thực hiện nếu trang này cần editor
                const editorTextareas = document.querySelectorAll('textarea#noidung, textarea#noidung_editor');

                if (typeof tinymce !== 'undefined' && editorTextareas.length > 0) {
                    console.log('Attempting to initialize TinyMCE on:', editorTextareas);
                    tinymce.init({
                        selector: 'textarea#noidung, textarea#noidung_editor',
                        height: 350,
                        plugins: [
                            'advlist autolink lists link image charmap print preview hr anchor pagebreak',
                            'searchreplace wordcount visualblocks visualchars code fullscreen',
                            'insertdatetime media nonbreaking save table directionality',
                            'emoticons template paste textpattern help'
                        ],
                        toolbar: 'undo redo | formatselect | bold italic backcolor | \
                                  alignleft aligncenter alignright alignjustify | \
                                  bullist numlist outdent indent | removeformat | link image media | code | help',
                        image_advtab: true,
                        // language: 'vi_VN' // Uncomment nếu bạn đã tải gói ngôn ngữ tiếng Việt
                    }).then(function(editors) {
                        console.log('TinyMCE initialized successfully for:', editors.length, 'editors');
                    }).catch(error => console.error("TinyMCE Init Error:", error));
                } else {
                    // Ghi log nếu không khởi tạo được
                    if (typeof tinymce === 'undefined') {
                        console.warn('TinyMCE library (tinymce) is not defined. Check if it was loaded correctly in <head>. Page: <?php echo $page; ?>');
                    }
                    if (editorTextareas.length === 0) {
                        console.warn('TinyMCE library loaded, but no target textarea (#noidung or #noidung_editor) found on this page (<?php echo $page; ?>).');
                    }
                }
            } else {
                 console.log('TinyMCE initialization skipped for this page (<?php echo $page; ?>).');
            }
        }); // End DOMContentLoaded
    </script>
</body>
</html>
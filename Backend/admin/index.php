<?php
// Lấy tham số 'page' từ URL
$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';  // Mặc định là 'dashboard' nếu không có tham số 'page'

// Bao gồm nội dung dựa trên giá trị tham số 'page'
if ($page === 'tintuc') {
    include('tintuc.php');  // Nội dung trang tin tức & sự kiện
} elseif ($page === 'tuyensinh') {
    include('tuyensinh.php');  // Nội dung trang tuyển sinh
} elseif ($page === 'daotao') {
    include('daotao.php');  // Nội dung trang đào tạo
} elseif ($page === 'lienhe') {
    include('lienket.php');  // Nội dung trang liên hệ
} else {
    include('dashboard.php');  // Nội dung trang mặc định (dashboard)
}
?>

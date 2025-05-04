<?php
// Kiểm tra nếu session chưa được khởi tạo thì mới gọi session_start()
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Kiểm tra đăng nhập
if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
    header("Location: ./admin/login.php");
    exit();
}

// Lấy thông tin người dùng từ session
$current_user_id = $_SESSION['user_id'] ?? null;
$current_user_email = $_SESSION['email'] ?? 'Email không xác định';
$current_user_fullname = $_SESSION['ho_ten'] ?? 'Người dùng';
$current_user_avatar_char = strtoupper(mb_substr($current_user_fullname, 0, 1));

// Kiểm tra nếu không có user_id trong session
if ($current_user_id === null) {
    die("Lỗi: Người dùng chưa được xác định.");
}

// Lấy vai trò (vaitro_id) của người dùng từ session
$user_role_id = $_SESSION['vaitro_id'] ?? null;
if ($user_role_id === null) {
    die("Lỗi: Vai trò của người dùng chưa được xác định.");
}

// Kết nối cơ sở dữ liệu và lấy quyền của người dùng
$conn = new mysqli("localhost", "root", "", "phq");
if ($conn->connect_error) {
    die("Kết nối CSDL thất bại: " . $conn->connect_error);
}

// Lấy quyền của người dùng từ bảng quyen_vaitro
$sql_permissions = "SELECT quyen FROM quyen_vaitro WHERE vaitro_id = ?";
$stmt_permissions = $conn->prepare($sql_permissions);
$stmt_permissions->bind_param("i", $user_role_id);
$stmt_permissions->execute();
$result_permissions = $stmt_permissions->get_result();

// Lưu quyền vào mảng
$user_permissions = [];
while ($row = $result_permissions->fetch_assoc()) {
    $user_permissions[] = $row['quyen'];
}

$stmt_permissions->close();
$conn->close();
?>

<div class="top-bar">
    <div class="page-title">Bảng điều khiển</div>
    <div class="user-menu" onclick="toggleUserDropdown()">
        <div class="user-avatar"><?php echo htmlspecialchars($current_user_avatar_char); ?></div>
        <div class="user-name"><?php echo htmlspecialchars($current_user_fullname); ?></div>
        <div><i class="fas fa-chevron-down"></i></div>

        
    </div>
</div>

<!-- Phần thống kê nhanh -->
<div class="dashboard-stats">
    <div class="stat-card" onclick="showDetailModal('Tổng số bài viết', 'Dữ liệu thống kê bài viết (ví dụ: 142 bài).')">
        <div class="stat-title">Tổng số bài viết</div>
        <div class="stat-value">142</div>
    </div>
    <div class="stat-card" onclick="showDetailModal('Lượt truy cập tháng', 'Dữ liệu lượt truy cập (ví dụ: 28,456).')">
        <div class="stat-title">Lượt truy cập tháng</div>
        <div class="stat-value">28,456</div>
    </div>
    <div class="stat-card" onclick="showDetailModal('Tương tác mạng xã hội', 'Thống kê tương tác MXH (ví dụ: 5,782).')">
        <div class="stat-title">Tương tác mạng xã hội</div>
        <div class="stat-value">5,782</div>
    </div>
    
</div>

<!-- Tin tức & sự kiện mới -->
<div class="content-area">
    <div class="content-title">
        <span>Tin tức & Sự kiện mới nhất</span>
        <div class="content-actions">
            <?php if (in_array('CREATE_BAIVIET', $user_permissions)) { ?>
                <button class="btn btn-primary" onclick="navigateTo('index.php?page=tintuc_create')"><i class="fas fa-plus mr-2"></i> Tạo bài viết mới</button>
            <?php } ?>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 100px">Hình ảnh</th>
                <th>Tiêu đề</th>
                <th>Danh mục</th>
                <th>Ngày tạo</th>
                <th>Trạng thái</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
        <?php
        // Kết nối cơ sở dữ liệu bài viết
        $conn_bv = new mysqli("localhost", "root", "", "phq");
        if (!$conn_bv->connect_error) {
            $conn_bv->set_charset("utf8mb4");
            $sql_bv = "SELECT id, tieu_de, hinhanh, theloai, ngay_dang FROM baiviet ORDER BY id DESC LIMIT 5";
            $res_bv = $conn_bv->query($sql_bv);
            if ($res_bv && $res_bv->num_rows > 0) {
                while ($row_bv = $res_bv->fetch_assoc()) {
                    $img_url = (!empty($row_bv['hinhanh']) && file_exists('./bai_viet/uploads/' . $row_bv['hinhanh']))
                        ? 'bai_viet/uploads/' . htmlspecialchars($row_bv['hinhanh'])
                        : 'admin/assets/placeholder.png';
        ?>
                    <tr>
                        <td><img src="<?php echo $img_url; ?>" alt="Thumbnail" class="preview-thumbnail"></td>
                        <td><?php echo htmlspecialchars($row_bv['tieu_de']); ?></td>
                        <td><?php echo htmlspecialchars(ucfirst(str_replace('_',' ',$row_bv['theloai']))); ?></td>
                        <td><?php echo date("d/m/Y", strtotime($row_bv['ngay_dang'])); ?></td>
                        <td><span class="status-badge status-published">Đã xuất bản</span></td>
                        <td>
                            <?php if (in_array('EDIT_BAIVIET', $user_permissions)) { ?>
                                <button class="action-btn edit-btn" onclick="navigateTo('index.php?page=tintuc_edit&id=<?php echo $row_bv['id']; ?>')">
                                    <i class="fas fa-edit"></i> Sửa
                                </button>
                            <?php } ?>
                            <?php if (in_array('DELETE_BAIVIET', $user_permissions)) { ?>
                                <button class="action-btn delete-btn" onclick="deletePost(<?php echo $row_bv['id']; ?>)">
                                    <i class="fas fa-trash-alt"></i> Xóa
                                </button>
                            <?php } ?>
                        </td>
                    </tr>
        <?php
                }
                $res_bv->free_result();
            } else {
                echo '<tr><td colspan="6" style="text-align:center;">Chưa có bài viết nào.</td></tr>';
            }
            $conn_bv->close();
        } else {
            echo '<tr><td colspan="6" style="text-align:center; color:red;">Lỗi kết nối CSDL bài viết.</td></tr>';
        }
        ?>
        </tbody>
    </table>
    <div style="text-align: right; margin-top: 15px;">
        <?php if (in_array('EDIT_BAIVIET', $user_permissions)) { ?>
            <button class="btn btn-primary" onclick="navigateTo('index.php?page=tintuc')"><i class="fas fa-plus mr-2"></i> Xem tất cả bài viết →</button>
        <?php } ?>
    </div>
</div>
<tbody>
  
  
<!-- === JavaScript xử lý đăng xuất === -->
<script>
function handleLogout() {
    if (confirm("Bạn có chắc chắn muốn đăng xuất?")) {
        window.location.href = "./admin/logout.php";
    }
}

// Hàm xóa bài viết
function deletePost(postId) {
    if (confirm("Bạn có chắc chắn muốn xóa bài viết này?")) {
        window.location.href = "delete_post.php?id=" + postId;
    }
}
</script>

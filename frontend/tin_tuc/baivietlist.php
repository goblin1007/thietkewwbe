<?php
// File: Backend/bai_viet/baivietlist.php

// Kết nối CSDL
$conn = new mysqli("localhost", "root", "", "phq");
if ($conn->connect_error) { die("Kết nối thất bại: " . $conn->connect_error); }
$conn->set_charset("utf8mb4");

// Lấy thông tin người dùng từ session
// Nếu đã được gọi ở index.php thì bỏ dòng này
$user_permissions = $_SESSION['user_permissions'] ?? [];
$current_user_id = $_SESSION['user_id'] ?? null;
$user_role_id = $_SESSION['vaitro_id'] ?? null;

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



// ⚠️ CHẶN TRUY CẬP nếu không có quyền sửa bài viết
if (!in_array('EDIT_BAIVIET', $user_permissions)) {
    echo "<p style='color: red; font-weight: bold; padding:20px;'>⛔ Bạn không có quyền truy cập trang quản lý bài viết.</p>";
    exit(); // Dừng thực thi toàn bộ trang
}




// --- Lấy ID bài viết nếu đang sửa ---
$id = $_GET['id'] ?? null;
$is_editing = !empty($id);
$edit_data = null;

if ($is_editing) {
    $stmt_edit = $conn->prepare("SELECT * FROM baiviet WHERE id = ?"); // Lấy dữ liệu gốc
    if ($stmt_edit) {
        $stmt_edit->bind_param("i", $id);
        $stmt_edit->execute();
        $res_edit = $stmt_edit->get_result();
        $edit_data = $res_edit->fetch_assoc();
        $stmt_edit->close();
        if (!$edit_data) {
            echo "<p style='color: red;'>Lỗi: Không tìm thấy bài viết ID " . htmlspecialchars($id) . "</p>";
            $is_editing = false; $id = null;
        }
    } else {
        echo "<p style='color: red;'>Lỗi DB khi lấy dữ liệu sửa: " . $conn->error . "</p>";
        $is_editing = false; $id = null;
    }
}

global $page; // Lấy page từ index.php (để tạo link chính xác)
$current_page_param = $page ?? 'tintuc';

$_POST_DATA = null; // Khởi tạo biến để lưu dữ liệu POST nếu có lỗi

// --- Xử lý Submit Form ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_baiviet'])) {
    if ($current_user_id === null) {
        die("<p style='color:red;'>Lỗi: Yêu cầu đăng nhập để thực hiện.</p>");
    }

    // Lấy dữ liệu từ POST
    $tieude = $conn->real_escape_string($_POST['tieude'] ?? '');
    $mo_ta_ngan = $conn->real_escape_string($_POST['mo_ta_ngan'] ?? '');
    $theloai = $conn->real_escape_string($_POST['theloai'] ?? '');
    $chuyenmuc = $conn->real_escape_string($_POST['chuyenmuc'] ?? '');
    $hot = intval($_POST['hot'] ?? 0);
    // Ưu tiên lấy từ textarea có ID 'noidung_editor' (nếu TinyMCE dùng ID này)
    // Nếu không có thì lấy từ textarea có ID 'noidung'
    $noidung = $conn->real_escape_string($_POST['noidung_editor'] ?? ($_POST['noidung'] ?? ''));
    $filename = $edit_data['hinhanh'] ?? ''; // Giữ ảnh cũ mặc định

    // Xử lý upload ảnh
    if (!empty($_FILES['hinhanh']['name'])) {
        $upload_dir_relative = "uploads/";
        $upload_dir_absolute = __DIR__ . "/" . $upload_dir_relative;
        if (!is_dir($upload_dir_absolute)) { @mkdir($upload_dir_absolute, 0777, true); }

        $filename_new = time() . "_" . preg_replace('/[^a-zA-Z0-9_.-]/', '_', basename($_FILES['hinhanh']['name'])); // Làm sạch tên file
        $target_file = $upload_dir_absolute . $filename_new;
        $file_type = mime_content_type($_FILES['hinhanh']['tmp_name']);

        if (in_array($file_type,['image/jpeg','image/png','image/gif']) && $_FILES['hinhanh']['size']<=5000000 && move_uploaded_file($_FILES['hinhanh']['tmp_name'],$target_file)) {
             $filename = $filename_new;
             if ($is_editing && !empty($edit_data['hinhanh']) && $edit_data['hinhanh'] != $filename && file_exists($upload_dir_absolute . $edit_data['hinhanh'])) {
                 @unlink($upload_dir_absolute . $edit_data['hinhanh']);
             }
        } else {
             echo "<p style='color: red;'>⚠️ Lỗi upload ảnh. File có thể không đúng định dạng (JPG, PNG, GIF), quá lớn (>5MB) hoặc có lỗi khi lưu.</p>";
             $filename = $edit_data['hinhanh'] ?? ''; // Giữ ảnh cũ nếu upload lỗi
        }
    }

    $post_id = $_POST['id'] ?? null;

    if (!empty($post_id)) { // Cập nhật
        $update_id = intval($post_id);
        $query = "UPDATE baiviet SET
                    tieu_de=?, mo_ta_ngan=?, theloai=?, chuyenmuc=?, hot=?,
                    noidung=?, hinhanh=?, updated_by_nhanvien_id=?
                  WHERE id=?";
        $stmt_update = $conn->prepare($query);
        if ($stmt_update) {
            $stmt_update->bind_param("ssssissii",
                                      $tieude, $mo_ta_ngan, $theloai, $chuyenmuc, $hot,
                                      $noidung, $filename, $current_user_id, $update_id);
            if ($stmt_update->execute()) {
                echo "<script>window.location.href = 'index.php?page=tintuc&status=updated';</script>"; exit();
            } else { $error_message = "Lỗi cập nhật bài viết: " . $stmt_update->error; }
            $stmt_update->close();
        } else { $error_message = "Lỗi chuẩn bị câu lệnh cập nhật: " . $conn->error; }

    } else { // Thêm mới
        $query = "INSERT INTO baiviet (tieu_de, mo_ta_ngan, hinhanh, theloai, chuyenmuc, hot,
                                      ngay_dang, noidung, created_by_nhanvien_id, updated_by_nhanvien_id)
                  VALUES (?, ?, ?, ?, ?, ?, NOW(), ?, ?, ?)";
        $stmt_insert = $conn->prepare($query);
        if ($stmt_insert) {
            $stmt_insert->bind_param("ssssissii",
                                      $tieude, $mo_ta_ngan, $filename, $theloai, $chuyenmuc, $hot,
                                      $noidung, $current_user_id, $current_user_id);
            if ($stmt_insert->execute()) {
                echo "<script>window.location.href = 'index.php?page=tintuc&status=added';</script>"; exit();
            } else { $error_message = "Lỗi thêm mới bài viết: " . $stmt_insert->error; }
            $stmt_insert->close();
        } else { $error_message = "Lỗi chuẩn bị câu lệnh thêm mới: " . $conn->error; }
    }

    // Nếu có lỗi xảy ra trong quá trình INSERT/UPDATE, lưu lại dữ liệu POST để điền form
    if (isset($error_message)) {
        echo "<p style='color: red;'>".$error_message."</p>";
        $_POST_DATA = $_POST; // Lưu lại dữ liệu đã nhập
    }
}

// --- Xử lý Xóa ---
if (isset($_GET['delete'])) {
     $delete_id = intval($_GET['delete']);
     // Không cần lấy thông tin trước khi xóa nếu không ghi log chi tiết
     $stmt_delete = $conn->prepare("DELETE FROM baiviet WHERE id = ?");
     if ($stmt_delete) {
         $stmt_delete->bind_param("i", $delete_id);
         if ($stmt_delete->execute()) {
             echo "<script>window.location.href = 'index.php?page=tintuc&status=deleted';</script>"; exit();
         } else { echo "<p style='color: red;'>Lỗi xóa bài viết: " . $stmt_delete->error . "</p>"; }
         $stmt_delete->close();
     } else { echo "<p style='color: red;'>Lỗi chuẩn bị câu lệnh xóa: " . $conn->error . "</p>"; }
}

// --- Lấy dữ liệu form (ưu tiên dữ liệu lỗi POST) ---
$form_data = $edit_data;
if ($_POST_DATA !== null) { // Nếu có lỗi submit trước đó
    $form_data = $_POST_DATA;
    // Ánh xạ lại tên trường và giữ ảnh cũ nếu cần
    $form_data['tieu_de'] = $form_data['tieude'] ?? ''; // Map lại nếu tên khác nhau
    // Giữ lại ảnh cũ khi submit lỗi (quan trọng)
    $form_data['hinhanh'] = $edit_data['hinhanh'] ?? '';
    // Lấy đúng nội dung đã nhập
    $form_data['noidung'] = $form_data['noidung_editor'] ?? ($form_data['noidung'] ?? '');
}


// --- Hiển thị thông báo status ---
if (isset($_GET['status'])) {
    $status = $_GET['status']; $message = '';
    switch ($status) { case 'added': $message = 'Thêm bài viết thành công!'; break; case 'updated': $message = 'Cập nhật bài viết thành công!'; break; case 'deleted': $message = 'Xóa bài viết thành công!'; break; }
    if ($message) { echo "<p class='status-message success'>" . htmlspecialchars($message) . "</p>"; }
}
?>

<!-- Bỏ các thẻ HTML boilerplate -->
<!-- CSS và JS sẽ được load bởi index.php -->
<style>
    body {
        font-family: Arial, sans-serif !important;
    }

    form {
        max-width: 1100px;
        margin: 0 auto 20px auto;
        background-color: #f9f9f9;
        padding: 25px 25px;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    h2, h3 {
        margin-left: 40px;
        color: #333;
    }

    label {
        display: block;
        margin-top: 15px;
        font-weight: bold;
    }

    input[type="text"],
    select,
    textarea {
        width: 100%;
        padding: 10px;
        margin-top: 5px;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
        font-size: 14px;
    }

    input[type="file"] {
        margin-top: 5px;
    }

    .submit-button,
    .button-link {
        margin-top: 20px;
        margin-left: 20px;
        display: inline-block;
        padding: 10px 20px;
        background-color:rgb(6, 47, 92);
        color: white;
        border: none;
        border-radius: 5px;
        text-decoration: none;
        cursor: pointer;
    }

    .submit-button:hover,
    .button-link:hover {
        background-color:rgb(6, 47, 92);
    }

    .status-message.success {
        color: green;
        margin-left: 40px;
        font-weight: bold;
    }

    table.table {
        width: 95%;
        margin: 20px auto 50px auto;
        border-collapse: collapse;
        background: #fff;
        box-shadow: 0 0 10px rgba(0,0,0,0.05);
    }

    table.table th,
    table.table td {
        padding: 12px 15px;
        border: 1px solid #ddd;
        text-align: center;
        font-size: 14px;
    }

    table.table th {
        background-color: #f0f0f0;
    }

    table.table img {
        border-radius: 4px;
    }

    a.edit, a.delete {
    text-decoration: none;
    padding: 3px 8px;
    border-radius: 2px;
    color: white;
    font-weight: bold;
    display: inline-block;
}

a.edit {
    background-color: #28a745;
    margin-right: 6px;
}

a.delete {
    background-color: #dc3545;
}

a.edit:hover {
    background-color: #218838;
}

a.delete:hover {
    background-color: #c82333;
}
.action-buttons {
    display: flex;
    justify-content: center;
    align-items: center; /* NEW: canh giữa theo chiều dọc */
    gap: 6px;
    height: 100%;
}

table.table td {
    vertical-align: middle; /* NEW: canh giữa nội dung trong ô */
}


</style>
<!-- Link quay về trang dashboard (hoặc trang danh sách tin tức chính) -->
<a href="index.php?page=dashboard" class="button-link">← Quay về Bảng điều khiển</a>

<!-- Tiêu đề form động -->
<h2><?php echo $is_editing ? 'Chỉnh sửa bài viết' : 'Thêm bài viết mới'; ?></h2>

<!-- Form thêm/sửa bài viết -->
<!-- Action trỏ về trang hiện tại (do index.php quản lý) -->
<form action="index.php?page=<?php echo $current_page_param; ?><?php echo $is_editing ? '&id=' . $id : ''; ?>" method="POST" enctype="multipart/form-data">
    <!-- Input ẩn chứa ID bài viết (khi sửa) -->
    <input type="hidden" name="id" value="<?php echo htmlspecialchars($form_data['id'] ?? ''); ?>">

    <!-- Trường upload hình ảnh -->
    <label for="hinhanh">Hình ảnh đại diện:</label>
    <input type="file" name="hinhanh" id="hinhanh" accept="image/jpeg, image/png, image/gif">
    <?php
    // Hiển thị ảnh hiện tại nếu có
    $current_image_url = '';
    // Lấy ảnh từ $edit_data nếu đang sửa, hoặc từ $form_data nếu submit lỗi (đã giữ ảnh cũ)
    $image_to_display = $form_data['hinhanh'] ?? null;
    if (!empty($image_to_display) && file_exists(__DIR__ . "/uploads/" . $image_to_display)) {
        $current_image_url = "bai_viet/uploads/" . htmlspecialchars($image_to_display); // Đường dẫn từ index.php
    }
    ?>
    <?php if ($current_image_url): ?>
        <p>Ảnh hiện tại: <img src="<?php echo $current_image_url; ?>" width="100" alt="Ảnh hiện tại"></p>
    <?php elseif($is_editing): ?>
        <p>(Chưa có ảnh)</p>
    <?php endif; ?>

    <!-- Trường Tiêu đề -->
    <label for="tieude">Tiêu đề:</label>
    <input type="text" name="tieude" id="tieude" required style="width:100%;" value="<?php echo htmlspecialchars($form_data['tieu_de'] ?? ($form_data['tieude'] ?? '')); ?>">

    <!-- Trường Mô tả ngắn -->
    <label for="mo_ta_ngan">Mô tả ngắn:</label>
    <textarea name="mo_ta_ngan" id="mo_ta_ngan" rows="4"><?php echo htmlspecialchars($form_data['mo_ta_ngan'] ?? ''); ?></textarea>

    <!-- Trường Thể loại -->
    <label for="theloai">Thể loại:</label>
    <select name="theloai" id="theloai" required>
        <?php $current_theloai = $form_data['theloai'] ?? ''; ?>
        <option value="">-- Chọn thể loại --</option>
        <option value="tin_tuc" <?php echo ($current_theloai == 'tin_tuc') ? 'selected' : ''; ?>>Tin tức</option>
        <option value="su_kien" <?php echo ($current_theloai == 'su_kien') ? 'selected' : ''; ?>>Sự kiện</option>
        <option value="thong_bao" <?php echo ($current_theloai == 'thong_bao') ? 'selected' : ''; ?>>Thông báo</option>
        <option value="hoat_dong" <?php echo ($current_theloai == 'hoat_dong') ? 'selected' : ''; ?>>Hoạt động</option>
        <!-- Thêm các thể loại khác nếu cần -->
    </select>

    <!-- Trường Chuyên mục -->
    <label for="chuyenmuc">Chuyên mục:</label>
    <input type="text" name="chuyenmuc" id="chuyenmuc" value="<?php echo htmlspecialchars($form_data['chuyenmuc'] ?? ''); ?>">

    <!-- Trường HOT -->
    <label for="hot">Nổi bật (HOT):</label>
    <select name="hot" id="hot" required>
        <?php $current_hot = $form_data['hot'] ?? 0; ?>
        <option value="0" <?php echo ($current_hot == 0) ? 'selected' : ''; ?>>Không</option>
        <option value="1" <?php echo ($current_hot == 1) ? 'selected' : ''; ?>>Có</option>
    </select>

    <!-- Trường Nội dung (Textarea cho TinyMCE) -->
    <label for="noidung_editor">Nội dung chi tiết:</label>
    <!-- ID này phải khớp với selector trong index.php -->
    <textarea name="noidung_editor" id="noidung_editor" rows="15"><?php echo htmlspecialchars($form_data['noidung'] ?? ''); ?></textarea>

    <!-- Nút Submit (Thêm name) -->
    <button type="submit" name="submit_baiviet" class="submit-button">
        <?php echo $is_editing ? 'Cập nhật bài viết' : 'Lưu bài viết mới'; ?>
    </button>

    <!-- Nút Hủy sửa (chỉ hiển thị khi đang sửa) -->
    <?php if ($is_editing): ?>
        <a href="index.php?page=tintuc" class="button-link" style="background-color: #6c757d;">Huỷ sửa</a>
    <?php endif; ?>
</form>

<hr style="margin-top: 30px; margin-bottom: 20px;">

<!-- Tiêu đề cho danh sách -->
<h3>Danh sách bài viết hiện có</h3>

<!-- Bảng hiển thị danh sách bài viết -->
<table border="1" cellpadding="10" cellspacing="0" class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Tiêu đề</th>
            <th>Hình ảnh</th>
            <th>Thể loại</th>
            <th>Chuyên mục</th>
            <th>HOT</th>
            <th>Ngày đăng</th>
            <th>Người tạo</th> <!-- CỘT MỚI -->
            <th>Sửa cuối</th> <!-- CỘT MỚI -->
            <th>Hành động</th>
        </tr>
    </thead>
    <tbody>
        <?php
        // Câu lệnh SELECT với JOIN để lấy tên người tạo/sửa
        $sql_list = "SELECT
                        bv.*,
                        creator.username AS creator_username,
                        updater.username AS updater_username
                    FROM baiviet bv
                    LEFT JOIN nhanvien creator ON bv.created_by_nhanvien_id = creator.id
                    LEFT JOIN nhanvien updater ON bv.updated_by_nhanvien_id = updater.id
                    ORDER BY bv.id DESC";
        $res_list = $conn->query($sql_list);

        if ($res_list && $res_list->num_rows > 0):
            while ($r = $res_list->fetch_assoc()):
                // Xử lý đường dẫn ảnh thu nhỏ
                $list_image_url = '';
                if (!empty($r['hinhanh']) && file_exists(__DIR__ . "/uploads/" . $r['hinhanh'])) {
                    $list_image_url = "bai_viet/uploads/" . htmlspecialchars($r['hinhanh']);
                }
        ?>
            <tr>
                <td><?php echo $r['id']; ?></td>
                <td style="max-width: 250px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;"><?php echo htmlspecialchars($r['tieu_de']); ?></td>
                <td><img src="<?php echo $list_image_url ?: 'admin/assets/placeholder.png'; ?>" width="80" alt="Thumb"></td>
                <td><?php echo htmlspecialchars(ucfirst(str_replace('_',' ',$r['theloai']))); ?></td>
                <td><?php echo htmlspecialchars($r['chuyenmuc']); ?></td>
                <td><?php echo $r['hot'] ? '<span style="color:red;font-weight:bold;">🔥</span>' : 'Không'; ?></td>
                <td><?php echo date("d/m/Y H:i", strtotime($r['ngay_dang'])); ?></td>
                <!-- Hiển thị username người tạo -->
                <td><?php echo htmlspecialchars($r['creator_username'] ?? 'N/A'); ?></td>
                <!-- Hiển thị username người sửa cuối -->
                <td><?php echo htmlspecialchars($r['updater_username'] ?? 'N/A'); ?></td>
                <td>
    
     <!-- Link Sửa -->
     <a href="index.php?page=tintuc_edit&id=<?php echo $r['id']; ?>" class="edit">Sửa</a>
                    <!-- Link Xóa -->
                    <a href="index.php?page=<?php echo $current_page_param; ?>&delete=<?php echo $r['id']; ?>" class="delete" onclick="return confirm('Bạn chắc chắn muốn xóa bài viết này?')">Xoá</a>
                </td>

            </tr>
        <?php
            endwhile;
        else: // Nếu không có bài viết nào
            echo '<tr><td colspan="10" style="text-align: center; padding: 20px;">Chưa có bài viết nào được tạo.</td></tr>'; // Cập nhật colspan = 10
        endif;
        if ($res_list) $res_list->free_result(); // Giải phóng bộ nhớ
        ?>
    </tbody>
</table>

<?php
// Đóng kết nối CSDL
if (isset($conn) && $conn instanceof mysqli) { $conn->close(); }
?>
<!-- Bỏ các thẻ HTML đóng -->
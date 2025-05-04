





<?php
// File: Backend/dao_tao/admindhchinhquy.php
// Được include bởi Backend/index.php khi page=daotao_chinhquy

// Đặt CSS vào đầu file PHP của bạn



$conn = new mysqli("localhost", "root", "", "phq");
if ($conn->connect_error) { die("Kết nối thất bại: " . $conn->connect_error); }
$conn->set_charset("utf8");


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
if (!in_array('EDIT_NOIDUNG_DHCHINHQUY', $user_permissions)) {
    echo "<p style='color: red; font-weight: bold; padding:20px;'>⛔ Bạn không có quyền truy cập trang quản lý bài viết.</p>";
    exit(); // Dừng thực thi toàn bộ trang
}

$id = $_GET['id'] ?? null;
$is_editing = !empty($id);
$edit_data = null;

if ($is_editing) {
    $stmt_edit = $conn->prepare("SELECT * FROM noidung_dhchinhquy WHERE id = ?");
    if($stmt_edit){
        $stmt_edit->bind_param("i", $id); $stmt_edit->execute();
        $res_edit = $stmt_edit->get_result(); $edit_data = $res_edit->fetch_assoc();
        $stmt_edit->close();
         if (!$edit_data) { echo "<p style='color: red;'>Lỗi: Không tìm thấy nội dung ID " . htmlspecialchars($id) . "</p>"; $is_editing = false; $id = null; }
    } else { echo "<p style='color: red;'>Lỗi DB: " . $conn->error . "</p>"; $is_editing = false; $id = null; }
}

global $page; $current_page_param = $page ?? 'daotao_chinhquy';

// --- Xử lý POST ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_dhchinhquy'])) {
    $muc = $_POST['muc'] ?? '';
    $tieude = $conn->real_escape_string($_POST['tieude'] ?? '');
    $noidung = $conn->real_escape_string($_POST['noidung'] ?? '');
    $filename = $edit_data['hinhanh'] ?? '';

    if (!empty($_FILES['hinhanh']['name'])) {
        $upload_dir_relative = "uploads/"; $upload_dir_absolute = __DIR__ . "/" . $upload_dir_relative;
        if (!is_dir($upload_dir_absolute)) { @mkdir($upload_dir_absolute, 0777, true); }
        $filename_new = time() . "_" . basename($_FILES['hinhanh']['name']);
        $target_file = $upload_dir_absolute . $filename_new;
        if (move_uploaded_file($_FILES['hinhanh']['tmp_name'], $target_file)) {
            $filename = $filename_new;
            if ($is_editing && !empty($edit_data['hinhanh']) && $edit_data['hinhanh'] != $filename && file_exists($upload_dir_absolute . $edit_data['hinhanh'])) { @unlink($upload_dir_absolute . $edit_data['hinhanh']); }
        } else { echo "<p style='color:red;'>⚠️ Lỗi upload ảnh.</p>"; $filename = $edit_data['hinhanh'] ?? ''; }
    }

    $post_id = $_POST['id'] ?? null;
    if (!empty($post_id)) { // Cập nhật
        $update_id = intval($post_id); $query = "UPDATE noidung_dhchinhquy SET muc=?, tieude=?, noidung=?, hinhanh=? WHERE id=?";
        $stmt_update = $conn->prepare($query);
        if($stmt_update){ $stmt_update->bind_param("ssssi", $muc, $tieude, $noidung, $filename, $update_id);
            if($stmt_update->execute()){ echo "<script>window.location.href = 'index.php?page=daotao_chinhquy&status=updated';</script>"; exit(); } else { echo "<p style='color:red;'>Lỗi cập nhật: ".$stmt_update->error."</p>"; } $stmt_update->close();
        } else { echo "<p style='color:red;'>Lỗi DB: ".$conn->error."</p>"; }
    } else { // Thêm mới
        $query = "INSERT INTO noidung_dhchinhquy (muc, tieude, noidung, hinhanh, ngaydang) VALUES (?, ?, ?, ?, NOW())";
        $stmt_insert = $conn->prepare($query);
        if($stmt_insert){ $stmt_insert->bind_param("ssss", $muc, $tieude, $noidung, $filename);
             if($stmt_insert->execute()){ echo "<script>window.location.href = 'index.php?page=daotao_chinhquy&status=added';</script>"; exit(); } else { echo "<p style='color:red;'>Lỗi thêm mới: ".$stmt_insert->error."</p>"; } $stmt_insert->close();
        } else { echo "<p style='color:red;'>Lỗi DB: ".$conn->error."</p>"; }
    }
    $_POST_DATA = $_POST;
}

// --- Xử lý Xóa ---
if (isset($_GET['delete'])) {
    $delete_id = intval($_GET['delete']); $stmt_delete = $conn->prepare("DELETE FROM noidung_dhchinhquy WHERE id = ?");
    if($stmt_delete){ $stmt_delete->bind_param("i", $delete_id);
        if($stmt_delete->execute()){ echo "<script>window.location.href = 'index.php?page=daotao_chinhquy&status=deleted';</script>"; exit(); } else { echo "<p style='color:red;'>Lỗi xóa: ".$stmt_delete->error."</p>"; } $stmt_delete->close();
    } else { echo "<p style='color:red;'>Lỗi DB: ".$conn->error."</p>"; }
}

$form_data = $edit_data; if (isset($_POST_DATA)) { $form_data = $_POST_DATA; /* map trường */ $form_data['hinhanh'] = $edit_data['hinhanh'] ?? ''; }

// --- Hiển thị thông báo ---
if (isset($_GET['status'])) {
    $status = $_GET['status']; $message = '';
    switch ($status) { case 'added': $message = 'Thêm nội dung thành công!'; break; case 'updated': $message = 'Cập nhật nội dung thành công!'; break; case 'deleted': $message = 'Xóa nội dung thành công!'; break; }
    if ($message) { echo "<p class='status-message status-".($status=='deleted'?'deleted':'success')."'>" . htmlspecialchars($message) . "</p>"; }
}
?>
<!-- Bỏ HTML boilerplate -->
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
<a href="index.php?page=daotao" class="button-link">← Quay lại QL Đào tạo</a>
<h2>Quản lý nội dung - Đại học chính quy</h2>

<form action="index.php?page=daotao_chinhquy<?php echo $is_editing ? '&id=' . $id : ''; ?>" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?php echo htmlspecialchars($form_data['id'] ?? ''); ?>">

    <label for="hinhanh">Hình ảnh:</label>
    <input type="file" name="hinhanh" id="hinhanh" accept="image/*">
    <?php $current_image_url = (!empty($edit_data['hinhanh']) && file_exists(__DIR__ . "/uploads/" . $edit_data['hinhanh'])) ? "dao_tao/uploads/" . htmlspecialchars($edit_data['hinhanh']) : ''; ?>
    <?php if ($current_image_url): ?><p>Ảnh hiện tại: <img src="<?php echo $current_image_url; ?>" width="100"></p><?php endif; ?>

    <label for="muc">Chọn mục:</label>
    <select name="muc" id="muc" required>
      <option value="thongbao" <?= (($form_data['muc'] ?? '')=='thongbao')?'selected':'' ?>>Thông báo</option>
      <option value="vanban" <?= (($form_data['muc'] ?? '')=='vanban')?'selected':'' ?>>Văn bản quản lý</option>
      <option value="chuongtrinh" <?= (($form_data['muc'] ?? '')=='chuongtrinh')?'selected':'' ?>>Chương trình đào tạo</option>
    </select>

    <label for="tieude">Tiêu đề:</label>
    <input type="text" name="tieude" id="tieude" required style="width:100%;" value="<?php echo htmlspecialchars($form_data['tieude'] ?? ''); ?>">

    <label for="noidung">Nội dung:</label>
    <textarea name="noidung" id="noidung"><?php echo htmlspecialchars($form_data['noidung'] ?? ''); ?></textarea>

    <button type="submit" name="submit_dhchinhquy" class="submit-button">
        <?php echo $is_editing ? 'Cập nhật' : 'Lưu nội dung'; ?>
    </button>
    <?php if ($is_editing): ?><a href="index.php?page=daotao_chinhquy" class="button-link">Huỷ sửa</a><?php endif; ?>
</form>

<hr><h3>Danh sách nội dung đã lưu</h3>
<table border="1" cellpadding="10" cellspacing="0" class="table">
    <thead><tr><th>ID</th><th>Mục</th><th>Tiêu đề</th> <th>Hình ảnh</th><th>Hành động</th></tr></thead>
    <tbody>
    <?php
    $res_list = $conn->query("SELECT id, muc, tieude, hinhanh FROM noidung_dhchinhquy ORDER BY id DESC");
    if ($res_list && $res_list->num_rows > 0): while ($r = $res_list->fetch_assoc()):
        $list_image_url = (!empty($r['hinhanh']) && file_exists(__DIR__ . "/uploads/" . $r['hinhanh'])) ? "dao_tao/uploads/" . htmlspecialchars($r['hinhanh']) : '';
    ?>
        <tr>
            <td><?php echo $r['id']; ?></td><td><?php echo htmlspecialchars($r['muc']); ?></td><td><?php echo htmlspecialchars($r['tieude']); ?></td>
            <td><?php if ($list_image_url): ?><img src="<?php echo $list_image_url; ?>" width="80"><?php else: echo "-"; endif; ?></td>
            <td>
                <a href="index.php?page=daotao_chinhquy&id=<?php echo $r['id']; ?>" class='edit'>Sửa</a>
                <a href="index.php?page=daotao_chinhquy&delete=<?php echo $r['id']; ?>" class='delete' onclick="return confirm('Xóa?')">Xoá</a>
            </td>
        </tr>
    <?php endwhile; else: echo '<tr><td colspan="5">Chưa có nội dung.</td></tr>'; endif; if ($res_list) $res_list->close(); ?>
    </tbody>
</table>

<!-- Script JS riêng nếu có -->
<!-- <script src="dao_tao/details.js"></script> --> <!-- Đường dẫn từ index.php -->


<!-- Bỏ HTML đóng -->
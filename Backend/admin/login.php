<?php
// Khởi tạo session
session_start();

// Kết nối CSDL
$conn = new mysqli("localhost", "root", "", "phq"); // Đổi lại DB nếu cần
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

$loginError = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // Chuẩn bị câu lệnh truy vấn để kiểm tra email và mật khẩu
    $stmt = $conn->prepare("SELECT id, email, password, full_name FROM nhanvien WHERE email = ?");
    if (!$stmt) {
        die("Lỗi prepare(): " . $conn->error);
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Kiểm tra mật khẩu
        if ($password === $user['password']) {
            // Đăng nhập thành công
            $_SESSION['user_logged_in'] = true;
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['ho_ten'] = $user['full_name'];

            // Lấy vaitro_id từ bảng nhanvien_vaitro
            $sql_role = "SELECT vaitro_id FROM nhanvien_vaitro WHERE nhanvien_id = ?";
            $stmt_role = $conn->prepare($sql_role);
            $stmt_role->bind_param("i", $user['id']); // Sửa lại tham số là user['id'] thay vì user['user_id']
            $stmt_role->execute();
            $role_result = $stmt_role->get_result();

            if ($role_result->num_rows > 0) {
                $role = $role_result->fetch_assoc();
                $_SESSION['vaitro_id'] = $role['vaitro_id'];  // Lưu vaitro_id vào session
            } else {
                die("Lỗi: Vai trò của người dùng chưa được xác định.");
            }

            $stmt_role->close();

            // ✅ Chuyển hướng về giao diện chính
            header("Location: ../index.php?page=dashboard");
            exit();
        } else {
            $loginError = "Mật khẩu không đúng!";
        }
    } else {
        $loginError = "Email không tồn tại!";
    }

    $stmt->close();
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng nhập hệ thống</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f4f6f9;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .login-container {
            background: #fff;
            padding: 35px 40px;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 400px;
            box-sizing: border-box;
        }

        .login-container h2 {
            margin-bottom: 25px;
            color: #333;
            text-align: center;
        }

        .form-group {
            margin-bottom: 18px;
        }

        label {
            display: block;
            font-size: 14px;
            margin-bottom: 6px;
            color: #444;
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 10px 12px;
            font-size: 15px;
            border: 1px solid #ccc;
            border-radius: 6px;
            box-sizing: border-box;
        }

        input[type="email"]:focus,
        input[type="password"]:focus {
            border-color: #007bff;
            outline: none;
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #007bff;
            border: none;
            border-radius: 6px;
            color: white;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

        .error {
            background-color: #ffe5e5;
            color: #c62828;
            padding: 10px 12px;
            border-radius: 6px;
            margin-bottom: 20px;
            border-left: 5px solid #c62828;
            font-size: 14px;
        }

        .logo {
            text-align: center;
            margin-bottom: 15px;
        }

        .logo img {
            width: 80px;
        }

        .footer {
            text-align: center;
            font-size: 12px;
            color: #777;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="logo">
            <img src="https://upload.wikimedia.org/wikipedia/vi/2/2a/Logo_%C4%90%E1%BA%A1i_h%E1%BB%8Dc_Th%C6%B0%C6%A1ng_m%E1%BA%A1i.jpg" alt="Logo">
        </div>
        <h2>Đăng nhập hệ thống</h2>

        <?php if ($loginError): ?>
            <div class="error"><?php echo htmlspecialchars($loginError); ?></div>
        <?php endif; ?>

        <form method="POST" action="login.php">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" name="email" id="email" required placeholder="Nhập email...">
            </div>
            <div class="form-group">
                <label for="password">Mật khẩu:</label>
                <input type="password" name="password" id="password" required placeholder="••••••••">
            </div>
            <div class="form-group">
                <button type="submit">Đăng nhập</button>
            </div>
        </form>

        <div class="footer">
            © <?php echo date("Y"); ?> Trường Đại học Thương mại
        </div>
    </div>
</body>
</html>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý tuyển sinh</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 30px;
            background: #f9f9f9;
        }
        h1 {
            color: #003399;
            border-bottom: 2px solid #003399;
            padding-bottom: 10px;
        }
        p {
            margin-bottom: 20px;
        }
        .button-group {
            display: flex;
            gap: 15px;
        }
        .btn {
            padding: 10px 20px;
            font-size: 16px;
            border: none;
            color: white;
            cursor: pointer;
            border-radius: 5px;
            text-decoration: none;
        }
        .btn-blue { background-color: #007bff; }
        .btn-green { background-color: #28a745; }
        .btn-yellow { background-color: #ffc107; color: black; }
    </style>
</head>
<body>
    <h1>QUẢN LÝ THÔNG TIN TUYỂN SINH ĐẠI HỌC THƯƠNG MẠI</h1>
    <p>Đây là trang tổng quan, cung cấp các lối tắt để quản lý những nội dung quan trọng liên quan đến hoạt động tuyển sinh của trường.</p>
    <p>Vui lòng chọn chức năng từ menu chính bên trái hoặc sử dụng các liên kết nhanh dưới đây:</p>
    
    <div class="button-group">
        <a href="quanly_phuongthuc.php" class="btn btn-blue">Quản lý Phương thức</a>
        <a href="quanly_dean.php" class="btn btn-green">Quản lý Đề án</a>
        <a href="quanly_bantin.php" class="btn btn-yellow">Quản lý Bản tin</a>
    </div>
</body>
</html>
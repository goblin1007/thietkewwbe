<?php
// File: Backend/dao_tao/index.php
// Nội dung chính cho trang tổng quan Đào tạo (?page=daotao)
// File này được include bởi Backend/index.php

// --- Bỏ các lệnh include thừa ---
// KHÔNG CẦN: include("sidebar.php");
// KHÔNG CẦN: include("header.php");
// KHÔNG CẦN: include ("footer.php");

// --- Bỏ các thẻ HTML boilerplate ---
// KHÔNG CẦN: <!DOCTYPE html>
// KHÔNG CẦN: <html lang="vi">
// KHÔNG CẦN: <head> ... </head>
// KHÔNG CẦN: <body>

// --- Bạn có thể nhúng CSS riêng bằng thẻ <style> nếu muốn ---
?>
<style>
    /* CSS riêng cho nội dung trang dao_tao/index.php nếu cần */
    .daotao-overview-container {
        padding: 20px;
        text-align: center;
    }
    .daotao-overview-container h3 {
        color: #1a237e; /* Màu xanh đậm */
        margin-bottom: 25px;
    }
    .daotao-overview-container p {
        margin-bottom: 20px;
        color: #555;
    }
     .daotao-quick-links a { /* Style chung cho các link */
        display: inline-block;
        margin: 5px;
        padding: 10px 15px;
        background-color: #e0e0e0;
        color: #333;
        text-decoration: none;
        border-radius: 4px;
        transition: background-color 0.3s;
     }
     .daotao-quick-links a:hover {
        background-color: #bdbdbd;
     }
</style>

<?php
// --- Chỉ giữ lại phần nội dung chính ---
?>
<div class="daotao-overview-container"> <?php /* Thêm class để CSS nếu cần */ ?>
    <h3> QUẢN LÝ THÔNG TIN ĐÀO TẠO ĐẠI HỌC THƯƠNG MẠI</h3>
    <p>Chọn một chuyên mục quản lý cụ thể từ menu bên trái.</p>
    <div class="daotao-quick-links">
        <p>Hoặc truy cập nhanh:</p>
        <a href="index.php?page=daotao_chinhquy">ĐH Chính quy</a>
        <a href="index.php?page=daotao_saudh">Sau Đại học</a>
        <a href="index.php?page=daotao_quocte">Hệ Quốc tế</a>
        <a href="index.php?page=daotao_tuxa">Hệ Từ xa</a>
    </div>
</div>

<?php
// KHÔNG CẦN: </body>
// KHÔNG CẦN: </html>
?>
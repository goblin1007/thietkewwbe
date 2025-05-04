<?php
// File: Backend/tuyensinh/backendts/indexts.php
// Nội dung chính của trang Quản lý Tuyển sinh dashboard
// File này được include bởi Backend/index.php

// KHÔNG CẦN: include("sidebar.php");
// KHÔNG CẦN: include("headerts.php");
// KHÔNG CẦN: include ("footer.php");
// KHÔNG CẦN: <link rel="stylesheet" href="...">
?>

<!-- Nhúng CSS trực tiếp vào file bằng thẻ <style> -->
<style>
    /* --- CSS riêng cho trang indexts.php --- */

    /* Áp dụng kiểu cho container chính của trang này để giới hạn phạm vi ảnh hưởng */
    .tuyensinh-dashboard-container {
        padding: 20px;
        background-color: #f8f9fa; /* Màu nền nhẹ cho khu vực nội dung */
        border: 1px solid #dee2e6; /* Viền nhẹ */
        border-radius: 5px; /* Bo góc */
        margin-bottom: 20px; /* Khoảng cách với phần tử bên dưới nếu có */
    }

    /* Kiểu cho tiêu đề chính của trang */
    .tuyensinh-dashboard-container h3 {
        color: #0056b3; /* Màu xanh dương đậm */
        border-bottom: 2px solid #0056b3; /* Đường gạch chân */
        padding-bottom: 10px;
        margin-top: 0; /* Bỏ margin top mặc định */
        margin-bottom: 20px; /* Khoảng cách dưới tiêu đề */
        text-align: center;
        font-size: 1.5em; /* Cỡ chữ lớn hơn */
    }

    /* Kiểu cho các đoạn văn bản mô tả */
    .tuyensinh-dashboard-container p {
        line-height: 1.6; /* Giãn dòng */
        color: #333; /* Màu chữ tối hơn */
        margin-bottom: 15px;
    }

    /* Kiểu cho khu vực chứa các link nhanh */
    .tuyensinh-dashboard-container .quick-links {
        margin-top: 25px;
        padding-top: 15px;
        border-top: 1px dashed #ccc; /* Phân cách nhẹ */
        text-align: center; /* Căn giữa các nút */
    }

    /* Kiểu cho các nút link nhanh */
    .tuyensinh-dashboard-container .quick-link-button {
        display: inline-block; /* Hiển thị trên cùng hàng */
        margin: 5px 10px; /* Khoảng cách giữa các nút */
        padding: 10px 18px; /* Tăng padding */
        color: white; /* Chữ màu trắng */
        text-decoration: none;
        border-radius: 4px;
        font-weight: bold;
        transition: background-color 0.3s ease, transform 0.1s ease; /* Hiệu ứng hover */
        border: none; /* Bỏ viền mặc định */
        cursor: pointer;
    }

    /* Màu nền khác nhau cho từng nút */
    .quick-link-button.phuongthuc { background-color: #007bff; /* Xanh dương */ }
    .quick-link-button.dean { background-color: #28a745; /* Xanh lá */ }
    .quick-link-button.bantin { background-color: #ffc107; color: #333; /* Vàng, chữ đen */ }
    .quick-link-button.khac { background-color: #6c757d; /* Xám */ }

    /* Hiệu ứng khi rê chuột qua nút */
    .tuyensinh-dashboard-container .quick-link-button:hover {
        opacity: 0.9;
        transform: translateY(-1px); /* Nhích lên nhẹ */
    }

    /* Thêm các kiểu khác nếu cần cho bảng thống kê, biểu đồ, v.v. */
    /* Ví dụ:
    .tuyensinh-stats-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }
    .tuyensinh-stats-table th,
    .tuyensinh-stats-table td {
        border: 1px solid #ccc;
        padding: 8px;
        text-align: left;
    }
    */

</style>

<!-- Nội dung chính của trang indexts.php -->
<!-- Sử dụng class container chính đã định nghĩa ở trên -->
<div class="tuyensinh-dashboard-container">
    <h3> QUẢN LÝ THÔNG TIN TUYỂN SINH ĐẠI HỌC THƯƠNG MẠI</h3>
    <p>Đây là trang tổng quan, cung cấp các lối tắt để quản lý những nội dung quan trọng liên quan đến hoạt động tuyển sinh của trường.</p>
    <p>Vui lòng chọn chức năng cụ thể từ menu chính bên trái hoặc sử dụng các liên kết nhanh dưới đây:</p>

    <div class="quick-links">
        <!-- Thêm class để phân biệt màu nút -->
        <a href="index.php?page=tuyensinh_phuongthuc" class="quick-link-button phuongthuc">Quản lý Phương thức</a>
        <a href="index.php?page=tuyensinh_dean" class="quick-link-button dean">Quản lý Đề án</a>
        <a href="index.php?page=tuyensinh_bantin" class="quick-link-button bantin">Quản lý Bản tin</a>
        <?php /*
        <a href="index.php?page=tuyensinh_khac" class="quick-link-button khac">Chức năng khác</a>
        */ ?>
    </div>

    <?php /*
    <!-- Khu vực hiển thị thống kê (ví dụ) -->
    <div style="margin-top: 30px;">
        <h4>Thống kê nhanh:</h4>
        <table class="tuyensinh-stats-table">
             <thead>
                 <tr><th>Mục</th><th>Số lượng</th></tr>
             </thead>
             <tbody>
                 <tr><td>Số phương thức đang áp dụng</td><td>5</td></tr>
                 <tr><td>Số đề án đã công bố</td><td>1</td></tr>
                 <tr><td>Số bản tin trong tháng</td><td>12</td></tr>
             </tbody>
        </table>
    </div>
    */ ?>
</div>
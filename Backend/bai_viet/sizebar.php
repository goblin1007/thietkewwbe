<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sidebar - Trường Đại Học Thương Mại</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

</head>
<body>
    <div class="container">
        <div class="sidebar">
            <div class="logo-container">
                <img src="https://tmu.edu.vn/template_dhtm/images/logo-sm.png" alt="Logo trường" class="logo">
                <div class="logo-text">TRƯỜNG ĐẠI HỌC<br>THƯƠNG MẠI</div>
            </div>
            
            <div class="menu-item active" onclick="activateMenuItem(this)">
                <div class="menu-icon"><i class="fas fa-home"></i></div>
                <div>Trang chủ</div>
            </div>
            <div class="menu-item" onclick="activateMenuItem(this)">
                <div class="menu-icon"><i class="fas fa-newspaper"></i></div>
                <div>Tin tức & Sự kiện</div>
            </div>
            <div class="menu-item" onclick="activateMenuItem(this)">
                <div class="menu-icon"><i class="fas fa-graduation-cap"></i></div>
                <div>Tuyển sinh</div>
            </div>
            <div class="menu-item" onclick="activateMenuItem(this)">
                <div class="menu-icon"><i class="fas fa-book"></i></div>
                <div>Đào tạo</div>
            </div>
            <div class="menu-item" onclick="showToast('Bạn có chắc chắn muốn đăng xuất không?')">
                <div class="menu-icon"><i class="fas fa-sign-out-alt"></i></div>
                <div>Đăng xuất</div>
            </div>
        </div>
    </div>

    <script>
        function activateMenuItem(element) {
            // Remove active class from all menu items
            const menuItems = document.querySelectorAll('.menu-item');
            menuItems.forEach(item => {
                item.classList.remove('active');
            });
            
            // Add active class to clicked item
            element.classList.add('active');
        }
        
        function showToast(message) {
            alert(message);
        }
    </script>
</body>
</html>
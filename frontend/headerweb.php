<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">    
    <link rel="stylesheet" href="/web/frontend/assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/web/frontend/assets/font/fontawesome-free-6.7.2-web/css/all.min.css">
    <link rel="stylesheet" href="/web/frontend/assets/css/bootstrap.min.css">
    
    
   
</head>
<body>
   <!-- Scroll Up -->
   <a id="scrollUp" href="#top" style="position: fixed; z-index: 2147483647; display: block;"><i class="fa-solid fa-angle-up"
    style="display: inline-block;
           font: 14px/1 FontAwesome;
           font-size: inherit;
           padding-top: 7px;
           "aria-hidden="true"></i></a>
<script type="text/javascript">
    document.getElementById("scrollUp").addEventListener("click", function(e) {
    e.preventDefault(); 
    window.scrollTo({
    top: 0,
    behavior: 'smooth' 
});
});
    window.addEventListener('scroll', function(){
        var scroll = document.querySelector('#scrollUp');
        
        scroll.classList.toggle("active", window.scrollY > 500)
    })
    </script>
    
    <header>
        <div class="grid">
            <div class="header_with_logo">
                <div class="header_logo_wap">
                    <div class="header_logo">
                        <a class="navbar-brand" href="/web/frontend/trang chu/trangchu.php">
                            <img src="/web/frontend/assets/img/logo-dhtm.png" alt="TMU">
                        </a>
                    </div>
                    <div class="header_logo_text">
                        <img src="/web/frontend/assets/img/banner-text-tmu.png" alt="TMU">
                    </div>
                </div>

                <div class="header_search_lang">
                <div class="header_search">
                    <form action="#" class="search-form" id="formHome" method="get">
                        <button class="btn btn_search" type="submit">
                            <span class="header_search_icon">
                                <i class="fa-solid fa-magnifying-glass"></i>
                            </span>
                        </button>
                        <input type="text" name="keyword" class="header_search_input" placeholder="Tìm kiếm">
                    </form>
                </div>

                    <div class="header_lang">
                        <button class="header_lang_btn btn">
                            <img class="header_lang_img" src="/web/frontend/assets/img/Flag_of_Vietnam.svg" alt="VietNam">
                        </button>
                        <div class="header_lang_list">
                            <img class="header_lang_img header_lang_list_img" src="/web/frontend/assets/img/Flag_of_Vietnam.svg" alt="VietNam">
                            <span class="header_lang_list_text">Vietnamese</span>
                        </div>
                    </div>
                    
                </div>
            </div>

            
            
        </div>
        <div class="menu">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <nav class="navbar navbar-expand-lg">

                            <div class="collapse navbar-collapse" id="worldNav">
                            <ul class="navbar-nav">
                                <li class="nav-item">
                                    <a href="/web/frontend/trang chu/trangchu.php" class="nav-link" target="_self">
                                    &nbsp;<i class="fa fa-home"></i>&nbsp;
                                    </a>
                                </li>
                                <!-- GIỚI THIỆU -->
                                <li class="nav-item dropdown introduce_item">
                                    <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">Giới thiệu</a>
                                    <ul class="dropdown-menu introduce_item_list">
                                    <li><a class="dropdown-item" href="/web/frontend/gioi_thieu/gioithieuchung.php">Giới thiệu chung</a></li>
                                    <li><a class="dropdown-item" href="/web/frontend/gioi_thieu/sumangmuctieu.php">Sứ mệnh mục tiêu</a></li>
                                    </ul>
                                </li>

                                <!-- TIN TỨC -->
                                <li class="nav-item">
                                    <a href="/web/frontend/tin_tuc/user.php" class="nav-link">Tin tức</a>
                                </li>


                                <!-- ĐÀO TẠO -->
                                <li class="nav-item">
                                    <a href="/web/frontend/daotao/user.php" class="nav-link">Đào Tạo </a>
                                </li>

                                <!-- TUYỂN SINH -->
                                <li class="nav-item">
                                    <a href="/web/frontend/tuyensinh/user.php" class="nav-link">Tuyển Sinh </a>
                                </li>
                            </ul>
                        </div>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</header>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="/web/frontend/assets/js/bootstrap.min.js"></script>
<script >
document.addEventListener('DOMContentLoaded', function () {
const btn = document.querySelector('.header_lang_btn');
const list = document.querySelector('.header_lang_list');

// Toggle hiển thị khi click vào nút
btn.addEventListener('click', function (event) {
    event.stopPropagation(); // Ngăn sự kiện click lan ra ngoài
    list.style.display = (list.style.display === 'flex') ? 'none' : 'flex';
});

// Ẩn khi click ra ngoài danh sách
document.addEventListener('click', function (e) {
    if (!list.contains(e.target) && !btn.contains(e.target)) {
        list.style.display = 'none';
    }
});
});

document.querySelector('.introduce_item').onmouseover = function (){
document.querySelector('.introduce_item_list').style.display='block';
}
document.querySelector('.introduce_item').onmouseout = function (){
document.querySelector('.introduce_item_list').style.display='none';
}
document.querySelector('.introduce_item_list').onmouseout = function(){
document.querySelector('.introduce_item_list').style.display='none';
}

document.querySelector('.news_item').onmouseover = function (){
document.querySelector('.news_item_list').style.display='block';
}
document.querySelector('.news_item').onmouseout = function (){
document.querySelector('.news_item_list').style.display='none';
}
document.querySelector('.dropdown-menu').onmouseout = function(){
document.querySelector('.news_item_list').style.display='none';
}

document.querySelector('.trainning_item').onmouseover = function (){
document.querySelector('.trainning_item_list').style.display='block';
}
document.querySelector('.trainning_item').onmouseout = function (){
document.querySelector('.trainning_item_list').style.display='none';
}
document.querySelector('.trainning_item_list').onmouseout = function(){
document.querySelector('.trainning_item_list').style.display='none';
}

document.querySelector(' .admission_item').onmouseover = function (){
document.querySelector(' .admission_item_list').style.display='block';
}
document.querySelector(' .admission_item').onmouseout = function (){
document.querySelector('.admission_item_list').style.display='none';
}
document.querySelector('.admission_item_list').onmouseout = function(){
document.querySelector('.admission_item_list').style.display='none';
}


</script>
</body>

</html>



                                
                                
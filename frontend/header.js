// /* header */
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


document.addEventListener("DOMContentLoaded", function () {
  const allTitles = document.querySelectorAll('.toggle-title');
  
  allTitles.forEach(function (title) {
    title.addEventListener('click', function () {
      const currentDetails = this.nextElementSibling;
      const currentIcon = this.querySelector('span');
      const isOpen = currentDetails.style.maxHeight && currentDetails.style.maxHeight !== '0px';

      console.log("Title clicked:", this.innerText);  // Để kiểm tra sự kiện click
      console.log("Is open:", isOpen);  // Kiểm tra trạng thái mở của phần tử

      // Đóng tất cả
      document.querySelectorAll('.post-details').forEach(function (detail) {
        detail.style.maxHeight = null;
        const icon = detail.previousElementSibling.querySelector('span');
        if (icon) icon.textContent = '▼';
      });

      // Nếu chưa mở thì mở
      if (!isOpen) {
        currentDetails.style.maxHeight = currentDetails.scrollHeight + 'px';
        currentIcon.textContent = '▲';
      }
    });
  });
});

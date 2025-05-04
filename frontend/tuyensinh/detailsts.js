document.addEventListener("DOMContentLoaded", function () {
  const allTitles = document.querySelectorAll('.toggle-title');

  allTitles.forEach(function (title) {
    title.addEventListener('click', function () {
      const currentContent = this.nextElementSibling;
      const currentArrow = this.querySelector('.arrow');
      const isOpen = currentContent.style.maxHeight && currentContent.style.maxHeight !== '0px';

      console.log("Title clicked:", this.innerText);
      console.log("Is open:", isOpen);

      // Đóng tất cả các mục khác
      document.querySelectorAll('.program-content').forEach(function (content) {
        content.style.maxHeight = null;
        const arrow = content.previousElementSibling.querySelector('.arrow');
        if (arrow) arrow.textContent = '▼';
      });

      // Nếu chưa mở, thì mở mục hiện tại
      if (!isOpen) {
        currentContent.style.maxHeight = currentContent.scrollHeight + 'px';
        if (currentArrow) currentArrow.textContent = '▲';
      }
    });
  });
});

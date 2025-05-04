// slider
const sliderMain = document.querySelector(".slider-main");
const sliderItems = document.querySelectorAll(".slider-item");
const prevBtn = document.querySelector(".slider-prev");
const nextBtn = document.querySelector(".slider-next");
const dot = document.querySelectorAll(".slider-dot");
var slideIndex = 0;
const slideItemWidth = sliderItems[0].offsetWidth;
const slideLength = sliderItems.length;
var posX = 0;

nextBtn.addEventListener("click", function () {
  changeSlide(1);
});

prevBtn.addEventListener("click", function () {
  changeSlide(-1);
});
autoNextSlide();

function changeSlide(direction) {
  if (direction === 1) {
    if (slideIndex === slideLength - 1) {
      posX = 0;
      slideIndex = 0;
      sliderMain.style = `transform: translateX(${posX}px)`;
      return;
    }
    slideIndex++;
    posX = posX - slideItemWidth;
    sliderMain.style = `transform: translateX(${posX}px)`;
  } else if (direction === -1) {
    if (slideIndex === 0) {
      slideIndex = slideLength - 1;
      posX = -slideItemWidth * (slideLength - 1);
      sliderMain.style = `transform: translateX(${posX}px)`;
      return;
    }
    slideIndex--;
    posX = posX + slideItemWidth;
    sliderMain.style = `transform: translateX(${posX}px)`;
  }
}

function autoNextSlide() {
  setInterval(function () {
    changeSlide(1);
  }, 5000);
}

document.getElementById("submit_contact").addEventListener("click", function () {
    const fullname = document.getElementById("fullname_footer").value.trim();
    const phone = document.getElementById("phone_footer").value.trim();
    const email = document.getElementById("email_footer").value.trim();
    const train_program = document.getElementById("train_program_footer").value;
    const note = document.getElementById("note_footer").value.trim();

    // Kiểm tra đơn giản
    if (!fullname || !phone || !email) {
        document.querySelector(".notice_content span").innerText = "Vui lòng điền đầy đủ thông tin bắt buộc.";
        document.getElementById("modal").style.display = "block";
        return;
    }

    fetch('save_contact.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({ fullname, phone, email, train_program, note })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            alert("Gửi thành công!");
            // Reset form
            document.querySelector(".footer_contact_form").reset();
        } else {
            alert("Có lỗi xảy ra, vui lòng thử lại.");
        }
    })
    .catch(err => {
        console.error(err);
        alert("Không thể gửi dữ liệu.");
    });
});


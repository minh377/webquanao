document.addEventListener("DOMContentLoaded", function () {

    // --- XỬ LÝ POPUP ĐĂNG NHẬP ---
    const modal = document.getElementById("loginModal");
    const loginBtn = document.getElementById("loginBtn");
    const closeBtn = document.querySelector(".close");

    // Mở modal khi bấm "Đăng nhập / Đăng ký"
    loginBtn.onclick = function () {
        modal.style.display = "block";
    }

    // Đóng modal khi bấm nút 'X'
    closeBtn.onclick = function () {
        modal.style.display = "none";
    }

    // Đóng modal khi click ra ngoài vùng form
    window.onclick = function (event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }

    // --- XỬ LÝ SLIDER (BANNER TỰ ĐỘNG CHẠY) ---
    let currentSlide = 0;
    const slides = document.querySelectorAll('.slide');

    function showSlide(index) {
        slides.forEach(slide => slide.classList.remove('active'));
        if (index >= slides.length) currentSlide = 0;
        if (index < 0) currentSlide = slides.length - 1;
        slides[currentSlide].classList.add('active');
    }

    function nextSlide() {
        currentSlide++;
        showSlide(currentSlide);
    }

    // Nút next/prev
    document.querySelector('.next-slide').addEventListener('click', nextSlide);
    document.querySelector('.prev-slide').addEventListener('click', () => {
        currentSlide--;
        showSlide(currentSlide);
    });

    // Tự động chạy mượt mà mỗi 5 giây
    setInterval(nextSlide, 5000);
});
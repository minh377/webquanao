document.addEventListener("DOMContentLoaded", function () {

    // ==========================================
    // 1. XỬ LÝ BẬT/TẮT POPUP ĐĂNG NHẬP
    // ==========================================
    const modal = document.getElementById("loginModal");
    const loginBtn = document.getElementById("loginBtn");
    const closeBtn = document.querySelector(".close");

    // Mở modal khi bấm "Đăng nhập / Đăng ký"
    if (loginBtn) {
        loginBtn.onclick = function () {
            modal.style.display = "block";
        }
    }

    // Đóng modal khi bấm nút 'X'
    if (closeBtn) {
        closeBtn.onclick = function () {
            modal.style.display = "none";
        }
    }

    // Đóng modal khi click ra ngoài vùng form
    window.onclick = function (event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }

    // ==========================================
    // 3. XỬ LÝ SLIDER (BANNER TỰ ĐỘNG CHẠY)
    // ==========================================
    let currentSlide = 0;
    const slides = document.querySelectorAll('.slide');

    function showSlide(index) {
        // Ẩn tất cả các slide
        slides.forEach(slide => slide.classList.remove('active'));

        // Quay vòng lại nếu vượt quá số lượng slide
        if (index >= slides.length) currentSlide = 0;
        if (index < 0) currentSlide = slides.length - 1;

        // Hiển thị slide hiện tại
        if (slides[currentSlide]) {
            slides[currentSlide].classList.add('active');
        }
    }

    function nextSlide() {
        currentSlide++;
        showSlide(currentSlide);
    }

    // Nút next/prev (Bấm mũi tên qua lại)
    const btnNext = document.querySelector('.next-slide');
    const btnPrev = document.querySelector('.prev-slide');

    if (btnNext) {
        btnNext.addEventListener('click', nextSlide);
    }
    if (btnPrev) {
        btnPrev.addEventListener('click', () => {
            currentSlide--;
            showSlide(currentSlide);
        });
    }

    // Tự động chạy mượt mà mỗi 5 giây
    if (slides.length > 0) {
        setInterval(nextSlide, 5000);
    }
});
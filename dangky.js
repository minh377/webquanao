document.addEventListener("DOMContentLoaded", function () {

    // Lấy các thành phần HTML ra để xử lý
    const registerForm = document.getElementById('registerPageForm');
    const password = document.getElementById('password');
    const confirmPassword = document.getElementById('confirm_password');
    const errorMsg = document.getElementById('password_error');

    if (registerForm) {
        // Lắng nghe sự kiện khi người dùng bấm nút Đăng Ký
        registerForm.addEventListener('submit', function (event) {

            // Kiểm tra mật khẩu có khớp không
            if (password.value !== confirmPassword.value) {
                // Nếu sai: Dừng ngay việc gửi form đi
                event.preventDefault();

                // Hiện dòng chữ báo lỗi
                errorMsg.style.display = 'block';
                confirmPassword.style.borderColor = '#d32f2f'; // Viền đỏ cảnh báo
            } else {
                // Nếu đúng: Ẩn lỗi và cho form chạy tiếp qua auth.php
                errorMsg.style.display = 'none';
                confirmPassword.style.borderColor = '#ddd';
            }
        });
    }
});
<?php
session_start();
// Nếu người dùng đã đăng nhập thì không cho vào trang đăng ký nữa, đẩy về trang chủ
if(isset($_SESSION['user_name'])){
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký tài khoản - ShopTheMan</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Dùng lại CSS trang chủ để lấy giao diện Header & Footer -->
    <link rel="stylesheet" href="trangchu.css">
    <!-- CSS thiết kế riêng cho phần thân của trang đăng ký -->
    <link rel="stylesheet" href="dangky.css">
</head>
<body>

    <!-- HEADER (Được giữ nguyên y hệt trang chủ) -->
    <header>
        <div class="top-header">
            <div class="header-left">
                <a href="index.php" style="text-decoration: none; color: #111;">
                    <h1 class="logo">ShopTheMan.</h1>
                </a>
            </div>
            <div class="header-center">
                <form class="search-bar" action="index.php" method="GET">
                    <input type="text" name="q" placeholder="Tìm kiếm sản phẩm...">
                    <button type="submit">TÌM</button>
                </form>
            </div>
            <div class="header-right">
                <!-- Chuyển hướng người dùng về trang chủ để đăng nhập -->
                <a href="index.php" class="btn-outline" style="text-decoration: none; display: inline-block;">Đăng nhập</a>
                <button class="btn-outline">Giỏ hàng (0)</button>
            </div>
        </div>
        <nav class="main-nav">
            <a href="#" class="text-red">SALE</a>
            <a href="#" class="bg-black">THỜI TRANG NAM &rarr;</a>
        </nav>
    </header>

    <!-- PHẦN THÂN: FORM ĐĂNG KÝ -->
    <main class="register-page">
        <div class="register-container">
            <h2>TẠO TÀI KHOẢN MỚI</h2>
            <p>Trở thành thành viên để nhận nhiều ưu đãi từ ShopTheMan.</p>
            
            <form id="registerPageForm" action="auth.php" method="POST">
                <!-- Biến action báo cho auth.php biết đây là lệnh đăng ký -->
                <input type="hidden" name="action" value="register"> 
                
                <div class="input-group">
                    <label>Họ và tên *</label>
                    <input type="text" name="fullname" id="fullname" placeholder="Ví dụ: Nguyễn Văn A" required>
                </div>
                
                <div class="input-group">
                    <label>Email *</label>
                    <input type="email" name="email" id="email" placeholder="Nhập địa chỉ email..." required>
                </div>
                
                <div class="input-group">
                    <label>Mật khẩu *</label>
                    <input type="password" name="password" id="password" placeholder="Tạo mật khẩu..." required>
                </div>

                <div class="input-group">
                    <label>Xác nhận mật khẩu *</label>
                    <input type="password" id="confirm_password" placeholder="Nhập lại mật khẩu..." required>
                    <small id="password_error" style="color: #d32f2f; display: none; margin-top: 5px; font-weight: 500;">Mật khẩu không khớp! Vui lòng kiểm tra lại.</small>
                </div>
                
                <button type="submit" class="btn-submit-register">ĐĂNG KÝ TÀI KHOẢN</button>
                <p class="login-redirect">Đã có tài khoản? <a href="index.php">Đăng nhập tại đây</a></p>
            </form>
        </div>
    </main>

    <!-- FOOTER (Giống hệt trang chủ) -->
    <footer>
        <div class="footer-container">
            <div class="footer-col">
                <h3>Về ShopTheMan</h3>
                <p>Chuyên cung cấp các sản phẩm thời trang sang trọng, chất lượng cao.</p>
            </div>
            <div class="footer-col">
                <h3>Liên hệ</h3>
                <p>Hotline: 0886 871 298</p>
                <p>Email: minhdca1v7n523@vlvh.ctu.edu.vn</p>
            </div>
            <div class="footer-col">
                <h3>Địa chỉ cửa hàng</h3>
                <p>Hẻm 77, Phạm Ngũ Lão, Ninh Kiều</p>
                <p>TP. Cần Thơ, Việt Nam</p>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2026 ShopTheMan. All rights reserved.</p>
        </div>
    </footer>

    <!-- Gọi file JS riêng biệt cho trang đăng ký -->
    <script src="dangky.js"></script>
</body>
</html>
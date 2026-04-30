<?php
session_start();
// Kết nối cơ sở dữ liệu
$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'shoptheman_db'; 

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// LƯU Ý: ĐOẠN XỬ LÝ TÌM KIẾM MỚI BẮT ĐẦU TỪ ĐÂY
$search_keyword = "";
if (isset($_GET['q'])) {
    // Lấy từ khóa người dùng nhập và làm sạch để bảo mật (chống SQL Injection)
    $search_keyword = $conn->real_escape_string($_GET['q']);
}

if ($search_keyword != "") {
    // Nếu có nhập từ khóa: Tìm các sản phẩm có tên chứa từ khóa đó (dùng LIKE)
    $sql = "SELECT * FROM products WHERE name LIKE '%$search_keyword%' ORDER BY created_at DESC";
    $title_section = 'KẾT QUẢ TÌM KIẾM';
    $desc_section = 'Hiển thị kết quả cho từ khóa: "'.htmlspecialchars($search_keyword).'"';
} else {
    // Nếu không tìm kiếm: Mặc định lấy 4 sản phẩm mới nhất
    $sql = "SELECT * FROM products ORDER BY created_at DESC LIMIT 4";
    $title_section = 'SẢN PHẨM NỔI BẬT';
    $desc_section = 'Chọn phong cách của riêng bạn và chứng tỏ sức hút riêng biệt.';
}

$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ShopTheMan - Thời trang nam</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="trangchu.css">
</head>
<body>

    <!-- HEADER -->
    <header>
        <div class="top-header">
            <!-- Cột trái: Logo -->
            <div class="header-left">
                <h1 class="logo">ShopTheMan.</h1>
            </div>
            <div class="header-center">
                <form class="search-bar" action="index.php" method="GET">
                    <input type="text" name="q" placeholder="Tìm kiếm sản phẩm..." value="<?php echo isset($_GET['q']) ? htmlspecialchars($_GET['q']) : ''; ?>">
                    <button type="submit">TÌM</button>
                </form>
            </div>

            <!-- Cột phải: Các nút chức năng -->
            <div class="header-right">
            <?php if(isset($_SESSION['user_name'])): ?>
                <!-- Nếu đã đăng nhập: Hiện tên và nút Thoát -->
                <span style="font-weight: 600; font-size: 14px; margin-right: 15px; display: flex; align-items: center;">
                    Chào, <?php echo $_SESSION['user_name']; ?> 
                    <a href="logout.php" style="color: #d32f2f; text-decoration: none; margin-left: 10px; font-size: 13px;">(Thoát)</a>
                </span>
            <?php else: ?>
                <!-- Nếu chưa đăng nhập: Hiện nút -->
                <button id="loginBtn" class="btn-outline">Đăng nhập / Đăng ký</button>
            <?php endif; ?>
                
            <!-- Đếm số sản phẩm trong giỏ -->
            <?php $cart_count = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0; ?>
            <a href="giohang.php" class="btn-outline" style="text-decoration: none; display: inline-block;">Giỏ hàng (<?php echo $cart_count; ?>)</a>
            </div>
        </div>
        <nav class="main-nav">
            <a href="#" class="text-red">SALE</a>
            <a href="#" class="bg-black">THỜI TRANG NAM &rarr;</a>
        </nav>
    </header>

    <section class="slider-container">
        <div class="slides">
            <!-- Slide 1 -->
            <div class="slide active" style="background-image: url('images/Banner.png');">
            </div>
            <div class="slide active" style="background-image: url('images/bannerphukien.png');">
            </div>
            <div class="slide active" style="background-image: url('images/bannerSale.png');">
            </div>
            <!-- Có thể thêm các slide khác tại đây -->
        </div>
        <button class="prev-slide">&#10094;</button>
        <button class="next-slide">&#10095;</button>
    </section>

    <!-- PRODUCTS SECTION -->
    <section class="product-section">
        <div class="section-title">
            <!-- Sử dụng PHP để linh hoạt thay đổi tiêu đề -->
            <h2><?php echo $title_section; ?></h2>
            <p><?php echo $desc_section; ?></p>
        </div>
        <div class="product-grid">
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    // Format giá tiền
                    $formatted_price = number_format($row["price"], 0, ',', '.') . 'đ';
                    echo '
                    <div class="product-card">
                        <img src="'.$row["image_url"].'" alt="'.$row["name"].'">
                        <div class="product-info">
                            <h3>'.$row["name"].'</h3>
                            <p>'.$formatted_price.'</p>
                        </div>
                        <!-- Form gửi ID sản phẩm sang trang giỏ hàng -->
                        <form action="giohang.php" method="POST" style="margin:0;">
                            <input type="hidden" name="action" value="add">
                            <input type="hidden" name="product_id" value="'.$row["id"].'">
                            <button type="submit" class="add-to-cart">THÊM VÀO GIỎ</button>
                        </form>
                    </div>';
                }
            } else {
                echo "<p>Chưa có sản phẩm nào.</p>";
            }
            ?>
        </div>
    </section>

    <!-- FOOTER -->
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
            <p>&copy; 2025 ShopTheMan. All rights reserved.</p>
        </div>
    </footer>

    <!-- LOGIN POPUP MODAL -->
    <div id="loginModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            
            <!-- FORM ĐĂNG NHẬP -->
            <div id="loginForm">
                <h2>ĐĂNG NHẬP</h2>
                <form action="auth.php" method="POST">
                    <input type="hidden" name="action" value="login">
                    <div class="input-group">
                        <label>Email</label>
                        <input type="email" name="email" placeholder="Nhập email..." required>
                    </div>
                    <div class="input-group">
                        <label>Mật khẩu</label>
                        <input type="password" name="password" placeholder="Nhập mật khẩu..." required>
                    </div>
                    <button type="submit" class="btn-login-submit">ĐĂNG NHẬP</button>
                    <p class="register-link">Chưa có tài khoản? <a href="dangky.php">Đăng ký ngay</a></p>
                </form>
            </div>
        </div>
    </div>

    <script src="trangchu.js"></script>
</body>
</html>
<?php $conn->close(); ?>    
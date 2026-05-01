<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'shoptheman_db');

if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Câu lệnh SQL lấy TOÀN BỘ sản phẩm (không giới hạn 4 cái như trang chủ)
$sql = "SELECT * FROM products ORDER BY created_at DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Tất cả sản phẩm - ShopTheMan</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="trangchu.css">
    <style>
        .price-box { margin: 10px 0; }
        .old-price { text-decoration: line-through; color: #999; font-size: 13px; margin-right: 10px; }
        .new-price { color: #d32f2f; font-weight: bold; font-size: 16px; }
        .btn-detail { display: block; width: 100%; padding: 12px; background: #111; color: #fff; text-align: center; text-decoration: none; font-weight: bold; margin-top: 10px; border-radius: 4px; transition: 0.3s; }
        .btn-detail:hover { background: #d32f2f; }
    </style>
</head>
<body>

    <!-- HEADER ĐÃ GẮN LINK LOGO VỀ TRANG CHỦ -->
    <header>
        <div class="top-header">
            <!-- Logo bấm về trang chủ -->
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
                <?php if(isset($_SESSION['user_name'])): ?>
                    <span style="font-weight: 600; font-size: 14px; margin-right: 15px; display: flex; align-items: center;">
                        Chào, <?php echo $_SESSION['user_name']; ?> 
                        <a href="logout.php" style="color: #d32f2f; text-decoration: none; margin-left: 10px; font-size: 13px;">(Thoát)</a>
                    </span>
                <?php else: ?>
                    <a href="index.php" class="btn-outline" style="text-decoration: none; display: inline-block;">Đăng nhập</a>
                <?php endif; ?>
                
                <?php $cart_count = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0; ?>
                <a href="giohang.php" class="btn-outline" style="text-decoration: none; display: inline-block;">Giỏ hàng (<?php echo $cart_count; ?>)</a>
            </div>
        </div>
        
        <!-- Thanh menu điều hướng chính xác -->
        <nav class="main-nav">
            <a href="sale.php" class="text-red">SALE</a>
            <a href="thoitrangnam.php" class="bg-black">THỜI TRANG NAM &rarr;</a>
        </nav>
    </header>

    <!-- NỘI DUNG SẢN PHẨM -->
    <section class="product-section">
        <div class="section-title">
            <h2>THỜI TRANG NAM TỔNG HỢP</h2>
            <p>Khám phá toàn bộ bộ sưu tập thời trang cao cấp của ShopTheMan.</p>
        </div>
        <div class="product-grid">
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo '<div class="product-card">';
                    echo '<img src="'.$row["image_url"].'" alt="'.$row["name"].'">';
                    echo '<div class="product-info">';
                    echo '<h3>'.$row["name"].'</h3>';
                    
                    // Hiển thị giá thông minh (Nếu có giảm giá thì gạch bỏ giá gốc)
                    echo '<div class="price-box">';
                    if (isset($row["sale_price"]) && $row["sale_price"] > 0) {
                        echo '<span class="old-price">'.number_format($row["price"], 0, ',', '.').'đ</span>';
                        echo '<span class="new-price">'.number_format($row["sale_price"], 0, ',', '.').'đ</span>';
                    } else {
                        echo '<p style="font-weight: 500; font-size: 15px; color: #555;">'.number_format($row["price"], 0, ',', '.').'đ</p>';
                    }
                    echo '</div>'; 
                    
                    echo '</div>'; 
                    // Nút chuyển qua trang chi tiết để chọn Size
                    echo '<a href="chitiet.php?id='.$row["id"].'" class="btn-detail">XEM CHI TIẾT</a>';
                    echo '</div>'; 
                }
            } else {
                echo "<p>Chưa có sản phẩm nào trong kho.</p>";
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
            <p>&copy; 2026 ShopTheMan. All rights reserved.</p>
        </div>
    </footer>

</body>
</html>
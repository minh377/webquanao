<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'shoptheman_db');

// Chỉ lấy các sản phẩm đang có giá sale
$sql = "SELECT * FROM products WHERE sale_price > 0 ORDER BY created_at DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Sản Phẩm Sale - ShopTheMan</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="trangchu.css">
    <style>
        .price-box { margin: 10px 0; }
        .old-price { text-decoration: line-through; color: #999; font-size: 13px; margin-right: 10px; }
        .new-price { color: #d32f2f; font-weight: bold; font-size: 16px; }
        .btn-detail { display: block; width: 100%; padding: 12px; background: #111; color: #fff; text-align: center; text-decoration: none; font-weight: bold; margin-top: 10px; transition: 0.3s; }
        .btn-detail:hover { background: #d32f2f; }
    </style>
</head>
<body>
   <!-- HEADER -->
    <header>
        <div class="top-header">
            <!-- Cột trái: Logo (Đã được gắn link href="index.php" để quay về trang chủ) -->
            <div class="header-left">
                <a href="index.php" style="text-decoration: none; color: #111;">
                    <h1 class="logo">ShopTheMan.</h1>
                </a>
            </div>
            
            <!-- Cột giữa: Ô tìm kiếm -->
            <div class="header-center">
                <form class="search-bar" action="index.php" method="GET">
                    <input type="text" name="q" placeholder="Tìm kiếm sản phẩm...">
                    <button type="submit">TÌM</button>
                </form>
            </div>

            <!-- Cột phải: Các nút chức năng (Đăng nhập / Giỏ hàng) -->
            <div class="header-right">
                <?php if(isset($_SESSION['user_name'])): ?>
                    <!-- Nếu đã đăng nhập -->
                    <span style="font-weight: 600; font-size: 14px; margin-right: 15px; display: flex; align-items: center;">
                        Chào, <?php echo $_SESSION['user_name']; ?> 
                        <a href="logout.php" style="color: #d32f2f; text-decoration: none; margin-left: 10px; font-size: 13px;">(Thoát)</a>
                    </span>
                <?php else: ?>
                    <!-- Nếu chưa đăng nhập: Bấm vào sẽ về trang chủ để mở popup đăng nhập -->
                    <a href="index.php" class="btn-outline" style="text-decoration: none; display: inline-block;">Đăng nhập</a>
                <?php endif; ?>
                
                <!-- Hiển thị số lượng giỏ hàng -->
                <?php $cart_count = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0; ?>
                <a href="giohang.php" class="btn-outline" style="text-decoration: none; display: inline-block;">Giỏ hàng (<?php echo $cart_count; ?>)</a>
            </div>
        </div>
        
        <!-- Thanh menu điều hướng -->
        <nav class="main-nav">
            <a href="sale.php" class="text-red">SALE</a>
            <a href="index.php" class="bg-black">THỜI TRANG NAM &rarr;</a>
        </nav>
    </header>
    
    <section class="product-section">
        <div class="section-title">
            <h2>HÀNG SALE GIÁ SỐC</h2>
            <p>Săn ngay các mẫu đồ hot nhất với mức giá không tưởng.</p>
        </div>
        <div class="product-grid">
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo '
                    <div class="product-card">
                        <img src="'.$row["image_url"].'" alt="'.$row["name"].'">
                        <div class="product-info">
                            <h3>'.$row["name"].'</h3>
                            <div class="price-box">
                                <span class="old-price">'.number_format($row["price"], 0, ',', '.').'đ</span>
                                <span class="new-price">'.number_format($row["sale_price"], 0, ',', '.').'đ</span>
                            </div>
                        </div>
                        <!-- Bấm vào sẽ nhảy sang trang chi tiết -->
                        <a href="chitiet.php?id='.$row["id"].'" class="btn-detail">XEM CHI TIẾT</a>
                    </div>';
                }
            } else {
                echo "<p>Hiện tại chưa có sản phẩm sale nào.</p>";
            }
            ?>
        </div>
    </section>
</body>
</html>
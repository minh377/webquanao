<?php
session_start();

// Kết nối Database
$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'shoptheman_db'; 
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) die("Kết nối thất bại: " . $conn->connect_error);

// Khởi tạo giỏ hàng nếu chưa có
if(!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// 1. XỬ LÝ THÊM SẢN PHẨM VÀO GIỎ
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'add') {
    $product_id = $_POST['product_id'];
    
    // Nếu sản phẩm đã có trong giỏ thì tăng số lượng, chưa có thì set = 1
    if(isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]++;
    } else {
        $_SESSION['cart'][$product_id] = 1;
    }
    // Chuyển hướng lại chính trang giỏ hàng để tránh lỗi gửi lại form khi F5
    header("Location: giohang.php"); 
    exit();
}

// 2. XỬ LÝ XÓA SẢN PHẨM KHỎI GIỎ
if(isset($_GET['remove'])) {
    $remove_id = $_GET['remove'];
    unset($_SESSION['cart'][$remove_id]);
    header("Location: giohang.php");
    exit();
}

// 3. XỬ LÝ TĂNG/GIẢM SỐ LƯỢNG
if(isset($_GET['action']) && isset($_GET['id'])) {
    $id = $_GET['id'];
    $action = $_GET['action'];

    if(isset($_SESSION['cart'][$id])) {
        if($action == 'increase') {
            $_SESSION['cart'][$id]++; // Cộng thêm 1
        } elseif($action == 'decrease') {
            $_SESSION['cart'][$id]--; // Trừ đi 1
            if($_SESSION['cart'][$id] <= 0) {
                unset($_SESSION['cart'][$id]); // Nếu trừ về 0 thì tự động xóa khỏi giỏ
            }
        }
    }
    header("Location: giohang.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Giỏ hàng - ShopTheMan</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="trangchu.css">
    <!-- Đừng quên tạo file giohang.css nếu bạn muốn làm đẹp giao diện nhé -->
    <link rel="stylesheet" href="giohang.css"> 
</head>
<body>

    <!-- HEADER -->
    <header>
        <div class="top-header">
            <div class="header-left">
                <a href="index.php" style="text-decoration: none; color: #111;"><h1 class="logo">ShopTheMan.</h1></a>
            </div>
            <div class="header-right">
                <?php $cart_count = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0; ?>
                <a href="giohang.php" class="btn-outline" style="text-decoration: none; background: #111; color: #fff;">Giỏ hàng (<?php echo $cart_count; ?>)</a>
            </div>
        </div>
        <nav class="main-nav">
            <a href="index.php" class="text-red">TRANG CHỦ</a>
            <a href="index.php" class="bg-black">TIẾP TỤC MUA SẮM &rarr;</a>
        </nav>
    </header>

    <!-- NỘI DUNG GIỎ HÀNG -->
    <main class="cart-page">
        <h2>GIỎ HÀNG CỦA BẠN</h2>
        
        <?php if(empty($_SESSION['cart'])): ?>
            <div class="empty-cart">
                <p>Giỏ hàng của bạn đang trống.</p>
                <a href="index.php" class="btn-shopping">Mua sắm ngay</a>
            </div>
        <?php else: ?>
            <div class="cart-container">
                <table class="cart-table">
                    <thead>
                        <tr>
                            <th>Sản phẩm</th>
                            <th>Đơn giá</th>
                            <th>Số lượng</th>
                            <th>Thành tiền</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $total_price = 0;
                        // Lấy danh sách ID sản phẩm từ session
                        $ids = implode(',', array_keys($_SESSION['cart']));
                        
                        // Đảm bảo có ID thì mới truy vấn database
                        if(!empty($ids)) {
                            $sql = "SELECT * FROM products WHERE id IN ($ids)";
                            $result = $conn->query($sql);

                            while($row = $result->fetch_assoc()) {
                                $qty = $_SESSION['cart'][$row['id']];
                                $subtotal = $row['price'] * $qty;
                                $total_price += $subtotal;
                                
                                echo '
                                
                                <tr>
                                    <td class="product-col">
                                        <img src="'.$row['image_url'].'" alt="">
                                        <span>'.$row['name'].'</span>
                                    </td>
                                    <td>'.number_format($row['price'], 0, ',', '.').'đ</td>
                                    
                                    <!-- Thay thế cột số lượng cũ bằng đoạn mới này -->
                                    <td class="center-col">
                                        <div class="qty-control">
                                            <a href="giohang.php?action=decrease&id='.$row['id'].'" class="qty-btn">-</a>
                                            <span class="qty-num">'.$qty.'</span>
                                            <a href="giohang.php?action=increase&id='.$row['id'].'" class="qty-btn">+</a>
                                        </div>
                                    </td>
                                    
                                    <td style="font-weight: bold;">'.number_format($subtotal, 0, ',', '.').'đ</td>
                                    <td><a href="giohang.php?remove='.$row['id'].'" class="remove-btn">Xóa</a></td>
                                </tr>';
                            }
                        }
                        ?>
                    </tbody>
                </table>

                <div class="cart-summary">
                    <h3>Tổng đơn hàng: <span><?php echo number_format($total_price, 0, ',', '.'); ?>đ</span></h3>
                    <button class="btn-checkout">TIẾN HÀNH THANH TOÁN</button>
                </div>
            </div>
        <?php endif; ?>
    </main>

</body>
</html>
<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'shoptheman_db');

if(!isset($_SESSION['cart'])) { $_SESSION['cart'] = []; }

// 1. XỬ LÝ THÊM SẢN PHẨM (CÓ KÈM SIZE)
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'add') {
    $product_id = $_POST['product_id'];
    // Lấy size người dùng chọn (nếu thêm từ trang chủ không có size thì gán mặc định)
    $size = isset($_POST['size']) ? $_POST['size'] : 'Mặc định';
    
    // Tạo khóa duy nhất: Ví dụ "1-XL"
    $cart_key = $product_id . '-' . $size;
    
    if(isset($_SESSION['cart'][$cart_key])) {
        $_SESSION['cart'][$cart_key]++;
    } else {
        $_SESSION['cart'][$cart_key] = 1;
    }
    header("Location: giohang.php"); 
    exit();
}

// 2. XỬ LÝ XÓA SẢN PHẨM
if(isset($_GET['remove'])) {
    $remove_key = $_GET['remove'];
    unset($_SESSION['cart'][$remove_key]);
    header("Location: giohang.php"); exit();
}

// 3. XỬ LÝ TĂNG/GIẢM SỐ LƯỢNG
if(isset($_GET['action']) && isset($_GET['key'])) {
    $key = $_GET['key'];
    if(isset($_SESSION['cart'][$key])) {
        if($_GET['action'] == 'increase') $_SESSION['cart'][$key]++;
        if($_GET['action'] == 'decrease') {
            $_SESSION['cart'][$key]--;
            if($_SESSION['cart'][$key] <= 0) unset($_SESSION['cart'][$key]);
        }
    }
    header("Location: giohang.php"); exit();
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
                        // Lọc ra các ID sản phẩm không trùng lặp từ giỏ hàng
                        $product_ids = [];
                        foreach($_SESSION['cart'] as $key => $qty) {
                            $parts = explode('-', $key);
                            $product_ids[] = $parts[0];
                        }
                        $ids_string = implode(',', array_unique($product_ids));

                        if(!empty($ids_string)) {
                            // Lấy thông tin sản phẩm từ database
                            $result = $conn->query("SELECT * FROM products WHERE id IN ($ids_string)");
                            $products_data = [];
                            while($row = $result->fetch_assoc()) {
                                $products_data[$row['id']] = $row;
                            }

                            // Bắt đầu in từng dòng sản phẩm
                            foreach($_SESSION['cart'] as $key => $qty) {
                                $parts = explode('-', $key);
                                $p_id = $parts[0];
                                
                                // === ĐOẠN CODE ĐÃ ĐƯỢC SỬA LỖI ===
                                // Kiểm tra xem có size (key 1) không, nếu không có thì gán 'Mặc định'
                                $p_size = isset($parts[1]) ? $parts[1] : 'Mặc định'; 
                                
                                // Kiểm tra xem sản phẩm có thực sự tồn tại trong database không
                                if(isset($products_data[$p_id])) {
                                    $row = $products_data[$p_id];
                                    
                                    // Nếu có giá Sale thì lấy giá Sale để tính tiền
                                    $current_price = ($row['sale_price'] > 0) ? $row['sale_price'] : $row['price'];
                                    $subtotal = $current_price * $qty;
                                    $total_price += $subtotal;
                                    
                                    echo '
                                    <tr>
                                        <td class="product-col">
                                            <img src="'.$row['image_url'].'" alt="">
                                            <div>
                                                <span style="display:block;">'.$row['name'].'</span>
                                                <small style="color:#d32f2f;">Size: '.$p_size.'</small>
                                            </div>
                                        </td>
                                        <td>'.number_format($current_price, 0, ',', '.').'đ</td>
                                        <td class="center-col">
                                            <div class="qty-control">
                                                <a href="giohang.php?action=decrease&key='.$key.'" class="qty-btn">-</a>
                                                <span class="qty-num">'.$qty.'</span>
                                                <a href="giohang.php?action=increase&key='.$key.'" class="qty-btn">+</a>
                                            </div>
                                        </td>
                                        <td style="font-weight: bold;">'.number_format($subtotal, 0, ',', '.').'đ</td>
                                        <td><a href="giohang.php?remove='.$key.'" class="remove-btn">Xóa</a></td>
                                    </tr>';
                                }
                            }
                        }
                        ?>
                    </tbody>
                </table>

                <div class="cart-summary">
                    <h3>Tổng đơn hàng: <span><?php echo number_format($total_price, 0, ',', '.'); ?>đ</span></h3>
                    <a href="thanhtoan.php" class="btn-checkout" style="display: inline-block; text-decoration: none; text-align: center;">TIẾN HÀNH THANH TOÁN</a>
                </div>
            </div>
        <?php endif; ?>
    </main>

</body>
</html>
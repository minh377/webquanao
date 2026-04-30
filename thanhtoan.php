<?php
session_start();

// Kết nối Database
$host = 'localhost'; $user = 'root'; $pass = ''; $db = 'shoptheman_db'; 
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) die("Kết nối thất bại: " . $conn->connect_error);

// Nếu giỏ hàng trống thì ép quay về trang chủ
if(empty($_SESSION['cart'])) {
    header("Location: index.php");
    exit();
}

// 1. TÍNH TỔNG TIỀN VÀ LẤY THÔNG TIN SẢN PHẨM TRONG GIỎ
$total_price = 0;
$ids = implode(',', array_keys($_SESSION['cart']));
$cart_products = [];

if(!empty($ids)) {
    $sql = "SELECT id, name, price, image_url FROM products WHERE id IN ($ids)";
    $result = $conn->query($sql);
    while($row = $result->fetch_assoc()) {
        $qty = $_SESSION['cart'][$row['id']];
        $total_price += $row['price'] * $qty;
        $row['quantity'] = $qty; // Lưu số lượng vào mảng để dùng sau
        $cart_products[] = $row;
    }
}

// 2. XỬ LÝ LƯU VÀO DATABASE KHI BẤM "ĐẶT HÀNG"
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['place_order'])) {
    $fullname = $conn->real_escape_string($_POST['fullname']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $address = $conn->real_escape_string($_POST['address']);
    $note = $conn->real_escape_string($_POST['note']);

    // Bước 2.1: Lưu thông tin vào bảng orders
    $sql_order = "INSERT INTO orders (fullname, phone, address, note, total_price) 
                  VALUES ('$fullname', '$phone', '$address', '$note', '$total_price')";
    
    if($conn->query($sql_order) === TRUE) {
        $order_id = $conn->insert_id; // Lấy ID của đơn hàng vừa tạo
        
        // Bước 2.2: Lưu từng sản phẩm vào bảng order_details
        foreach($cart_products as $item) {
            $p_id = $item['id'];
            $p_qty = $item['quantity'];
            $p_price = $item['price'];
            $sql_detail = "INSERT INTO order_details (order_id, product_id, quantity, price) 
                           VALUES ('$order_id', '$p_id', '$p_qty', '$p_price')";
            $conn->query($sql_detail);
        }

        // Bước 2.3: Xóa giỏ hàng và thông báo thành công
        unset($_SESSION['cart']);
        echo "<script>
                alert('Đặt hàng thành công! Đơn hàng của bạn đang chờ xử lý.');
                window.location.href = 'index.php';
              </script>";
        exit();
    } else {
        echo "<script>alert('Có lỗi xảy ra, vui lòng thử lại!');</script>";
    }
}

// Nếu đã đăng nhập, tự điền sẵn tên
$default_name = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : '';
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thanh toán - ShopTheMan</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="trangchu.css">
    <link rel="stylesheet" href="thanhtoan.css">
</head>
<body>
    <!-- HEADER RÚT GỌN CHO TRANG THANH TOÁN -->
    <header style="border-bottom: 1px solid #eaeaea;">
        <div class="top-header" style="justify-content: center; padding: 20px;">
            <a href="index.php" style="text-decoration: none; color: #111;">
                <h1 class="logo">ShopTheMan. <span style="font-size: 18px; font-weight: 400; color: #666;">| Thanh toán</span></h1>
            </a>
        </div>
    </header>

    <main class="checkout-page">
        <form action="thanhtoan.php" method="POST" class="checkout-container">
            
            <!-- CỘT TRÁI: THÔNG TIN GIAO HÀNG -->
            <div class="checkout-form">
                <h2>Thông tin giao hàng</h2>
                
                <div class="input-group">
                    <label>Họ và tên người nhận *</label>
                    <input type="text" name="fullname" value="<?php echo $default_name; ?>" required placeholder="Nhập họ và tên...">
                </div>
                
                <div class="input-group">
                    <label>Số điện thoại *</label>
                    <input type="tel" name="phone" required placeholder="Nhập số điện thoại...">
                </div>
                
                <div class="input-group">
                    <label>Địa chỉ nhận hàng chi tiết *</label>
                    <textarea name="address" required placeholder="Ví dụ: Số 123, đường ABC, phường XYZ, quận..."></textarea>
                </div>
                
                <div class="input-group">
                    <label>Ghi chú đơn hàng (Tùy chọn)</label>
                    <textarea name="note" placeholder="Ghi chú về thời gian giao hàng..."></textarea>
                </div>
            </div>

            <!-- CỘT PHẢI: TỔNG QUAN ĐƠN HÀNG -->
            <div class="checkout-sidebar">
                <h2>Đơn hàng của bạn</h2>
                <div class="order-items">
                    <?php foreach($cart_products as $item): ?>
                        <div class="order-item">
                            <img src="<?php echo $item['image_url']; ?>" alt="">
                            <div class="item-info">
                                <h4><?php echo $item['name']; ?></h4>
                                <p>SL: <?php echo $item['quantity']; ?></p>
                            </div>
                            <div class="item-price">
                                <?php echo number_format($item['price'] * $item['quantity'], 0, ',', '.'); ?>đ
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <div class="order-total">
                    <p>Tổng tiền thanh toán:</p>
                    <h3><?php echo number_format($total_price, 0, ',', '.'); ?>đ</h3>
                </div>
                <p style="font-size: 13px; color: #666; margin-bottom: 20px; text-align: center;">Thanh toán khi nhận hàng (COD)</p>
                <button type="submit" name="place_order" class="btn-place-order">ĐẶT HÀNG NGAY</button>
            </div>
            
        </form>
    </main>
</body>
</html>
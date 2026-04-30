<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'shoptheman_db');

if(!isset($_GET['id'])) { header("Location: index.php"); exit(); }

$id = intval($_GET['id']);
$sql = "SELECT * FROM products WHERE id = $id";
$result = $conn->query($sql);
$product = $result->fetch_assoc();

if(!$product) { die("Sản phẩm không tồn tại!"); }

// Tách chuỗi sizes (VD: "S, M, L") thành mảng để tạo nút chọn
$sizes_array = explode(',', $product['sizes']);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title><?php echo $product['name']; ?> - ShopTheMan</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="trangchu.css">
    <style>
        .detail-container { display: flex; gap: 50px; max-width: 1000px; margin: 60px auto; padding: 20px; }
        .detail-img { flex: 1; }
        .detail-img img { width: 100%; border-radius: 8px; }
        .detail-info { flex: 1; }
        .detail-info h1 { font-size: 32px; margin-bottom: 15px; text-transform: uppercase; }
        .detail-price { font-size: 24px; color: #d32f2f; font-weight: bold; margin-bottom: 25px; }
        .old-price { font-size: 18px; color: #999; text-decoration: line-through; margin-right: 15px; }
        
        .size-selector { margin-bottom: 30px; }
        .size-selector p { font-weight: bold; margin-bottom: 10px; }
        .size-option { display: inline-block; margin-right: 10px; }
        .size-option input[type="radio"] { display: none; }
        .size-option label { display: block; padding: 10px 20px; border: 1px solid #ccc; cursor: pointer; border-radius: 4px; font-weight: 500; transition: 0.3s; }
        .size-option input[type="radio"]:checked + label { background: #111; color: #fff; border-color: #111; }
        
        .btn-add-cart { padding: 18px 40px; background: #d32f2f; color: #fff; border: none; font-weight: bold; font-size: 16px; cursor: pointer; border-radius: 4px; width: 100%; transition: 0.3s;}
        .btn-add-cart:hover { background: #111; }
    </style>
</head>
<body>
    <!-- (Bạn hãy copy nguyên khối <header> từ index.php dán vào đây nhé) -->

    <main class="detail-container">
        <div class="detail-img">
            <img src="<?php echo $product['image_url']; ?>" alt="<?php echo $product['name']; ?>">
        </div>
        
        <div class="detail-info">
            <h1><?php echo $product['name']; ?></h1>
            <div class="detail-price">
                <?php if($product['sale_price'] > 0): ?>
                    <span class="old-price"><?php echo number_format($product['price'], 0, ',', '.'); ?>đ</span>
                    <?php echo number_format($product['sale_price'], 0, ',', '.'); ?>đ
                <?php else: ?>
                    <?php echo number_format($product['price'], 0, ',', '.'); ?>đ
                <?php endif; ?>
            </div>

            <!-- FORM CHỌN SIZE & THÊM GIỎ HÀNG -->
            <form action="giohang.php" method="POST">
                <input type="hidden" name="action" value="add">
                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                
                <div class="size-selector">
                    <p>CHỌN KÍCH THƯỚC:</p>
                    <?php foreach($sizes_array as $index => $size): $size = trim($size); ?>
                        <div class="size-option">
                            <!-- Nút đầu tiên tự động được check -->
                            <input type="radio" id="size_<?php echo $size; ?>" name="size" value="<?php echo $size; ?>" <?php echo ($index == 0) ? 'checked' : ''; ?>>
                            <label for="size_<?php echo $size; ?>"><?php echo $size; ?></label>
                        </div>
                    <?php endforeach; ?>
                </div>

                <button type="submit" class="btn-add-cart">THÊM VÀO GIỎ HÀNG</button>
            </form>
        </div>
    </main>
</body>
</html>
<?php
session_start();

$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'shoptheman_db'; 

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action'];
    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password'];

    // 1. XỬ LÝ ĐĂNG KÝ
    if ($action == 'register') {
        $fullname = $conn->real_escape_string($_POST['fullname']);
        
        // Kiểm tra xem email đã tồn tại trong database chưa
        $check_email = $conn->query("SELECT * FROM users WHERE email='$email'");
        
        if ($check_email->num_rows > 0) {
            echo "<script>alert('Email này đã được đăng ký!'); window.location.href='index.php';</script>";
        } else {
            // Mã hóa mật khẩu để bảo mật trước khi lưu
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO users (fullname, email, password) VALUES ('$fullname', '$email', '$hashed_password')";
            
            if ($conn->query($sql) === TRUE) {
                echo "<script>alert('Đăng ký thành công! Bạn có thể đăng nhập ngay bây giờ.'); window.location.href='index.php';</script>";
            } else {
                echo "Lỗi: " . $conn->error;
            }
        }
    } 
    
    // 2. XỬ LÝ ĐĂNG NHẬP
    elseif ($action == 'login') {
        $sql = "SELECT * FROM users WHERE email='$email'";
        $result = $conn->query($sql);
        
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            // Giải mã và so sánh mật khẩu
            if (password_verify($password, $row['password'])) {
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['user_name'] = $row['fullname'];
                echo "<script>alert('Đăng nhập thành công!'); window.location.href='index.php';</script>";
            } else {
                echo "<script>alert('Sai mật khẩu!'); window.location.href='index.php';</script>";
            }
        } else {
            echo "<script>alert('Không tìm thấy tài khoản nào với email này!'); window.location.href='index.php';</script>";
        }
    }
}
$conn->close();
?>
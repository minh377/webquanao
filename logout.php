<?php
session_start();
session_unset(); // Xóa toàn bộ biến session
session_destroy(); // Hủy session
header("Location: index.php"); // Quay về trang chủ
exit();
?>
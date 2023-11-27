<?php
// Bắt đầu phiên làm việc
session_start();

// Hủy phiên làm việc
session_destroy();

// Xóa COOKIE nếu có
setcookie('user', '', time() - 3600, '/');

// Chuyển hướng đến trang đăng nhập
header("Location: login.php");
exit();

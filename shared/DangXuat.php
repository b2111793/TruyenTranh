<?php
    session_start(); 
    // Kiểm tra trạng thái đăng nhập
    if (isset($_SESSION["tenDangNhap"])) {
        if ($_SESSION["quyen"] == "Admin") {
            session_unset();
            session_destroy();
            echo "<script> window.location.href = './DangNhap.php'; </script>";
            exit;
        } else {
            session_unset();
            session_destroy();
            echo "<script> window.location.href = '../users/TrangChu.php'; </script>";
            exit;
        }
    }
?>

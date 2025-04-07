<?php
    session_start();
    include('../shared/database.php');

    if (!isset($_SESSION["tenDangNhap"])) {
        echo "Vui lòng đăng nhập để thêm sản phẩm vào yêu thích!";
        exit;
    }

    if (!isset($_POST["maSanPham"])) {
        echo "Không tìm thấy mã sản phẩm!";
        exit;
    }

    $tenDangNhap = mysqli_real_escape_string($conn, $_SESSION["tenDangNhap"]);
    $maSanPham = intval($_POST["maSanPham"]);

    // Thêm sản phẩm vào danh sách yêu thích
    $themYeuThich = "INSERT INTO yeuthich (TenDangNhap, MaSanPham) VALUES ('$tenDangNhap', '$maSanPham')";
    if (mysqli_query($conn, $themYeuThich)) {
        echo "Đã thêm sản phẩm vào danh sách yêu thích!";
        exit;
    } else {
        echo "Đã xảy ra lỗi khi thêm sản phẩm vào yêu thích: " . mysqli_error($conn);
        exit;
    }
?>
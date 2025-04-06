<?php
    session_start();
    include('../shared/database.php');

    if (!isset($_SESSION["tenDangNhap"])) {
        echo "Vui lòng đăng nhập để xóa sản phẩm yêu thích!";
        exit;
    }

	if (isset($_SESSION["tenDangNhap"]) && $_SESSION["quyen"] == "Admin") {
        echo "<script> window.location.href = '../admin/TrangChuAdmin.php'; </script>";
        exit;
	}

    if (!isset($_POST['maSanPham'])) {
        echo "Không tìm thấy mã sản phẩm!";
        exit;
    }

    $tenDangNhap = mysqli_real_escape_string($conn, $_SESSION["tenDangNhap"]);
    $maSanPham = intval($_POST['maSanPham']);

    // Xóa sản phẩm khỏi danh sách yêu thích
    $xoaYeuThich = "DELETE FROM yeuthich WHERE TenDangNhap = '$tenDangNhap' AND MaSanPham = '$maSanPham'";
    if (mysqli_query($conn, $xoaYeuThich)) {
        echo "Đã xóa sản phẩm khỏi danh sách yêu thích!";
        exit;
    } else {
        echo "Đã xảy ra lỗi khi xóa sản phẩm yêu thích: " . mysqli_error($conn);
        exit;
    }
?>
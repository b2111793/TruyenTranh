<?php
    session_start();
    include('../shared/database.php');

    if (!isset($_SESSION["tenDangNhap"])) {
        echo "Vui lòng đăng nhập để kiểm tra trạng thái yêu thích!";
        exit;
    }

    if (isset($_SESSION["tenDangNhap"]) && $_SESSION["quyen"] == "Admin") {
        echo "<script> window.location.href = '../admin/TrangChuAdmin.php'; </script>";
        exit;
	}

    if (!isset($_POST["maSanPham"])) {
        echo "Không tìm thấy mã sản phẩm!";
        exit;
    }

    $tenDangNhap = mysqli_real_escape_string($conn, $_SESSION["tenDangNhap"]);
    $maSanPham = intval($_POST["maSanPham"]);

    // Kiểm tra sản phẩm đã có trong danh sách yêu thích chưa
    $kiemTra = "SELECT * FROM yeuthich WHERE TenDangNhap = '$tenDangNhap' AND MaSanPham = '$maSanPham'";
    $truyVan_KiemTra = mysqli_query($conn, $kiemTra);

    if (mysqli_num_rows($truyVan_KiemTra) > 0) {
        echo "Đã yêu thích";
        exit;
    } else {
        echo "Chưa yêu thích";
        exit;
    }
?>
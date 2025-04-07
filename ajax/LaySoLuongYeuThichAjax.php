<?php
    session_start();
    include('../shared/database.php');

    if (!isset($_SESSION["tenDangNhap"])) {
        echo "0";
        exit;
    }

    if (isset($_SESSION["tenDangNhap"]) && $_SESSION["quyen"] == "Admin") {
        echo "<script> window.location.href = '../admin/TrangChuAdmin.php'; </script>";
        exit;
	}

    $tenDangNhap = mysqli_real_escape_string($conn, $_SESSION["tenDangNhap"]);

    // Đếm tổng số sản phẩm yêu thích
    $query = "SELECT COUNT(*) as total FROM yeuthich WHERE TenDangNhap = '$tenDangNhap'";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);

    echo $row['total'];
    exit;
?>
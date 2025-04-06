<?php
    session_start();
    include('../shared/database.php');

    if (!isset($_SESSION["tenDangNhap"])) {
        echo "Vui lòng đăng nhập để sửa bình luận!";
        exit;
    }

    // Kiểm tra dữ liệu đầu vào
    if (!isset($_POST["maBinhLuan"]) || !isset($_POST["noiDung"])) {
        echo "Thiếu thông tin để sửa bình luận!";
        exit;
    }

    $maBinhLuan = mysqli_real_escape_string($conn, $_POST["maBinhLuan"]);
    $noiDung = mysqli_real_escape_string($conn, $_POST["noiDung"]);
    $tenDangNhap = mysqli_real_escape_string($conn, $_SESSION["tenDangNhap"]);

    // Kiểm tra quyền sở hữu bình luận
    $kiemTraQuyen = "SELECT * FROM binhluan WHERE MaBinhLuan = '$maBinhLuan' AND TenDangNhap = '$tenDangNhap'";
    $truyVan_KiemTraQuyen = mysqli_query($conn, $kiemTraQuyen);
    if (mysqli_num_rows($truyVan_KiemTraQuyen) == 0) {
        echo "Bạn không có quyền sửa bình luận này!";
        exit;
    }

    // Cập nhật bình luận
    $capNhatBinhLuan = "UPDATE binhluan SET NoiDung = '$noiDung' WHERE MaBinhLuan = '$maBinhLuan'";
    if (mysqli_query($conn, $capNhatBinhLuan)) {
        echo "Cập nhật bình luận thành công!";
    } else {
        echo "Đã xảy ra lỗi khi cập nhật bình luận: " . mysqli_error($conn);
    }
    exit;
?>
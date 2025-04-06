<?php 
    session_start();
    include('../shared/database.php');

    if (!isset($_SESSION["tenDangNhap"])) {
        echo "Vui lòng đăng nhập để sửa bình luận!";
        exit;
    }

    // Kiểm tra dữ liệu đầu vào
    if (!isset($_POST["maBinhLuan"])) {
        echo "Thiếu thông tin để xóa bình luận!";
        exit;
    }

    $maBinhLuan = mysqli_real_escape_string($conn, $_POST["maBinhLuan"]);
    $tenDangNhap = mysqli_real_escape_string($conn, $_SESSION["tenDangNhap"]);

    // Kiểm tra quyền sở hữu bình luận
    $kiemTraQuyen = "SELECT * FROM binhluan WHERE MaBinhLuan = '$maBinhLuan' AND TenDangNhap = '$tenDangNhap'";
    $truyVan_KiemTraQuyen = mysqli_query($conn, $kiemTraQuyen);
    if (mysqli_num_rows($truyVan_KiemTraQuyen) == 0) {
        echo "Bạn không có quyền xóa bình luận này!";
        exit;
    }

    // Xóa bình luận
    $xoaBinhLuan = "DELETE FROM binhluan WHERE MaBinhLuan = '$maBinhLuan'";
    if(mysqli_query($conn, $xoaBinhLuan))
        echo "Đã xóa bình luận thành công!";
    else
        echo "Đã xảy ra lỗi khi xóa bình luận!" . mysqli_error($conn);
?>
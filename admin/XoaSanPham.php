<?php
    include('../shared/headerAdmin.php');
    if(!isset($_SESSION["tenDangNhap"])){
        echo "<script> window.location.href = '../shared/DangNhap.php'; </script>";
        exit;
    }

    if(isset($_SESSION["tenDangNhap"]) && $_SESSION["quyen"] == "Member"){
        echo "<script> window.location.href = '../users/TrangChu.php'; </script>";
        exit;
    }

    if (!isset($_GET["maSanPham"])) {
        $_SESSION['toastr'] = ['type' => 'warning', 'message' => 'Không tìm thấy mã sản phẩm!'];
        echo "<script> window.location.href = './QuanLySanPham.php'; </script>";
        exit;
    }

    $maSanPham = mysqli_real_escape_string($conn, $_GET["maSanPham"]);

    // Kiểm tra sản phẩm có tồn tại không
    $laySanPham = "SELECT * FROM sanpham WHERE MaSanPham = '$maSanPham'";
    $truyVan_LaySanPham = mysqli_query($conn, $laySanPham);
    if (mysqli_num_rows($truyVan_LaySanPham) == 0) {
        $_SESSION['toastr'] = ['type' => 'warning', 'message' => 'Sản phẩm không tồn tại!'];
        echo "<script> window.location.href = './QuanLySanPham.php'; </script>";
        exit;
    }
    $sanPham = mysqli_fetch_assoc($truyVan_LaySanPham);

    // Kiểm tra xem sản phẩm có trong đơn hàng không
    $kiemTraDonHang = "SELECT * FROM ct_dondat WHERE MaSanPham = '$maSanPham'";
    $truyVan_KiemTraDonHang = mysqli_query($conn, $kiemTraDonHang);
    if (mysqli_num_rows($truyVan_KiemTraDonHang) > 0) {
        $_SESSION['toastr'] = ['type' => 'warning', 'message' => 'Không thể xóa sản phẩm vì sản phẩm đã có trong đơn đặt!'];
        echo "<script> window.location.href = './QuanLySanPham.php'; </script>";
        exit;
    }

    // Xóa các đánh giá liên quan
    $xoaDanhGia = "DELETE FROM danhgia WHERE MaSanPham = '$maSanPham'";
    mysqli_query($conn, $xoaDanhGia);

    // Xóa yêu thích liên quan
    $xoaYeuThich = "DELETE FROM yeuthich WHERE MaSanPham = '$maSanPham'";
    mysqli_query($conn, $xoaYeuThich);

    // Xóa các file ảnh nếu tồn tại
    if (!empty($sanPham['Anh']) && file_exists($sanPham['Anh'])) {
        unlink($sanPham['Anh']);
    }
    if (!empty($sanPham['AnhMoTa1']) && file_exists($sanPham['AnhMoTa1'])) {
        unlink($sanPham['AnhMoTa1']);
    }
    if (!empty($sanPham['AnhMoTa2']) && file_exists($sanPham['AnhMoTa2'])) {
        unlink($sanPham['AnhMoTa2']);
    }
    if (!empty($sanPham['AnhMoTa3']) && file_exists($sanPham['AnhMoTa3'])) {
        unlink($sanPham['AnhMoTa3']);
    }

    // Xóa sản phẩm
    $xoaSanPham = "DELETE FROM sanpham WHERE MaSanPham = '$maSanPham'";
    if (mysqli_query($conn, $xoaSanPham)) {
        $_SESSION['toastr'] = ['type' => 'success', 'message' => 'Xóa sản phẩm thành công!'];
        echo "<script> window.location.href = './QuanLySanPham.php'; </script>";
        exit;
    } else {
        $_SESSION['toastr'] = ['type' => 'error', 'message' => 'Đã xảy ra lỗi khi xóa sản phẩm: ' . mysqli_error($conn)];
        echo "<script> window.location.href = './QuanLySanPham.php'; </script>";
        exit;
    }
?>

<?php include('../shared/footerAdmin.php'); ?>
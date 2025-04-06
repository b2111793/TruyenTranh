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

    if (!isset($_GET["maLoaiSP"])){
        echo "<script> window.location.href = './QuanLyDanhMuc.php'; </script>";
    }

    $maLoaiSP = intval($_GET["maLoaiSP"]);

    // Kiểm tra xem danh mục có sản phẩm liên quan không
    $kiemTraTonTai = "SELECT COUNT(*) as tongSanPham FROM sanpham WHERE MaLoaiSP = '$maLoaiSP'";
    $truyVan_KiemTraTonTai = mysqli_query($conn, $kiemTraTonTai);
    $cot = mysqli_fetch_assoc($truyVan_KiemTraTonTai);

    if($cot['tongSanPham'] > 0){
        $_SESSION['toastr'] = ['type' => 'warning', 'message' => 'Không thể xóa danh mục vì vẫn còn sản phẩm thuộc danh mục này!'];
        echo "<script> window.location.href = './QuanLyDanhMuc.php'; </script>";
        exit;
    } 
    else{
        // Xóa danh mục
        $xoaDanhMuc = "DELETE FROM loaisp WHERE MaLoaiSP = '$maLoaiSP'";
        if(mysqli_query($conn, $xoaDanhMuc)){
            $_SESSION['toastr'] = ['type' => 'success', 'message' => 'Xóa danh mục thành công!'];
            echo "<script> window.location.href = './QuanLyDanhMuc.php';</script> ";
            exit;
        } 
        else{
            $_SESSION['toastr'] = ['type' => 'error', 'message' => 'Đã xảy ra lỗi khi xóa danh mục!'];
            echo "<script> window.location.href = './QuanLyDanhMuc.php';</script> ";
            exit;
        }
    }
?>
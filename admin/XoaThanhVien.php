<?php
    session_start();
    include('../shared/database.php');
    if(!isset($_SESSION["tenDangNhap"])){
        echo "<script> window.location.href = '../shared/DangNhap.php'; </script>";
        exit;
    }

    if(isset($_SESSION["tenDangNhap"]) && $_SESSION["quyen"] == "Member"){
        echo "<script> window.location.href = '../users/TrangChu.php'; </script>";
        exit;
    }

    if (isset($_GET['tenDangNhap'])) {
        $tenDangNhap = mysqli_real_escape_string($conn, $_GET['tenDangNhap']);
        $xoa = "DELETE FROM nguoidung WHERE TenDangNhap = '$tenDangNhap'";
        if (mysqli_query($conn, $xoa)) {
            $_SESSION['toastr'] = ['type' => 'success', 'message' => 'Xóa thành viên thành công!'];
            echo "<script> window.location.href = './QuanLyThanhVien.php'; </script>";
            exit;
        } else {
            $_SESSION['toastr'] = ['type' => 'error', 'message' => 'Lỗi khi xóa thành viên: ' . mysqli_error($conn)];
            echo "<script> window.location.href = './QuanLyThanhVien.php'; </script>";
            exit;
        }
    } else {
        $_SESSION['toastr'] = ['type' => 'error', 'message' => 'Không tìm thấy thành viên!'];
        echo "<script> window.location.href = './QuanLyThanhVien.php'; </script>";
        exit;
    }
?>
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
        $moKhoa = "UPDATE nguoidung SET TrangThai = 'Kích hoạt' WHERE TenDangNhap = '$tenDangNhap'";
        if (mysqli_query($conn, $moKhoa)) {
            $_SESSION['toastr'] = ['type' => 'success', 'message' => 'Mở khóa thành viên thành công!'];
            echo "<script> window.location.href = './QuanLyThanhVien.php'; </script>";
            exit;
        } else {
            $_SESSION['toastr'] = ['type' => 'error', 'message' => 'Lỗi khi mở khóa thành viên: ' . mysqli_error($conn)];
            echo "<script> window.location.href = './QuanLyThanhVien.php'; </script>";
            exit;
        }
    } else {
        $_SESSION['toastr'] = ['type' => 'warning', 'message' => 'Không tìm thấy thành viên'];
        echo "<script> window.location.href = './QuanLyThanhVien.php'; </script>";
        exit;
    }
?>
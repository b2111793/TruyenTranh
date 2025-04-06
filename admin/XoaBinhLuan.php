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

    // Kiểm tra MaBinhLuan từ URL
    if (!isset($_GET['maBinhLuan']) || !is_numeric($_GET['maBinhLuan'])) {
        $_SESSION['toastr'] = ['type' => 'warning', 'message' => 'Mã bình luận không tồn tại!'];
        echo "<script> window.location.href = './QuanLyBinhLuan.php'; </script>";
        exit;
    }

    $maBinhLuan = intval($_GET['maBinhLuan']);

    // Xóa bình luận
    $xoaBinhLuan = "DELETE FROM binhluan WHERE MaBinhLuan = $maBinhLuan";
    if (mysqli_query($conn, $xoaBinhLuan)) {
        $_SESSION['toastr'] = ['type' => 'success', 'message' => 'Xóa bình luận thành công!'];
        echo "<script> window.location.href = './QuanLyBinhLuan.php'; </script>";
        exit;
    } else {
        $_SESSION['toastr'] = ['type' => 'error', 'message' => 'Xóa bình luận thất bại: ' . mysqli_error($conn)];
        echo "<script> window.location.href = './QuanLyBinhLuan.php'; </script>";
        exit;
    }
?>
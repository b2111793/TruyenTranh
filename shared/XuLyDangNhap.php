<?php
    session_start();
    include(__DIR__ . '/database.php');

    // Kiểm tra xem form có được gửi không
    if ($_SERVER["REQUEST_METHOD"] != "POST") {
        $_SESSION['toastr'] = ['type' => 'error', 'message' => 'Yêu cầu không hợp lệ!'];
        echo "<script> window.location.href = './DangNhap.php'; </script>";
        exit;
    }

    // Lấy dữ liệu từ form
    $tenDangNhap = mysqli_real_escape_string($conn, $_POST["tenDangNhap"]);
    $matKhau = $_POST["matKhau"];
    $trangHienTai = $_POST["trangHienTai"];

    // Truy vấn kiểm tra tài khoản
    $kiemTraTonTai = "SELECT * FROM nguoidung WHERE TenDangNhap = '$tenDangNhap'";
    $truyVan_KiemTraTonTai = mysqli_query($conn, $kiemTraTonTai);

    if (mysqli_num_rows($truyVan_KiemTraTonTai) > 0) {
        $cot = mysqli_fetch_assoc($truyVan_KiemTraTonTai);
        
        // Kiểm tra mật khẩu đã mã hóa
        if (password_verify($matKhau, $cot['MatKhau'])) {
            // Kiểm tra trạng thái tài khoản
            if ($cot["TrangThai"] == 'Khóa') {
                $_SESSION['toastr'] = ['type' => 'error', 'message' => 'Tài khoản của bạn hiện đang bị khóa! Vui lòng liên hệ Admin.'];
                echo "<script> window.location.href = '$trangHienTai'; </script>";
                exit;
            } else {
                // Đăng nhập thành công
                $_SESSION["tenDangNhap"] = $tenDangNhap;
                $_SESSION["quyen"] = $cot["Quyen"]; // Lưu quyền vào session
                $_SESSION['toastr'] = ['type' => 'success', 'message' => 'Đăng nhập thành công!'];
                // Chuyển hướng dựa trên quyền
                if ($cot["Quyen"] == "Admin") {
                    echo "<script> window.location.href = '../admin/TrangChuAdmin.php'; </script>";
                    exit;
                } else {
                    echo "<script> window.location.href = '$trangHienTai'; </script>";
                    exit;
                }
                exit;
            }
        } else {
            // Mật khẩu không đúng
            $_SESSION['toastr'] = ['type' => 'error', 'message' => 'Mật khẩu chưa đúng!'];
            echo "<script> window.location.href = '$trangHienTai'; </script>";
            exit;
        }
    } else {
        // Tên đăng nhập không tồn tại
        $_SESSION['toastr'] = ['type' => 'error', 'message' => 'Tên đăng nhập chưa đúng!'];
        echo "<script> window.location.href = '$trangHienTai'; </script>";
        exit;
    }
?>
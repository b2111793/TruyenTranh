<?php
    session_start();
    include('../shared/database.php');

    $tenDangNhap = mysqli_real_escape_string($conn, $_POST["tenDangNhap"]);
    $matKhauCu = mysqli_real_escape_string($conn, $_POST["matKhauCu"]);
    $matKhauMoi = mysqli_real_escape_string($conn, $_POST["matKhauMoi"]);
    $matKhauMoi = password_hash($matKhauMoi, PASSWORD_DEFAULT);

    $kiemTraTonTai = "SELECT * FROM nguoidung WHERE TenDangNhap = '$tenDangNhap'"; 
    $truyVan_KiemTraTonTai = mysqli_query($conn, $kiemTraTonTai);
    if(mysqli_num_rows($truyVan_KiemTraTonTai) > 0){
        $cot = mysqli_fetch_assoc($truyVan_KiemTraTonTai);
        if (password_verify($matKhauCu, $cot['MatKhau'])) {
            $doiMatKhau = "UPDATE nguoidung SET MatKhau = '$matKhauMoi' WHERE TenDangNhap = '$tenDangNhap'";
            if(mysqli_query($conn, $doiMatKhau)){
                echo "Đổi mật khẩu thành công!";
            }
            else{
                echo "Xảy ra lỗi!";
            }
        }
        else{
            echo "Mật khẩu hiện tại không chính xác!";
        }  
    }
?>
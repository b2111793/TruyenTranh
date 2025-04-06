<?php 
    session_start();
    include('../shared/database.php');

    if($_POST["tenDangNhap"] == ""){
        echo "Bạn phải đăng nhập mới có thể đánh giá!";
    }
    else{
        $maSanPham = mysqli_real_escape_string($conn, $_POST["maSanPham"]);
        $tenDangNhap = mysqli_real_escape_string($conn, $_POST["tenDangNhap"]);
        $layDanhGia = "SELECT * FROM danhgia WHERE MaSanPham = '$maSanPham' AND TenDangNhap = '$tenDangNhap'";
        $truyVan_LayDanhGia = mysqli_query($conn, $layDanhGia);

        if(mysqli_num_rows($truyVan_LayDanhGia) > 0){
            echo "Bạn đã đánh giá sản phẩm này rồi!";
            exit;
        }
        else{
            $noiDung = mysqli_real_escape_string($conn, $_POST["noiDung"]);
            $themDanhGia = "INSERT INTO danhgia VALUES('$maSanPham', '$tenDangNhap', '$noiDung')";
            
            if(mysqli_query($conn, $themDanhGia)){
                echo "Đánh giá thành công!";
                exit;
            }
            else{
                echo "Đã xảy ra lỗi khi đánh giá sản phẩm: " . mysqli_error($conn);
                exit;
            }
        }
    }
?>
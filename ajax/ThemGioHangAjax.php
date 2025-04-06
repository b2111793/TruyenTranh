<?php
    session_start();
    include('../shared/database.php');

    // Kiểm tra đăng nhập
    if (!isset($_SESSION["tenDangNhap"])) {
        echo "Vui lòng đăng nhập để thêm sản phẩm vào giỏ hàng!";
        exit;
    }

    // Lấy dữ liệu từ yêu cầu AJAX
    $maSanPham = mysqli_real_escape_string($conn, $_POST["maSanPham"]);
    $soLuong = (int)$_POST["soLuong"];

    // Kiểm tra số lượng hợp lệ
    if ($soLuong <= 0) {
        echo "Số lượng không hợp lệ! Vui lòng chọn số lượng lớn hơn 0.";
        exit;
    }

    // Lấy thông tin sản phẩm
    $laySanPham = "SELECT * FROM sanpham WHERE MaSanPham = '$maSanPham'";
    $truyVan_LaySanPham = mysqli_query($conn, $laySanPham);

    if (mysqli_num_rows($truyVan_LaySanPham) == 0) {
        echo "Sản phẩm không tồn tại!";
        exit;
    }

    $cot = mysqli_fetch_assoc($truyVan_LaySanPham);

    // Kiểm tra số lượng tồn kho
    if ($cot["SoLuong"] == 0) {
        echo "Sản phẩm đã hết hàng!";
        exit;
    }

    // Khởi tạo giỏ hàng mới
    $gioHangMoi = array(array(
        "maSanPham" => $cot["MaSanPham"],
        "anhSanPham" => $cot["Anh"],
        "tenSanPham" => $cot["TenSanPham"],
        "soLuong" => $soLuong,
        "donGia" => $cot["DonGia"],
        "soLuongSanPhamHienCon" => $cot["SoLuong"]
    ));

    // Xử lý giỏ hàng
    if (isset($_SESSION["gioHang"])) { // Giỏ hàng đã tồn tại
        $themSanPhamMoi = false;
        $gioHangDaCo = [];

        foreach ($_SESSION["gioHang"] as $cotGioHang) {
            if ($cotGioHang["maSanPham"] == $maSanPham) {
                $soLuongDat = $cotGioHang["soLuong"] + $soLuong;
                if ($soLuongDat > $cot["SoLuong"]) {
                    $gioHangDaCo[] = array(
                        "maSanPham" => $cotGioHang["maSanPham"],
                        "anhSanPham" => $cotGioHang["anhSanPham"],
                        "tenSanPham" => $cotGioHang["tenSanPham"],
                        "soLuong" => $cotGioHang["soLuong"],
                        "donGia" => $cotGioHang["donGia"],
                        "soLuongSanPhamHienCon" => $cotGioHang["soLuongSanPhamHienCon"]
                    );
                    echo "Số lượng đặt vượt quá sản phẩm hiện tại còn!";
                    exit;
                } else {
                    $gioHangDaCo[] = array(
                        "maSanPham" => $cotGioHang["maSanPham"],
                        "anhSanPham" => $cotGioHang["anhSanPham"],
                        "tenSanPham" => $cotGioHang["tenSanPham"],
                        "soLuong" => $soLuongDat,
                        "donGia" => $cotGioHang["donGia"],
                        "soLuongSanPhamHienCon" => $cotGioHang["soLuongSanPhamHienCon"]
                    );
                }
                $themSanPhamMoi = true;
            } else {
                $gioHangDaCo[] = array(
                    "maSanPham" => $cotGioHang["maSanPham"],
                    "anhSanPham" => $cotGioHang["anhSanPham"],
                    "tenSanPham" => $cotGioHang["tenSanPham"],
                    "soLuong" => $cotGioHang["soLuong"],
                    "donGia" => $cotGioHang["donGia"],
                    "soLuongSanPhamHienCon" => $cotGioHang["soLuongSanPhamHienCon"]
                );
            }
        }

        if (!$themSanPhamMoi) {
            $_SESSION["gioHang"] = array_merge($gioHangDaCo, $gioHangMoi);
        } else {
            $_SESSION["gioHang"] = $gioHangDaCo;
        }
    } else {
        $_SESSION["gioHang"] = $gioHangMoi;
    }

    // Tính tổng số sản phẩm và tổng tiền
    $tongSanPham = 0;
    $tongTien = 0;

    foreach ($_SESSION["gioHang"] as $cotGioHang) {
        $tongSanPham++;
        $tongTien += $cotGioHang["soLuong"] * $cotGioHang["donGia"];
    }

    // Trả về thông báo thành công và HTML của giỏ hàng
    echo "Thêm sản phẩm vào giỏ hàng thành công!|";
?>
<div class="cart box_1">
    <a href="GioHang.php">
        <div class="total">
            <span><?php echo number_format($tongTien, 0, ',', '.'); ?> đ</span>
            (<span id="simpleCart_quantity"><?php echo $tongSanPham; ?></span> Sản phẩm)
        </div>
        <img src="../images/cart-1.png" alt="" />
    </a>
    <p><a href="./SanPham.php?moiGioHang=0" class="simpleCart_empty">Làm mới</a></p>
    <div class="clearfix"></div>
</div>
<?php 
    session_start();

    if (isset($_SESSION["gioHang"]) && isset($_POST["maSanPham"])) {
        $gioHangDaCo = array();

        foreach ($_SESSION["gioHang"] as $cotGioHang) {
            if ($cotGioHang["maSanPham"] != $_POST["maSanPham"]) {
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

        // Kiểm tra xem giỏ hàng có còn sản phẩm nào không
        if (count($gioHangDaCo) > 0) {
            $_SESSION["gioHang"] = $gioHangDaCo;
        } else {
            unset($_SESSION["gioHang"]);
            echo "<script> window.location.href = './SanPham.php'; </script>";
        }
    }

    // Kiểm tra lại nếu giỏ hàng rỗng thì hủy luôn session
    if (!isset($_SESSION["gioHang"]) || !is_array($_SESSION["gioHang"]) || count($_SESSION["gioHang"]) == 0) {
        unset($_SESSION["gioHang"]);
    } else {
?>

<ul class="unit">
    <li><span>Hình ảnh</span></li>
    <li><span>Tên sản phẩm</span></li>		
    <li><span>Số lượng</span></li>
    <li><span>Đơn giá</span></li>
    <li><span>Thành tiền</span></li>
    <div class="clearfix"> </div>
</ul>

<?php foreach ($_SESSION["gioHang"] as $cotGioHang) { ?>
<ul class="cart-header">
    <div class="close1" onclick="xoa_sanPham(<?php echo $cotGioHang['maSanPham']; ?>)"> </div>
    <li class="ring-in">
        <a href="./ChiTietSanPham.php?MaSanPham=<?php echo $cotGioHang["maSanPham"]; ?>">
            <img style="width: 100px;" src="<?php echo $cotGioHang["anhSanPham"]; ?>" class="img-responsive" alt="">
        </a>
    </li>
    <li><span><?php echo $cotGioHang["tenSanPham"]; ?></span></li>
    <li><span>
        <select id="soLuongDat" onchange="capNhat_gioHang(<?php echo $cotGioHang['maSanPham']; ?>, $(this).val())">
            <?php for ($index = 1; $index <= $cotGioHang["soLuongSanPhamHienCon"]; $index++) {
                if ($cotGioHang["soLuong"] == $index) { ?>
                    <option value="<?php echo $index; ?>" selected><?php echo $index; ?></option>
                <?php } else { ?>
                    <option value="<?php echo $index; ?>"><?php echo $index; ?></option>
                <?php } 
            } ?>
        </select>
    </span></li>
    <li><span><?php echo number_format($cotGioHang["donGia"], 0, ',', '.'); ?></span></li>
    <li><span><?php echo number_format($cotGioHang["soLuong"] * $cotGioHang["donGia"], 0, ',', '.'); ?></span></li>
    <div class="clearfix"> </div>
</ul>
<?php } } ?>

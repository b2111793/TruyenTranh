<?php 
    session_start();

    if(isset($_SESSION["gioHang"])){
        foreach($_SESSION["gioHang"] as $cotGioHang){
            if($cotGioHang["maSanPham"] == $_POST["maSanPham"]){
                $gioHangDaCo[] = array(
                    "maSanPham" => $cotGioHang["maSanPham"],
                    "anhSanPham" => $cotGioHang["anhSanPham"],
                    "tenSanPham" => $cotGioHang["tenSanPham"],
                    "soLuong" => $_POST["soLuong"],
                    "donGia" => $cotGioHang["donGia"],
                    "soLuongSanPhamHienCon" => $cotGioHang["soLuongSanPhamHienCon"]
                );
            }
            else{
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
        $_SESSION["gioHang"] = $gioHangDaCo;
    }
?>

<ul class="unit">
    <li><span>Hình ảnh</span></li>
    <li><span>Tên sản phẩm</span></li>		
    <li><span>Số lượng</span></li>
    <li><span>Đơn giá</span></li>
    <li><span>Thành tiền</span></li>
    <div class="clearfix"> </div>
</ul>
<?php foreach($_SESSION["gioHang"] as $cotGioHang){ ?>
<ul class="cart-header">
    <div class="close1"> </div>
        <li class="ring-in"><a href="./ChiTietSanPham.php?MaSanPham=<?php echo $cotGioHang["maSanPham"]; ?>" ><img style="width: 100px;"
        src="<?php echo $cotGioHang["anhSanPham"]; ?>" class="img-responsive" alt=""></a>
        </li>
        <li><span><?php echo $cotGioHang["tenSanPham"]; ?></span></li>
        <li><span>
                <select id="soLuongDat" onchange="capNhat_gioHang(<?php echo $cotGioHang['maSanPham']; ?>, $(this).val())">
                    <?php for($index = 1; $index <= $cotGioHang["soLuongSanPhamHienCon"]; $index++){
                        if($cotGioHang["soLuong"] == $index){
                    ?>
                        <option value="<?php echo $index; ?>" selected><?php echo $index; ?></option>
                    <?php } else{ ?>
                        <option value="<?php echo $index; ?>"><?php echo $index; ?></option>
                    <?php } } ?>
                    
                </select>
            </span></li>
        <li><span><?php echo number_format( $cotGioHang["donGia"], 0, ',', '.'); ?></span></li>
        <li><span><?php echo number_format($cotGioHang["soLuong"] * $cotGioHang["donGia"], 0, ',', '.'); ?></span></li>
    <div class="clearfix"> </div>
</ul>
<?php } ?>
<?php  
    include('../shared/database.php');

    // Lấy tham số lọc từ POST
    $tacGia = isset($_POST["tacGia"]) ? mysqli_real_escape_string($conn, $_POST["tacGia"]) : "";
    $xuatXu = isset($_POST["xuatXu"]) ? mysqli_real_escape_string($conn, $_POST["xuatXu"]) : "";
    $loaiSanPham = isset($_POST["loaiSanPham"]) ? mysqli_real_escape_string($conn, $_POST["loaiSanPham"]) : "";

    // Xây dựng điều kiện lọc
    $dieuKien = "WHERE 1=1";
    if (!empty($tacGia)) {
        $dieuKien .= " AND TacGia = '$tacGia'";
    }
    if (!empty($xuatXu)) {
        $dieuKien .= " AND XuatXu = '$xuatXu'";
    }
    if (!empty($loaiSanPham)) {
        $dieuKien .= " AND MaLoaiSP = '$loaiSanPham'";
    }

    // Truy vấn lấy danh sách sản phẩm
    $laySanPham = "SELECT * FROM sanpham $dieuKien";
    $truyVan_LaySanPham = mysqli_query($conn, $laySanPham);

    // Đếm tổng số sản phẩm
    $tongSoSanPham = mysqli_num_rows($truyVan_LaySanPham);
    if($tongSoSanPham == 0){
        echo "<br><b>Không tìm thấy sản phẩm thỏa các điều kiện!</b>";
        exit;
    }
?>

<div class="clearfix"> </div>
<div id="productContainer">
<?php 
$index = 0;
while ($cot = mysqli_fetch_assoc($truyVan_LaySanPham)) { 
    $index++;
    $maSanPham = $cot["MaSanPham"];
    $isHidden = ($index > 6) ? 'hidden' : '';
?>
    <div class="product-one <?php echo $isHidden; ?>" data-index="<?php echo $index; ?>">
        <div class="col-md-4 product-left single-left"> 
            <div class="p-one simpleCart_shelfItem">
                <a href="./ChiTietSanPham.php?MaSanPham=<?php echo $cot["MaSanPham"]; ?>">
                    <img height="200px" src="<?php echo $cot["Anh"]; ?>" alt="<?php echo $cot["Anh"]; ?>" />
                    <div class="mask mask1">
                        <span>Xem chi tiết</span>
                    </div>
                </a>
                <h4 class="tenSanPham"><?php echo $cot["TenSanPham"]; ?></h4>
                <h5>Tác giả: <?php echo $cot["TacGia"]; ?></h5>
                <p><a class="item_add" href="./GioHang.php" ><i></i>
                    <span class="item_price">
                        <?php echo number_format($cot["DonGia"], 0, ',', '.'); ?> đ 
                    </span></a>
                </p>
                <div class="single-but item_add">
                    <?php if (isset($_SESSION["tenDangNhap"])) { ?>
                        <span> 
                            <i class="far fa-heart heart-icon" data-product-id="<?php echo $maSanPham; ?>"></i>
                        </span>
                        <button 
                            type="button" 
                            class="btn btn-success btn-them-gio-hang"
                            style="<?php echo ($cot["SoLuong"] == 0) ? 'cursor: not-allowed !important; opacity: 0.5;' : ''; ?>" 
                            <?php echo ($cot["SoLuong"] > 0) ? 'onclick="them_gioHang('.$maSanPham.', 1)"' : ''; ?>
                        >
                            Thêm vào giỏ hàng
                        </button>                   
                    <?php } else { ?>
                        <a data-toggle="modal" data-target="#largeModal_dn" href="#" class="btn btn-success btn-them-gio-hang" style="margin: auto;">
                            Thêm vào giỏ hàng
                        </a>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
<?php 
    if ($index % 3 == 0) { ?>
        <div class="clearfix"> </div>
<?php }
} 
?>
</div>

<?php 
if ($tongSoSanPham > 6) { ?>
    <div class="clearfix"> </div>
    <div style="text-align: center; margin-top: 20px;">
        <button id="anBotButton" class="btn btn-secondary" style="display: none;">Ẩn bớt</button>
        <button id="xemTiepButton" class="btn btn-primary">Xem tiếp</button>
    </div>
<?php } ?>
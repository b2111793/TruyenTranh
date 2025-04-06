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
        // Thêm class 'hidden' cho các sản phẩm từ thứ 7 trở đi
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
                <button type="button" class="btn btn-success" style="margin-top: 10px;"
                    onclick="addToCart(<?php echo $cot['MaSanPham']; ?>, 1)">Thêm vào giỏ hàng
                </button>
            </div>
        </div>
    </div>
<?php 
        // Thêm div.clearfix sau mỗi 3 sản phẩm
        if ($index % 3 == 0) { ?>
            <div class="clearfix"> </div>
<?php   }
    } 
?>
</div>

<?php 
    // Hiển thị nút "Xem tiếp" và "Ẩn bớt" nếu có nhiều hơn 6 sản phẩm
    if ($tongSoSanPham > 6) { ?>
        <div class="clearfix"> </div>
        <div style="text-align: center; margin-top: 20px;">
            <button id="anBotButton" class="btn btn-secondary" style="display: none;">Ẩn bớt</button>
            <button id="xemTiepButton" class="btn btn-primary">Xem tiếp</button>
        </div>
<?php } ?>

<script>
    $(document).ready(function() {
        var soSanPhamMoiLanHienThi = 6;
        var soSanPhamHienTai = 6; // Số sản phẩm đã hiển thị ban đầu
        var tongSoSanPham = <?php echo $tongSoSanPham; ?>; // Tổng số sản phẩm
        var soSanPhamBanDau = 6; // Số sản phẩm hiển thị ban đầu

        // Sự kiện nhấn nút "Xem tiếp"
        $("#xemTiepButton").click(function() {
            // Tính số sản phẩm tiếp theo cần hiển thị
            var soSanPhamTiepTheo = Math.min(soSanPhamHienTai + soSanPhamMoiLanHienThi, tongSoSanPham);

            // Hiển thị các sản phẩm từ (soSanPhamHienTai + 1) đến soSanPhamTiepTheo
            for (var i = soSanPhamHienTai + 1; i <= soSanPhamTiepTheo; i++) {
                $(".product-one[data-index='" + i + "']").removeClass("hidden");
            }

            // Cập nhật số sản phẩm hiện tại
            soSanPhamHienTai = soSanPhamTiepTheo;

            // Hiển thị nút "Ẩn bớt" nếu có sản phẩm đã được hiển thị thêm
            if (soSanPhamHienTai > soSanPhamBanDau) {
                $("#anBotButton").show();
            }

            // Ẩn nút "Xem tiếp" nếu đã hiển thị hết sản phẩm
            if (soSanPhamHienTai >= tongSoSanPham) {
                $("#xemTiepButton").hide();
            }
        });

        // Sự kiện nhấn nút "Ẩn bớt"
        $("#anBotButton").click(function() {
            // Ẩn tất cả các sản phẩm từ thứ (soSanPhamBanDau + 1) trở đi
            for (var i = soSanPhamBanDau + 1; i <= tongSoSanPham; i++) {
                $(".product-one[data-index='" + i + "']").addClass("hidden");
            }

            // Cập nhật số sản phẩm hiện tại về trạng thái ban đầu
            soSanPhamHienTai = soSanPhamBanDau;

            // Hiển thị lại nút "Xem tiếp"
            $("#xemTiepButton").show();

            // Ẩn nút "Ẩn bớt"
            $("#anBotButton").hide();
        });
    });
</script>
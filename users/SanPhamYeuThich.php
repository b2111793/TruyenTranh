<?php 
    include('../shared/header.php');
    if (!isset($_SESSION["tenDangNhap"])) {
        echo "<script> window.location.href = '../shared/DangNhap.php'; </script>";
        exit;
    }
    // Kiểm tra trạng thái đăng nhập
    if (isset($_SESSION["tenDangNhap"])) {
        if ($_SESSION["quyen"] == "Admin") {
            echo "<script> window.location.href = '../admin/TrangChuAdmin.php'; </script>";
            exit;
        } 
    }

    $tenDangNhap = mysqli_real_escape_string($conn, $_SESSION["tenDangNhap"]);
    // Lấy danh sách sản phẩm yêu thích
    $layYeuThich = "SELECT yt.*, sp.TenSanPham, sp.DonGia, sp.Anh 
                    FROM yeuthich yt 
                    JOIN sanpham sp ON yt.MaSanPham = sp.MaSanPham 
                    WHERE yt.TenDangNhap = '$tenDangNhap'";
    $truyVan_LayYeuThich = mysqli_query($conn, $layYeuThich);
?>

<!--start-breadcrumbs-->
<div class="breadcrumbs">
    <div class="container">
        <div class="breadcrumbs-main">
            <ol class="breadcrumb">
                <li><a href="./TrangChu.php">Trang chủ</a></li>
                <li class="active">Sản phẩm yêu thích</li>
            </ol>
        </div>
    </div>
</div>
<!--end-breadcrumbs-->

<div class="ckeckout">
    <div class="container">
        <div class="ckeckout-top">
            <div class="cart-items heading">
                <h3>Sản phẩm yêu thích</h3>
                <div class="in-check">
                    <ul class="unit">
                        <li><span>Hình ảnh</span></li>
                        <li><span>Tên sản phẩm</span></li>
                        <li><span>Đơn giá</span></li>
                        <li><span>Hành động</span></li>
                        <div class="clearfix"></div>
                    </ul>
                    <?php 
                    if (mysqli_num_rows($truyVan_LayYeuThich) == 0) {
                        echo "<p>Bạn chưa có sản phẩm yêu thích nào.</p>";
                    } else {
                            $soSanPham = 0;
                            $soSanPhamToiDa = 5;
                        while ($cotYeuThich = mysqli_fetch_assoc($truyVan_LayYeuThich)) { 
                            $soSanPham++;
                            $classAn = ($soSanPham > $soSanPhamToiDa) ? 'anDoiTuong' : '';
                    ?>
                    <ul class="cart-header <?php echo $classAn ?>">
                        <li class="ring-in">
                            <a href="./ChiTietSanPham.php?MaSanPham=<?php echo $cotYeuThich['MaSanPham']; ?>">
                                <img style="width: 100px;" src="<?php echo $cotYeuThich['Anh']; ?>" class="img-responsive" alt="Ảnh sản phẩm">
                            </a>
                        </li>
                        <li><span><?php echo $cotYeuThich['TenSanPham']; ?></span></li>
                        <li><span><?php echo number_format($cotYeuThich['DonGia'], 0, ',', '.'); ?> VNĐ</span></li>
                        <li style="width: auto">
                            <a onclick="them_gioHang(<?php echo $cotYeuThich['MaSanPham']; ?>, 1)" href="#" class="add-cart add-check" 
                                style="padding: 5px 10px;">Thêm vào giỏ hàng</a>
                            <a href="#" class="add-cart add-check" style="padding: 5px 10px; background-color: #ff5555;"
                                onclick="xoa_yeuThich(<?php echo $cotYeuThich['MaSanPham']; ?>)">Xóa
                            </a>
                        </li>
                        <div class="clearfix"></div>
                    </ul>
                    <?php } if ($soSanPham > $soSanPhamToiDa) { ?>
                        <button id="xemThemYeuThich" class="btn btn-primary">Xem thêm</button>
                        <button id="anYeuThich" class="btn btn-primary" style="display: none;">Ẩn bớt</button>
                    <?php } } ?>
                    <script>
                        $(document).ready(function () {
                            $("#xemThemYeuThich").click(function () {
                                $(".anDoiTuong").slideDown(); 
                                $(this).hide();
                                $("#anYeuThich").show(); 
                            });

                            $("#anYeuThich").click(function () {
                                $(".anDoiTuong").slideUp(); 
                                $(this).hide(); 
                                $("#xemThemYeuThich").show(); 
                            });
                        });
                    </script>
                </div>
                <a href="./SanPham.php" class="add-cart add-check" 
                    style="padding: 5px 10px; float: right !important; margin-top: 0">
                    Tiếp tục mua sắm
                </a>
            </div>
        </div>
    </div>
</div>

<?php include('../shared/footer.php'); ?>
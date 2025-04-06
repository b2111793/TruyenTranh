<?php 
    include('../shared/header.php');
    // Kiểm tra trạng thái đăng nhập
    if (isset($_SESSION["tenDangNhap"]) && $_SESSION["quyen"] == "Admin") {
        echo "<script> window.location.href = '../admin/TrangChuAdmin.php'; </script>";
        exit;
    }

    if (!isset($_SESSION["tenDangNhap"])) {
        echo "<script> window.location.href = '../shared/DangNhap.php'; </script>";
        exit;
    }

    if (!isset($_GET['maDonDat'])) {
        $_SESSION['toastr'] = ['type' => 'warning', 'message' => 'Không tìm thấy mã đơn đặt!'];
        echo "<script> window.location.href = './LichSuDonHang.php'; </script>";
        exit;
    }

    $tenDangNhap = mysqli_real_escape_string($conn, $_SESSION["tenDangNhap"]);
    $maDonDat = mysqli_real_escape_string($conn, $_GET['maDonDat']);

    // Kiểm tra đơn hàng có thuộc về người dùng không
    $layDonDat = "SELECT * FROM dondat WHERE MaDonDat = '$maDonDat' AND TenDangNhap = '$tenDangNhap'";
    $truyVan_LayDonDat = mysqli_query($conn, $layDonDat);
    if (mysqli_num_rows($truyVan_LayDonDat) == 0) {
        $_SESSION['toastr'] = ['type' => 'warning', 'message' => 'Đơn đặt không tồn tại hoặc không thuộc về bạn!'];
        echo "<script> window.location.href = './LichSuDonHang.php'; </script>";
        exit;
    }

    $donDat = mysqli_fetch_assoc($truyVan_LayDonDat);

    // Lấy chi tiết đơn hàng
    $layChiTiet = "SELECT ct.*, sp.TenSanPham, sp.DonGia, sp.Anh 
                FROM ct_dondat ct 
                JOIN sanpham sp ON ct.MaSanPham = sp.MaSanPham 
                WHERE ct.MaDonDat = '$maDonDat'";
    $truyVan_LayChiTiet = mysqli_query($conn, $layChiTiet);

    // Lấy thông tin khách hàng
    $layThanhVien = "SELECT * FROM nguoidung WHERE TenDangNhap = '$tenDangNhap'";
    $truyVan_LayThanhVien = mysqli_query($conn, $layThanhVien);
    $cotThanhVien = mysqli_fetch_assoc($truyVan_LayThanhVien);
?>

<!--start-breadcrumbs-->
<div class="breadcrumbs">
    <div class="container">
        <div class="breadcrumbs-main">
            <ol class="breadcrumb">
                <li><a href="./TrangChu.php">Trang chủ</a></li>
                <li><a href="./LichSuDonHang.php">Lịch sử đơn hàng</a></li>
                <li class="active">Chi tiết đơn hàng</li>
            </ol>
        </div>
    </div>
</div>
<!--end-breadcrumbs-->

<div class="ckeckout">
    <div class="container">
        <div class="ckeckout-top">
            <div class="cart-items heading">
                <h3 style="margin-bottom: 20px;">Chi tiết đơn hàng</h3>
                <div>
                    <ul class="unit">
                        <li style="width: 10%"><span>Mã đơn</span></li>
                        <li><span>Khách hàng</span></li>
                        <li><span>Nơi giao</span></li>
                        <li style="width: 15%"><span>Ngày đặt</span></li>
                        <li style="width: 15%"><span>Trạng thái</span></li>
                        <li><span>Tổng tiền</span></li>
                        <div class="clearfix"></div>
                    </ul>
                    <ul class="cart-header">
                        <li style="width: 10%"><span><?php echo $donDat['MaDonDat']; ?></span></li>
                        <li><span><?php echo htmlspecialchars($cotThanhVien['HoTen']); ?></span></li>
                        <li><span><?php echo htmlspecialchars($donDat['NoiGiao']); ?></span></li>
                        <li style="width: 15%"><span><?php echo date("d/m/Y", strtotime($donDat['NgayDat'])); ?></span></li>
                        <li style="width: 15%"><span><?php echo $donDat['TrangThai']; ?></span></li>
                        <li><span>
                            <?php 
                            $tongTien = 0;
                            while ($chiTiet = mysqli_fetch_assoc($truyVan_LayChiTiet)) {
                                $tongTien += $chiTiet['SoLuong'] * $chiTiet['DonGia'];
                            }
                            echo number_format($tongTien, 0, ',', '.'); ?> VNĐ
                            </span>
                        </li>
                        <div class="clearfix" style="height: 100px;"></div>
                    </ul>
                </div>

                <h3 style="margin-top: 20px;">Danh sách sản phẩm</h3>
                <div class="in-check">
                    <ul class="unit">
                        <li><span>Hình ảnh</span></li>
                        <li><span>Tên sản phẩm</span></li>
                        <li><span>Số lượng</span></li>
                        <li><span>Đơn giá</span></li>
                        <li><span>Thành tiền</span></li>
                        <div class="clearfix"></div>
                    </ul>
                    <?php 
                    // Reset con trỏ để sử dụng lại kết quả truy vấn
                    mysqli_data_seek($truyVan_LayChiTiet, 0);
                    while ($chiTiet = mysqli_fetch_assoc($truyVan_LayChiTiet)) { 
                        $thanhTien = $chiTiet['SoLuong'] * $chiTiet['DonGia'];
                    ?>
                    <ul class="cart-header">
                        <li class="ring-in">
                            <a href="./ChiTietSanPham.php?MaSanPham=<?php echo $chiTiet["MaSanPham"]; ?>">
                                <img style="height: 132px" src="<?php echo $chiTiet['Anh']; ?>" class="img-responsive" alt="">
                            </a>
                        </li>
                        <li><span><?php echo $chiTiet['TenSanPham']; ?></span></li>
                        <li><span><?php echo $chiTiet['SoLuong']; ?></span></li>
                        <li><span><?php echo number_format($chiTiet['DonGia'], 0, ',', '.'); ?> VNĐ</span></li>
                        <li><span><?php echo number_format($thanhTien, 0, ',', '.'); ?> VNĐ</span></li>
                        <div class="clearfix"></div>
                    </ul>
                    <?php } ?>
                </div>
                <a href="./LichSuDonHang.php" class="add-cart add-check" 
                    style="padding: 5px 10px; float: right !important; margin-top: 0">
                    Quay lại
                </a>
            </div>
        </div>
    </div>
</div>

<?php include('../shared/footer.php'); ?>
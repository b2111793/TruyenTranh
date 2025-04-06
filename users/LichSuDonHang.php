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

    $tenDangNhap = mysqli_real_escape_string($conn, $_SESSION["tenDangNhap"]);

    // Lấy trạng thái từ query string (nếu có)
    $trangThaiLoc = isset($_GET['trangThai']) ? mysqli_real_escape_string($conn, $_GET['trangThai']) : 'all';

    // Lấy danh sách trạng thái 
    $layTrangThai = "SELECT DISTINCT TrangThai FROM dondat WHERE TenDangNhap = '$tenDangNhap'";
    $truyVan_LayTrangThai = mysqli_query($conn, $layTrangThai);
    $danhSachTrangThai = [];
    while ($row = mysqli_fetch_assoc($truyVan_LayTrangThai)) {
        $danhSachTrangThai[] = $row['TrangThai'];
    }

    // Lấy danh sách đơn hàng của người dùng
    $layDonDat = "SELECT * FROM dondat WHERE TenDangNhap = '$tenDangNhap'";
    if ($trangThaiLoc !== 'all') {
        $layDonDat .= " AND TrangThai = '$trangThaiLoc'";
    }
    $layDonDat .= " ORDER BY NgayDat DESC";
    $truyVan_LayDonDat = mysqli_query($conn, $layDonDat);

    // Lưu danh sách đơn hàng vào mảng để đếm số lượng
    $danhSachDonHang = [];
    while ($donDat = mysqli_fetch_assoc($truyVan_LayDonDat)) {
        $danhSachDonHang[] = $donDat;
    }
    $soDonHang = count($danhSachDonHang);
    $soDonHangToiDa = 5;
?>

<!--start-breadcrumbs-->
<div class="breadcrumbs">
    <div class="container">
        <div class="breadcrumbs-main">
            <ol class="breadcrumb">
                <li><a href="./TrangChu.php">Trang chủ</a></li>
                <li class="active">Danh sách đơn hàng</li>
            </ol>
        </div>
    </div>
</div>
<!--end-breadcrumbs-->

<div class="ckeckout">
    <div class="container">
        <div class="ckeckout-top">
            <div class="cart-items heading">
                <h3>Lịch sử đơn hàng</h3>
                <!-- Dropdown lọc trạng thái -->
                <div class="filter-section" style="margin-bottom: 20px; text-align: right;">
                    <form method="GET" action="">
                        <label for="trangThai">Lọc theo trạng thái: </label>
                        <select name="trangThai" id="trangThai" onchange="this.form.submit()">
                            <option value="all" <?php echo $trangThaiLoc == 'all' ? 'selected' : ''; ?>>Tất cả</option>
                            <?php foreach ($danhSachTrangThai as $trangThai) { ?>
                                <option value="<?php echo htmlspecialchars($trangThai); ?>" 
                                    <?php echo $trangThaiLoc == $trangThai ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($trangThai); ?>
                                </option>
                            <?php } ?>
                        </select>
                    </form>
                </div>
                <div class="in-check">
                    <ul class="unit">
                        <li><span>Mã đơn đặt</span></li>
                        <li><span>Ngày đặt</span></li>
                        <li><span>Trạng thái</span></li>
                        <li><span>Tổng tiền</span></li>
                        <li><span>Hành động</span></li>
                        <div class="clearfix"></div>
                    </ul>
                    <?php 
                    if ($soDonHang == 0) {
                        echo "<p>Bạn chưa có đơn hàng nào" . ($trangThaiLoc != 'all' ? " với trạng thái '$trangThaiLoc'." : ".") . "</p>";
                    } else {
                        $index = 0;
                        foreach ($danhSachDonHang as $donDat) {
                            $index++;
                            $classAn = ($index > $soDonHangToiDa) ? 'anDoiTuong' : '';

                            $maDonDat = intval($donDat['MaDonDat']);
                            $layChiTiet = "SELECT ct.*, sp.DonGia 
                                           FROM ct_dondat ct 
                                           JOIN sanpham sp 
                                           ON ct.MaSanPham = sp.MaSanPham 
                                           WHERE ct.MaDonDat = '$maDonDat'";
                            $truyVan_LayChiTiet = mysqli_query($conn, $layChiTiet);

                            $tongTien = 0;
                            while ($chiTiet = mysqli_fetch_assoc($truyVan_LayChiTiet)) {
                                $tongTien += $chiTiet['SoLuong'] * $chiTiet['DonGia'];
                            }
                    ?>
                    <ul class="cart-header <?php echo $classAn ?>" style="padding: 0 14px;" data-trangthai="<?php echo htmlspecialchars($donDat['TrangThai']); ?>">
                        <li><span><?php echo $donDat['MaDonDat']; ?></span></li>
                        <li><span><?php echo date("d/m/Y", strtotime($donDat['NgayDat'])); ?></span></li>
                        <li><span><?php echo $donDat['TrangThai']; ?></span></li>
                        <li><span><?php echo number_format($tongTien, 0, ',', '.'); ?> VNĐ</span></li>
                        <li style="text-align: center;">
                            <a href="./ChiTietDonHang.php?maDonDat=<?php echo $donDat['MaDonDat']; ?>" 
                                class="add-cart add-check" style="padding: 5px 10px;">Xem chi tiết
                            </a>
                        </li>
                        <div class="clearfix" style="height: 100px;"></div>
                    </ul>
                    <?php } if ($soDonHang > $soDonHangToiDa) { ?>
                        <button id="xemThemDonHang" class="btn btn-primary">Xem thêm</button>
                        <button id="anDonHang" class="btn btn-primary" style="display: none;">Ẩn bớt</button>
                    <?php } } ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* CSS tối thiểu cho dropdown lọc */
    .filter-section select {
        padding: 5px;
        font-size: 14px;
        border: 1px solid #ccc;
        border-radius: 4px;
        background-color: #fff;
        cursor: pointer;
    }

    .filter-section label {
        margin-right: 10px;
        font-size: 14px;
    }

    /* Responsive: Điều chỉnh trên màn hình nhỏ */
    @media (max-width: 768px) {
        .filter-section {
            text-align: center !important;
        }
    }
</style>

<script>
    $(document).ready(function () {
        // Xử lý "Xem thêm" và "Ẩn bớt"
        $("#xemThemDonHang").click(function () {
            $(".anDoiTuong").slideDown(); 
            $(this).hide();
            $("#anDonHang").show(); 
        });

        $("#anDonHang").click(function () {
            $(".anDoiTuong").slideUp(); 
            $(this).hide(); 
            $("#xemThemDonHang").show(); 
        });
    });
</script>

<?php include('../shared/footer.php'); ?>
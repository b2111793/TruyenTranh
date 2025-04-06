<?php 
    include('../shared/header.php');
    // Kiểm tra trạng thái đăng nhập
    if (isset($_SESSION["tenDangNhap"]) && $_SESSION["quyen"] == "Admin") {
        echo "<script> window.location.href = '../admin/TrangChuAdmin.php'; </script>";
        exit;
    }

    // Kiểm tra đăng nhập
    if (!isset($_SESSION["tenDangNhap"])) {
        echo "<script> window.location.href = '../shared/DangNhap.php'; </script>";
        exit;
    }

    // Kiểm tra mã đơn đặt
    if (!isset($_SESSION["maDonDat"]) || !isset($_GET['maDonDat']) || $_SESSION["maDonDat"] != (int)$_GET['maDonDat']) {
        $_SESSION['toastr'] = ['type' => 'warning', 'message' => 'Không tìm thấy đơn đặt hàng!'];
        echo "<script> window.location.href = './GioHang.php'; </script>";
        exit;
    }
    $maDonDat = intval($_GET['maDonDat']);
    // Tính tổng tiền từ ct_dondat
    $layChiTiet =  "SELECT ct.*, sp.DonGia 
                    FROM ct_dondat ct 
                    JOIN sanpham sp 
                    ON ct.MaSanPham = sp.MaSanPham 
                    WHERE ct.MaDonDat = '$maDonDat'";
    $truyVan_LayChiTiet = mysqli_query($conn, $layChiTiet);

    $tongTienGioHang = 0;
    while ($chiTiet = mysqli_fetch_assoc($truyVan_LayChiTiet)) {
        $tongTienGioHang += $chiTiet['SoLuong'] * $chiTiet['DonGia'];
    }

    $maDonDat = $_GET['maDonDat'];

    // Giả lập thanh toán thành công
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['xacNhanMomo'])) {
        // Cập nhật trạng thái đơn đặt
        $capNhatDonDat = "UPDATE dondat SET TrangThai = 'Đã thanh toán' WHERE MaDonDat = '$maDonDat'";
        mysqli_query($conn, $capNhatDonDat);

        // Xóa giỏ hàng và session thanh toán
        unset($_SESSION["gioHang"]);
        unset($_SESSION["maDonDat"]);
        $_SESSION['toastr'] = ['type' => 'success', 'message' => 'Thanh toán qua Momo thành công! Mã đơn đặt: ' . $maDonDat];
        echo "<script> window.location.href = './SanPham.php'; </script>";
        exit;
    }
?>

<!--start-breadcrumbs-->
<div class="breadcrumbs">
    <div class="container">
        <div class="breadcrumbs-main">
            <ol class="breadcrumb">
                <li><a href="./TrangChu.php">Trang chủ</a></li>
                <li><a href="./GioHang.php">Giỏ hàng</a></li>
                <li><a href="./ThanhToan.php">Thanh toán</a></li>
                <li class="active">Thanh toán Momo</li>
            </ol>
        </div>
    </div>
</div>
<!--end-breadcrumbs-->

<!--start-ckeckout-->
<div class="ckeckout">
    <div class="container">
        <div class="ckeckout-top">
            <div class="cart-items heading">
                <h3>Thanh toán qua Momo</h3>
                <div style="text-align: center; padding: 20px;">
                    <p><strong>Mã đơn đặt:</strong> <?php echo $maDonDat; ?></p>
                    <p><strong>Tổng tiền:</strong> <?php echo number_format($tongTienGioHang, 0, ',', '.'); ?>đ</p>
                    <p>Vui lòng quét mã QR dưới đây để thanh toán qua Momo:</p>
                    <img src="../images/momo_qr.png" alt="Momo QR Code" style="width: 200px; height: 200px;" />
                    <p>(Hình ảnh QR chỉ mang tính chất minh họa)</p>
                    <form method="POST" action="">
                        <a  href="./GioHang.php"><button class="btn btn-dark">Hủy</button></a>
                        <button type="submit" name="xacNhanMomo" class="btn btn-primary">Xác nhận thanh toán</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!--end-ckeckout-->

<?php include('../shared/footer.php'); ?>
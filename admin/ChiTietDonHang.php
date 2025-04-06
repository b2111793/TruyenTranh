<?php
    include('../shared/headerAdmin.php');
    if(!isset($_SESSION["tenDangNhap"])){
        echo "<script> window.location.href = '../shared/DangNhap.php'; </script>";
        exit;
    }
    if(isset($_SESSION["tenDangNhap"]) && $_SESSION["quyen"] == "Member"){
        echo "<script> window.location.href = '../users/TrangChu.php'; </script>";
        exit;
    }

    if (!isset($_GET["maDonDat"])) {
        $_SESSION['toastr'] = ['type' => 'warning', 'message' => 'Không tìm thấy mã đơn đặt!'];
        echo "<script> window.location.href = './QuanLyDonHang.php';</script>";
        exit;
    }

    $maDonDat = mysqli_real_escape_string($conn, $_GET["maDonDat"]);

    // Lấy thông tin đơn đặt
    $layDonDat = "SELECT dd.*, tv.HoTen, tv.DienThoai, tv.Email, tv.DiaChi 
                FROM dondat dd 
                JOIN nguoidung tv ON dd.TenDangNhap = tv.TenDangNhap 
                WHERE dd.MaDonDat = '$maDonDat'";
    $truyVan_LayDonDat = mysqli_query($conn, $layDonDat);
    if (mysqli_num_rows($truyVan_LayDonDat) == 0) {
        $_SESSION['toastr'] = ['type' => 'warning', 'message' => 'Đơn đặt không tồn tại!'];
        echo "<script> window.location.href = './QuanLyDonHang.php';</script>";
        exit;
    }
    $donDat = mysqli_fetch_assoc($truyVan_LayDonDat);

    // Lấy chi tiết đơn đặt
    $layChiTiet = "SELECT ct.*, sp.TenSanPham, sp.DonGia, sp.Anh 
                FROM ct_dondat ct 
                JOIN sanpham sp ON ct.MaSanPham = sp.MaSanPham 
                WHERE ct.MaDonDat = '$maDonDat'";
    $truyVan_LayChiTiet = mysqli_query($conn, $layChiTiet);
?>

<main>
    <div class="container-fluid px-4">
        <h1 class="mt-4">Chi tiết đơn đặt</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="./TrangChuAdmin.php">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="./QuanLyDonHang.php">Quản lý đơn đặt</a></li>
            <li class="breadcrumb-item active">Chi tiết đơn đặt</li>
        </ol>
        <div class="card mb-4">
            <div class="card-body">
                <h5>Thông tin đơn đặt</h5>
                <p><strong>Mã đơn đặt:</strong> <?php echo $donDat['MaDonDat']; ?></p>
                <p><strong>Khách hàng:</strong> <?php echo $donDat['HoTen']; ?></p>
                <p><strong>Số điện thoại:</strong> <?php echo $donDat['DienThoai']; ?></p>
                <p><strong>Email:</strong> <?php echo $donDat['Email']; ?></p>
                <p><strong>Địa chỉ giao:</strong> <?php echo $donDat['NoiGiao']; ?></p>
                <p><strong>Ngày đặt:</strong> <?php echo $donDat['NgayDat']; ?></p>
                <p><strong>Trạng thái:</strong> <?php echo $donDat['TrangThai']; ?></p>

                <h5>Danh sách sản phẩm</h5>
                <table class="table table-bordered" id="duLieuBang">
                    <thead>
                        <tr>
                            <th>Ảnh</th>
                            <th>Tên sản phẩm</th>
                            <th>Số lượng</th>
                            <th>Đơn giá</th>
                            <th>Thành tiền</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $tongTien = 0;
                        while ($chiTiet = mysqli_fetch_assoc($truyVan_LayChiTiet)) { 
                            $thanhTien = $chiTiet['SoLuong'] * $chiTiet['DonGia'];
                            $tongTien += $thanhTien;
                        ?>
                            <tr>
                                <td class="duLieuSo"><img src="<?php echo $chiTiet['Anh']; ?>" alt="Ảnh sản phẩm" style="max-width: 100px;"></td>
                                <td><?php echo $chiTiet['TenSanPham']; ?></td>
                                <td class="duLieuSo"><?php echo $chiTiet['SoLuong']; ?></td>
                                <td class="duLieuSo"><?php echo number_format($chiTiet['DonGia'], 0, ',', '.'); ?> VNĐ</td>
                                <td class="duLieuSo"><?php echo number_format($thanhTien, 0, ',', '.'); ?> VNĐ</td>
                            </tr>
                        <?php } ?>
                        <tr>
                            <td colspan="4" class="text-end"><strong>Tổng tiền:</strong></td>
                            <td class="duLieuSo"><?php echo number_format($tongTien, 0, ',', '.'); ?> VNĐ</td>
                        </tr>
                    </tbody>
                </table>
                <a href="./QuanLyDonHang.php" class="btn btn-secondary">Quay lại</a>
            </div>
        </div>
    </div>
</main>

<?php include('../shared/footerAdmin.php'); ?>
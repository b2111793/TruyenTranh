<?php
    include('../shared/headerAdmin.php');
    if (!isset($_SESSION["tenDangNhap"])) {
        echo "<script> window.location.href = '../shared/DangNhap.php'; </script>";
        exit;
    }

    if (isset($_SESSION["tenDangNhap"]) && $_SESSION["quyen"] == "Member") {
        echo "<script> window.location.href = '../users/TrangChu.php'; </script>";
        exit;
    }

    // --- Nhóm 1: Thống kê cho các chức năng quản lý ---

    // Quản lý danh mục 
    $layDanhMuc = "SELECT COUNT(*) AS TongDanhMuc FROM loaisp";
    $truyVan_LayDanhMuc = mysqli_query($conn, $layDanhMuc);
    $tongDanhMuc = mysqli_fetch_assoc($truyVan_LayDanhMuc)["TongDanhMuc"] ?? 0;

    // Quản lý sản phẩm
    $laySanPham = "SELECT COUNT(*) AS TongSanPham FROM sanpham";
    $truyVan_LaySanPham = mysqli_query($conn, $laySanPham);
    $tongSanPham = mysqli_fetch_assoc($truyVan_LaySanPham)['TongSanPham'] ?? 0;

    // Quản lý thành viên
    $layThanhVien = "SELECT COUNT(*) AS TongThanhVien FROM nguoidung";
    $truyVan_LayThanhVien = mysqli_query($conn, $layThanhVien);
    $tongThanhVien = mysqli_fetch_assoc($truyVan_LayThanhVien)['TongThanhVien']-1 ?? 0;

    // Quản lý đơn hàng
    $layDonHang = "SELECT COUNT(*) AS TongDonHang FROM dondat";
    $truyVan_LayDonHang = mysqli_query($conn, $layDonHang);
    $tongDonHang = mysqli_fetch_assoc($truyVan_LayDonHang)['TongDonHang'] ?? 0;

    // Quản lý bình luận 
    $layBinhLuan = "SELECT COUNT(*) AS TongBinhLuan FROM binhluan";
    $truyVan_LayBinhLuan = mysqli_query($conn, $layBinhLuan);
    $tongBinhLuan = mysqli_fetch_assoc($truyVan_LayBinhLuan)['TongBinhLuan'] ?? 0;

    // --- Nhóm 2: Thống kê cho các chức năng thống kê ---

    // Tổng doanh thu (đơn hàng đã giao)
    $layTongDoanhThu = "SELECT SUM(ct.SoLuong * sp.DonGia) AS TongDoanhThu 
                        FROM dondat dd 
                        JOIN ct_dondat ct ON dd.MaDonDat = ct.MaDonDat 
                        JOIN sanpham sp ON ct.MaSanPham = sp.MaSanPham 
                        WHERE dd.TrangThai = 'Đã giao'";
    $truyVan_TongDoanhThu = mysqli_query($conn, $layTongDoanhThu);
    $tongDoanhThu = mysqli_fetch_assoc($truyVan_TongDoanhThu)['TongDoanhThu'] ?? 0;

    // Sản phẩm bán chạy (dựa trên số lượng bán được trong các đơn hàng đã giao)
    $laySanPhamBanChay =   "SELECT sp.TenSanPham, sp.MaSanPham, SUM(ct.SoLuong) AS TongSoLuong 
                            FROM ct_dondat ct 
                            JOIN dondat dd ON ct.MaDonDat = dd.MaDonDat 
                            JOIN sanpham sp ON ct.MaSanPham = sp.MaSanPham 
                            WHERE dd.TrangThai = 'Đã giao' 
                            GROUP BY ct.MaSanPham, sp.TenSanPham 
                            ORDER BY TongSoLuong DESC 
                            LIMIT 1";
    $truyVan_SanPhamBanChay = mysqli_query($conn, $laySanPhamBanChay);
    $sanPhamBanChay = mysqli_fetch_assoc($truyVan_SanPhamBanChay);
    $tenSanPhamBanChay = $sanPhamBanChay['TenSanPham'] ?? 'Chưa có';
    $tongSoLuongBanChay = $sanPhamBanChay['TongSoLuong'] ?? 0;

    // Thống kê đánh giá
    $layDanhGia = "SELECT COUNT(*) AS TongDanhGia FROM danhgia";
    $truyVan_LayDanhGia = mysqli_query($conn, $layDanhGia);
    $tongDanhGia = mysqli_fetch_assoc($truyVan_LayDanhGia)['TongDanhGia'] ?? 0;

    // Thống kê yêu thích
    $layYeuThich = "SELECT COUNT(*) AS TongYeuThich FROM yeuthich";
    $truyVan_LayYeuThich = mysqli_query($conn, $layYeuThich);
    $tongYeuThich = mysqli_fetch_assoc($truyVan_LayYeuThich)['TongYeuThich'] ?? 0;
?>

<main>
    <div class="container-fluid px-4">
        <h1 class="mt-4">Trang chủ</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">Tổng quan</li>
        </ol>
        <!-- Thống kê sản phẩm bán chạy -->
        <div class="col-xl-12 col-md-12">
                <div class="card text-white mb-4 san-pham-ban-chay-nhat">
                    <div class="card-body">
                        <h5><i class="fas fa-fire me-2"></i>Sản phẩm bán chạy nhất</h5>
                        <h3 class="mt-2"><?php echo $tenSanPhamBanChay; ?></h3>
                        <small>(Đã bán: <?php echo number_format($tongSoLuongBanChay, 0, ',', '.'); ?>)</small>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <?php if($tongSoLuongBanChay > 0){ 
                                $maSanPham = $sanPhamBanChay['MaSanPham'];
                        ?>
                        <a class="small text-white stretched-link" 
                            href="./ChiTietSanPhamAdmin.php?maSanPham=<?php echo $maSanPham; ?>">Xem chi tiết
                        </a>
                        <?php } ?>
                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                    </div>
                </div>
            </div>
        <!-- Nhóm 1: Thẻ thống kê cho các chức năng quản lý -->
        <div class="row">
            <!-- Quản lý danh mục -->
            <div class="col-xl-3 col-md-6">
                <div class="card text-white mb-4 quan-ly-danh-muc">
                    <div class="card-body">
                       <h5><i class="fas fa-tags me-2"></i>Quản lý danh mục</h5>
                        <p class="mt-2">Số thể loại hiện tại: <?php echo number_format($tongDanhMuc, 0, ',', '.'); ?></p>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="./QuanLyDanhMuc.php">Xem chi tiết</a>
                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                    </div>
                </div>
            </div>

            <!-- Quản lý sản phẩm -->
            <div class="col-xl-3 col-md-6">
                <div class="card text-white mb-4 quan-ly-san-pham">
                    <div class="card-body">
                       <h5><i class="fas fa-box me-2"></i>Quản lý sản phẩm</h5> 
                        <p class="mt-2">Số sản phẩm hiện tại: <?php echo number_format($tongSanPham, 0, ',', '.'); ?></p>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="./QuanLySanPham.php">Xem chi tiết</a>
                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                    </div>
                </div>
            </div>

            <!-- Quản lý thành viên -->
            <div class="col-xl-3 col-md-6">
                <div class="card text-white mb-4 quan-ly-thanh-vien">
                    <div class="card-body">
                        <h5><i class="fas fa-users me-2"></i>Quản lý thành viên</h5>
                        <p class="mt-2">Tổng số thành viên: <?php echo number_format($tongThanhVien, 0, ',', '.'); ?></p>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="./QuanLyThanhVien.php">Xem chi tiết</a>
                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                    </div>
                </div>
            </div>

            <!-- Quản lý đơn hàng -->
            <div class="col-xl-3 col-md-6">
                <div class="card text-white mb-4 quan-ly-don-hang">
                    <div class="card-body">
                        <h5><i class="fas fa-shopping-cart me-2"></i>Quản lý đơn hàng</h5>
                        <p class="mt-2">Số đơn hàng hiện tại: <?php echo number_format($tongDonHang, 0, ',', '.'); ?></p>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="./QuanLyDonHang.php">Xem chi tiết</a>
                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                    </div>
                </div>
            </div>

            <!-- Quản lý bình luận -->
            <div class="col-xl-3 col-md-6">
                <div class="card text-white mb-4 quan-ly-binh-luan">
                    <div class="card-body">
                        <h5><i class="fas fa-comments me-2"></i>Quản lý bình luận</h5>
                        <p class="mt-2">Số bình luận hiện tại: <?php echo number_format($tongBinhLuan, 0, ',', '.'); ?></p>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="./QuanLyBinhLuan.php">Xem chi tiết</a>
                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                    </div>
                </div>
            </div>
      
        <!-- Nhóm 2: Thẻ thống kê cho các chức năng thống kê -->
       
            <!-- Thống kê doanh thu -->
            <div class="col-xl-3 col-md-6">
                <div class="card text-white mb-4 thong-ke-doanh-thu">
                    <div class="card-body">
                        <h5><i class="fas fa-dollar-sign me-2"></i>Thống kê doanh thu</h5>
                        <p class="mt-2">Doanh thu hiện tại: <?php echo number_format($tongDoanhThu, 0, ',', '.'); ?> VNĐ</p>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="./ThongKeDoanhThu.php">Xem chi tiết</a>
                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                    </div>
                </div>
            </div>

             <!-- Thống kê đánh giá -->
             <div class="col-xl-3 col-md-6">
                <div class="card text-white mb-4 thong-ke-danh-gia">
                    <div class="card-body">
                        <h5><i class="fas fa-star me-2"></i>Thống kê đánh giá</h5>
                        <p class="mt-2">Tổng số đánh giá: <?php echo number_format($tongDanhGia, 0, ',', '.'); ?><p>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="./ThongKeDanhGia.php">Xem chi tiết</a>
                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                    </div>
                </div>
            </div>

            <!-- Thống kê yêu thích -->
            <div class="col-xl-3 col-md-6">
                <div class="card text-white mb-4 thong-ke-yeu-thich">
                    <div class="card-body">
                        <h5><i class="fas fa-heart me-2"></i>Thống kê yêu thích</h5>
                        <p class="mt-2">Tổng số yêu thích: <?php echo number_format($tongYeuThich, 0, ',', '.'); ?></p>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="./ThongKeYeuThich.php">Xem chi tiết</a>
                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include('../shared/footerAdmin.php'); ?>
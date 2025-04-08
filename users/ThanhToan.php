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

    // Kiểm tra giỏ hàng tồn tại và không rỗng
    if (!isset($_SESSION["gioHang"]) || empty($_SESSION["gioHang"])) {
        echo "<script>window.location.href = './TrangChu.php';</script>";
        exit;
    }

    // Kiểm tra hình thức thanh toán
    if (!isset($_GET['hinhThuc']) || !in_array($_GET['hinhThuc'], ['COD', 'Momo'])) {
        $_SESSION['toastr'] = ['type' => 'warning', 'message' => 'Hình thức thanh toán không hợp lệ!'];
        echo "<script> window.location.href = './GioHang.php'; </script>";
        exit;
    }
    $hinhThucThanhToan = $_GET['hinhThuc'];

    // Lấy thông tin khách hàng
    $tenDangNhap = mysqli_real_escape_string($conn, $_SESSION["tenDangNhap"]);
    $layThanhVien = "SELECT * FROM nguoidung WHERE TenDangNhap = '$tenDangNhap'";
    $truyVan_LayThanhVien = mysqli_query($conn, $layThanhVien);
    $cotThanhVien = mysqli_fetch_assoc($truyVan_LayThanhVien);

    // Tính tổng tiền và tổng sản phẩm
    $tongTienGioHang = 0;
    foreach ($_SESSION["gioHang"] as $cotGioHang) {
        $tongTienGioHang += $cotGioHang["soLuong"] * $cotGioHang["donGia"];
    }

    // Phí ship 30.000đ cho mỗi đơn, 0đ đối với đơn > 300.000đ
    $phiShip = ($tongTienGioHang > 300000) ? 0 : 30000;

    // Xử lý đặt hàng
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['datHang'])) {
        // Kiểm tra số lượng tồn kho
        $errors = [];
        foreach ($_SESSION["gioHang"] as $cotGioHang) {
            $maSanPham = intval($cotGioHang['maSanPham']);
            $soLuongDat = (int)$cotGioHang['soLuong'];

            $laySanPham = "SELECT SoLuong, TenSanPham FROM sanpham WHERE MaSanPham = '$maSanPham'";
            $truyVan_LaySanPham = mysqli_query($conn, $laySanPham);
            $sanPham = mysqli_fetch_assoc($truyVan_LaySanPham);

            if ($soLuongDat > $sanPham['SoLuong']) {
                $errors[] = "Sản phẩm {$sanPham['TenSanPham']} không đủ số lượng tồn kho (hiện có: {$sanPham['SoLuong']})!";
            }
        }

        if (!empty($errors)) {
            $errorMessage = implode("\\n", $errors);
            $_SESSION['toastr'] = ['type' => 'error', 'message' => "$errorMessage"];
            echo "<script> window.location.href = './GioHang.php'; </script>";
            exit;
        }

        // Lấy thông tin giao hàng từ form
        $noiGiao = mysqli_real_escape_string($conn, $_POST["noiGiao"]);
        $dienThoai = mysqli_real_escape_string($conn, $_POST["dienThoai"]);

        // Kiểm tra thông tin giao hàng
        if (empty($noiGiao) || empty($dienThoai)) {
            $_SESSION['toastr'] = ['type' => 'warning', 'message' => 'Vui lòng nhập đầy đủ thông tin giao hàng!'];
            echo "<script> window.location.href = './ThanhToan.php'; </script>";
            exit;
        } else {
            // Tạo đơn đặt
            $trangThai = ($hinhThucThanhToan == 'COD') ? 'Chờ xử lý' : 'Chờ thanh toán'; // Trạng thái ban đầu
            $ngayDat = date("Y-m-d");

            $themDonDat = "INSERT INTO dondat (TenDangNhap, TrangThai, NoiGiao, NgayDat, HinhThucThanhToan) 
                        VALUES ('$tenDangNhap', '$trangThai', '$noiGiao', '$ngayDat', '$hinhThucThanhToan')";
            
            if (mysqli_query($conn, $themDonDat)) {
                $maDonDat = mysqli_insert_id($conn); // Lấy MaDonDat ngay sau khi thêm

                // Thêm chi tiết đơn đặt và cập nhật số lượng tồn kho
                foreach ($_SESSION["gioHang"] as $cotGioHang) {
                    $maSanPham = intval($cotGioHang['maSanPham']);
                    $soLuong = (int)$cotGioHang['soLuong'];

                    // Thêm vào ct_dondat
                    $themChiTietDonDat = "INSERT INTO ct_dondat (MaDonDat, MaSanPham, SoLuong) 
                                        VALUES ('$maDonDat', '$maSanPham', '$soLuong')";
                    mysqli_query($conn, $themChiTietDonDat);

                    // Cập nhật số lượng tồn kho
                    $capNhatTonKho = "UPDATE sanpham 
                                    SET SoLuong = SoLuong - $soLuong 
                                    WHERE MaSanPham = '$maSanPham'";
                    mysqli_query($conn, $capNhatTonKho);
                }

                // Xử lý tùy theo hình thức thanh toán
                if ($hinhThucThanhToan == 'COD') {
                    // Thanh toán khi nhận hàng
                    unset($_SESSION["gioHang"]);
                    $_SESSION['toastr'] = ['type' => 'success', 'message' => 'Đã đặt hàng thành công! Mã đơn đặt: ' . $maDonDat];
                    echo "<script> window.location.href = './SanPham.php'; </script>";
                    exit;
                } else {
                    // Thanh toán online (Momo)
                    // Lưu mã đơn đặt vào session để xử lý sau khi thanh toán online
                    $_SESSION["maDonDat"] = (int)$maDonDat;
                    if ($hinhThucThanhToan == 'Momo') {
                        $_SESSION['toastr'] = ['type' => 'info', 'message' => 'Chuyển hướng đến cổng thanh toán Momo...'];
                        $redirectUrl = "./ThanhToanMomo.php?maDonDat=" . $maDonDat;
                        echo "<script> window.location.href = '$redirectUrl'; </script>";
                        exit;
                    } 
                }
            } else {
                $_SESSION['toastr'] = ['type' => 'error', 'message' => 'Đã xảy ra lỗi khi đặt hàng: ' . mysqli_error($conn)];
                echo "<script> window.location.href = './ThanhToan.php'; </script>";
                exit;
            }
        }
    }
?>

<!--start-breadcrumbs-->
<div class="breadcrumbs">
    <div class="container">
        <div class="breadcrumbs-main">
            <ol class="breadcrumb">
                <li><a href="./TrangChu.php">Trang chủ</a></li>
                <li><a href="./GioHang.php">Giỏ hàng</a></li>
                <li class="active">Thanh toán</li>
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
                <h3>Thanh toán</h3>

                <!-- Hiển thị hình thức thanh toán -->
                <div style="margin-bottom: 20px;">
                    <h4>Hình thức thanh toán: 
                        <?php echo ($hinhThucThanhToan == 'COD') ? "Thanh toán khi nhận hàng (COD)" : "Thanh toán qua Momo"; ?>
                    </h4>
                </div>

                <!-- Hiển thị danh sách sản phẩm trong giỏ hàng -->
                <div class="in-check">
                    <h4><b>Danh sách sản phẩm</b></h4>
                    <ul class="unit">
                        <li><span>Hình ảnh</span></li>
                        <li><span>Tên sản phẩm</span></li>        
                        <li><span>Số lượng</span></li>
                        <li><span>Đơn giá</span></li>
                        <li><span>Thành tiền</span></li>
                        <div class="clearfix"></div>
                    </ul>
                    <?php 
                    foreach ($_SESSION["gioHang"] as $cotGioHang) { 
                        $thanhTien = $cotGioHang["soLuong"] * $cotGioHang["donGia"];
                    ?>
                    <ul class="cart-header">
                        <li class="ring-in">
                            <a href="ChiTietSanPham.php?MaSanPham=<?php echo $cotGioHang['maSanPham']; ?>">
                                <img style="width: 100px;" src="<?php echo $cotGioHang['anhSanPham']; ?>" class="img-responsive" alt="">
                            </a>
                        </li>
                        <li><span><?php echo $cotGioHang['tenSanPham']; ?></span></li>
                        <li><span><?php echo $cotGioHang['soLuong']; ?></span></li>
                        <li><span><?php echo number_format($cotGioHang['donGia'], 0, ',', '.'); ?>đ</span></li>
                        <li><span><?php echo number_format($thanhTien, 0, ',', '.'); ?>đ</span></li>
                        <div class="clearfix"></div>
                    </ul>
                    <?php } ?>
                </div> 

                <!-- Hiển thị thông tin giao hàng -->
                <div style="margin-top: 30px;">
                    <h4><b>Thông tin giao hàng</b></h4>
                    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?hinhThuc=' . $hinhThucThanhToan; ?>">
                        <ul class="unit">
                            <li><span>Thông tin khách hàng</span></li>
                            <li><span>Ngày đặt</span></li>        
                            <li><span>Tổng sản phẩm</span></li>
                            <li><span>Phí giao hàng</span></li>
                            <li><span>Tổng tiền</span></li>
                            <div class="clearfix"></div>
                        </ul>
                        <ul class="cart-header">
                            <li>
                                <span style="text-align: left;">
                                    Tên khách hàng: <input type="text" value="<?php echo htmlspecialchars($cotThanhVien['HoTen']); ?>" readonly><br>
                                    Số điện thoại: <input type="number" name="dienThoai" value="<?php echo htmlspecialchars($cotThanhVien['DienThoai']); ?>" required><br>
                                    Nơi giao: <textarea style="width: 100%;" rows="4" id="noiGiao" name="noiGiao" required><?php echo htmlspecialchars($cotThanhVien['DiaChi']); ?></textarea>
                                </span>
                            </li>
                            <li><span><?php echo date("d/m/Y"); ?></span></li>
                            <li><span><?php echo count($_SESSION['gioHang']); ?></span></li>
                            <li><span><?php echo number_format($phiShip, 0, ',', '.'); ?> đ</span></li>
                            <li><span><?php echo number_format($tongTienGioHang+$phiShip, 0, ',', '.'); ?> đ</span></li>
                            <div class="clearfix"></div>
                        </ul>
                        <a class="btn btn-primary" href="./GioHang.php">Quay lại</a>
                        <br>
                        <input style="padding: 5px 10px; float: right !important; margin-top: 0" 
                            class="add-cart add-check" type="submit" name="datHang" value="Xác nhận đặt hàng">
                    </form>
                </div>
            </div>  
        </div>
    </div>
</div>
<!--end-ckeckout-->

<?php include('../shared/footer.php'); ?>
<?php
    include('../shared/header.php');
    // Kiểm tra trạng thái đăng nhập
    if (isset($_SESSION["tenDangNhap"]) && $_SESSION["quyen"] == "Admin") {
        echo "<script> window.location.href = '../admin/TrangChuAdmin.php'; </script>";
        exit;
    }

    // Kiểm tra giỏ hàng tồn tại và không rỗng
    if (!isset($_SESSION["gioHang"]) || empty($_SESSION["gioHang"])) {
        echo "<script>window.location.href = './TrangChu.php';</script>";
        exit;
    }

    // Kiểm tra số lượng tồn kho cho từng sản phẩm trong giỏ hàng
    $errors = [];
    foreach ($_SESSION["gioHang"] as $key => $cotGioHang) {
        $maSanPham = intval($cotGioHang["maSanPham"]);
        $soLuongDat = (int)$cotGioHang["soLuong"];

        $laySanPham = "SELECT SoLuong FROM sanpham WHERE MaSanPham = '$maSanPham'";
        $truyVan_LaySanPham = mysqli_query($conn, $laySanPham);
        $sanPham = mysqli_fetch_assoc($truyVan_LaySanPham);

        if ($soLuongDat > $sanPham['SoLuong']) {
            $errors[] = "Sản phẩm {$cotGioHang['tenSanPham']} không đủ số lượng tồn kho (hiện có: {$sanPham['SoLuong']})!";
            // Xóa sản phẩm khỏi giỏ hàng nếu số lượng không đủ
            unset($_SESSION["gioHang"][$key]);
        } else {
            // Cập nhật số lượng tồn kho hiện tại trong giỏ hàng
            $_SESSION["gioHang"][$key]["soLuongSanPhamHienCon"] = $sanPham['SoLuong'];
        }
    }

    // Nếu giỏ hàng rỗng sau khi kiểm tra, chuyển hướng
    if (empty($_SESSION["gioHang"])) {
        unset($_SESSION["gioHang"]);
        $_SESSION['toastr'] = ['type' => 'info', 'message' => 'Giỏ hàng của bạn đã được làm mới vì một số sản phẩm không đủ số lượng tồn kho!'];
        echo "<script> window.location.href = './SanPham.php'; </script>";
        exit;
    }

    // Nếu có lỗi về số lượng tồn kho, hiển thị thông báo
    if (!empty($errors)) {
        $errorMessage = implode("\\n", $errors);
        $_SESSION['toastr'] = ['type' => 'error', 'message' => "$errorMessage"];
        echo "<script> window.location.href = './GioHang.php'; </script>";
        exit;
    }
?>

<!--start-breadcrumbs-->
<div class="breadcrumbs">
    <div class="container">
        <div class="breadcrumbs-main">
            <ol class="breadcrumb">
                <li><a href="./TrangChu.php">Trang chủ</a></li>
                <li class="active">Giỏ hàng</li>
            </ol>
        </div>
    </div>
</div>
<!--end-breadcrumbs-->

<!--start-ckeckout-->
<div id="gioHang">
    <div class="ckeckout" style="padding-bottom: 0;">
        <div class="container">
            <div class="ckeckout-top">
                <div class="cart-items heading">
                    <h3>Giỏ hàng</h3>
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
                        $tongTienGioHang = 0;
                        foreach ($_SESSION["gioHang"] as $cotGioHang) { 
                            $tongTienGioHang += $cotGioHang["soLuong"] * $cotGioHang["donGia"];
                        ?>
                        <ul class="cart-header" style="padding: 0 14px;">
                            <div class="close1" onclick="xoa_sanPham(<?php echo $cotGioHang['maSanPham']; ?>)"> </div>
                            <li class="ring-in">
                                <a href="./ChiTietSanPham.php?MaSanPham=<?php echo $cotGioHang['maSanPham']; ?>">
                                    <img style="width: 100px;" src="<?php echo $cotGioHang['anhSanPham']; ?>" class="img-responsive" alt="Ảnh sản phẩm">
                                </a>
                            </li>
                            <li><span><?php echo $cotGioHang['tenSanPham']; ?></span></li>
                            <li>
                                <span>
                                    <input id="soLuongDat" type="number" 
                                        onchange="capNhat_gioHang(<?php echo $cotGioHang['maSanPham']; ?>, $(this).val())"
                                        value="<?php echo $cotGioHang['soLuong']; ?>"
                                        min=0 max=<?php echo $cotGioHang['soLuongSanPhamHienCon']; ?>
                                    >
                                </span>
                            </li>
                            <li><span><?php echo number_format($cotGioHang['donGia'], 0, ',', '.'); ?></span></li>
                            <li><span><?php echo number_format($cotGioHang['soLuong'] * $cotGioHang['donGia'], 0, ',', '.'); ?></span></li>
                            <div class="clearfix"></div>
                        </ul>
                        <?php } ?>
                    </div>
                </div>  
            </div>
        </div>
    </div>
    <!--end-ckeckout-->

    <div class="container" style="text-align: right; padding-right: 30px">
        <?php if (isset($_SESSION["tenDangNhap"])) { ?>
			<a class="btn btn-primary" href="./SanPham.php" style="float: left; margin-left: 14px">Thêm sản phẩm</a>
            <a id="anGioHang" class="add-cart add-check" style="cursor: pointer;" data-toggle="modal" 
                data-target="#hinhThucThanhToanModal">Đặt hàng
            </a>
        <?php } else { ?>
            <span class="text-danger">Bạn cần đăng nhập để đặt hàng</span>
        <?php } ?>
    </div>
</div>

<!-- Modal chọn hình thức thanh toán -->
<div id="hinhThucThanhToanModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="padding: 20px;">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Chọn hình thức thanh toán</h4>
            </div>
            <div class="modal-body">
                <form id="hinhThucThanhToanForm" method="GET" action="ThanhToan.php">
                    <div class="form-group">
                        <label>Hình thức thanh toán:</label>
                            <label style="margin-left: 40px; text-align: center;">
                                <input type="radio" name="hinhThuc" value="COD" checked> Thanh toán khi nhận hàng (COD)
                            </label>
                            <label style="margin-left: 40px; text-align: center;">
                                <input type="radio" name="hinhThuc" value="Momo"> Thanh toán qua ví điện tử Momo
                            </label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-primary">Tiếp tục</button> 
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include('../shared/footer.php'); ?>
<!DOCTYPE html>
<html>
<head>
<title>Truyện tranh</title>
<!-- Bootstrap CSS -->
<link href="../css/bootstrap.css" rel="stylesheet" type="text/css" media="all" />
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="../js/jquery-1.11.0.min.js"></script>
<!-- Bootstrap JS -->
<script src="../js/bootstrap.min.js"></script>
<!-- Custom Theme files -->
<!--theme-style-->
<link href="../css/style.css" rel="stylesheet" type="text/css" media="all" />		
<!--//theme-style-->
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script type="application/x-javascript"> 
    addEventListener("load", function() {
            setTimeout(hideURLbar, 0); 
        }, false);
    function hideURLbar(){ 
        window.scrollTo(0,1); 
    } 
</script>
<!--fonts-->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> -->
<link href='http://fonts.googleapis.com/css?family=Alegreya+Sans+SC:100,300,400,500,700,800,900,100italic,300italic,400italic,500italic,700italic,800italic,900italic' rel='stylesheet' type='text/css'>
<link href='http://fonts.googleapis.com/css?family=Lato:100,300,400,700,900,100italic,300italic,400italic,700italic,900italic' rel='stylesheet' type='text/css'>
<!--//fonts-->



<!-- Toastr CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
        <!-- jQuery (cần để Toastr hoạt động) -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <!-- Toastr JS -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

        <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

        <script>
            toastr.options = {
                "closeButton": true, // Hiển thị nút đóng
                "debug": false,
                "newestOnTop": true, // Thông báo mới hiển thị phía trên
                "progressBar": true, // Hiển thị thanh tiến trình
                "positionClass": "toast-top-center", // Vị trí
                "preventDuplicates": true, // Ngăn thông báo trùng lặp
                "onclick": null,
                "showDuration": "300", // Thời gian hiển thị (ms)
                "hideDuration": "1000", // Thời gian ẩn (ms)
                "timeOut": "5000", // Thời gian tự động ẩn (ms)
                "extendedTimeOut": "1000", // Thời gian ẩn khi hover (ms)
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            };
        </script>

<script type="text/javascript" src="../js/easing.js"></script>

<!-- start menu -->
<script src="../js/simpleCart.min.js"> </script>
<link href="../css/memenu.css" rel="stylesheet" type="text/css" media="all" />
<script type="text/javascript" src="../js/memenu.js"></script>
<script>
    $(document).ready(function(){
        $(".memenu").memenu();
    });
</script>		

<?php
    session_start();
    include(__DIR__ . '/database.php');
    include(__DIR__ . '/functions.php');

    if(isset($_GET["lamMoiGioHang"]))
        unset($_SESSION["gioHang"]);
?>
<!-- Slick Slider CSS -->
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css"/>

<!-- Slick Slider JS -->
<script src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
</head>

<body id="home"> 
<!--top-header-->
<div class="top-header">
    <div class="container">
        <div class="top-header-main">
            <div class="col-md-4 top-header-left rounded-3">
                <div class="search-bar">
                    <form id="formTimKiem" method="POST" action="./TimKiemSanPham.php">
                        <input id="timKiemTenSanPham" name="timKiemTenSanPham" type="text" placeholder="Nhập tên sản phẩm ...">
                        <input type="submit" value="" class="btn btn-primary">
                    </form>
                </div>
                <script>
                    $(document).ready(function(){
                        $('#formTimKiem').submit(function(e){
                            let timKiem = $('#timKiemTenSanPham').val().trim();
                            if(timKiem === ""){
                                e.preventDefault(); // Ngăn form gửi đi
                                toastr.info("Hãy nhập từ khóa tìm kiếm sản phẩm!");
                            }
                        });
                    });
                </script>
            </div>
            <div class="col-md-4 top-header-middle">
                <a href="./TrangChu.php"><img width="304px" height="50px" src="../images/logo-removebg-preview.png" alt="Ảnh logo" /></a>
            </div>
            <div class="col-md-4 top-header-right divGioHang">
                <div class="cart box_1">
                    <a href="./GioHang.php">
                        <div class="total">
                            <?php
                                $tongSanPham = 0;
                                $tongTien = 0;
                                if (isset($_SESSION["gioHang"]) && is_array($_SESSION["gioHang"])) {
                                    foreach ($_SESSION["gioHang"] as $cotGioHang) {
                                        $tongSanPham += $cotGioHang["soLuong"];
                                        $tongTien += $cotGioHang["soLuong"] * $cotGioHang["donGia"];
                                    }
                                }
                            ?>
                            <span><?php echo number_format($tongTien, 0, ',', '.'); ?> đ</span>
                            (<span id="simpleCart_quantity"><?php echo $tongSanPham; ?></span> sản phẩm)
                        </div>
                        <img src="../images/cart-1.png" alt="Ảnh giỏ hàng" />
                    </a>
                    <p><a href="<?php echo $_SERVER["PHP_SELF"]; ?>?lamMoiGioHang=0" class="simpleCart_empty">Làm mới</a></p>
                    <div class="clearfix"></div>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
</div>
<!--top-header-->
<!--bottom-header-->
<div class="header-bottom">
    <div class="container">
        <div class="top-nav">
            <ul class="memenu skyblue"><li class="active"><a href="./TrangChu.php">Trang chủ</a></li>
                <li><a href="./SanPham.php">Sản phẩm</a></li>
                <li class="grid"><a>Danh mục sản phẩm</a>
                    <div class="mepanel">
                        <div class="row">
                            <div class="col1 me-one">
                                <h4>Thể loại</h4>
                                <ul>
                                <?php
                                    $layLoaiSP = "SELECT * FROM loaisp";
                                    $truyVan_LayLoaiSP = mysqli_query($conn, $layLoaiSP);
                                    while($cot = mysqli_fetch_assoc($truyVan_LayLoaiSP)){
                                        ?>
                                    <li><a href="./DanhMucSanPham.php?loaiSanPham=<?php echo $cot["MaLoaiSP"] ?>">
                                        <?php echo $cot["TenLoai"] ?></a></li>  
                                <?php } ?>
                                </ul>
                            </div>  
                            <div class="col1 me-one">
                                <h4>Tác giả</h4>
                                <ul>
                                    <?php
                                        $layTacGia = "SELECT DISTINCT TacGia FROM sanpham WHERE TacGia IS NOT NULL ORDER BY TacGia";
                                        hienThiDanhMuc($conn, $layTacGia, 'tacGia', 'TacGia');
                                    ?>
                                </ul>
                            </div>
                            <div class="col1 me-one">
                                <h4>Xuất xứ</h4>
                                <ul>
                                    <?php
                                        $layXuatXu = "SELECT DISTINCT XuatXu FROM sanpham WHERE XuatXu IS NOT NULL ORDER BY XuatXu";
                                        hienThiDanhMuc($conn, $layXuatXu, 'xuatXu', 'XuatXu');
                                    ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </li>
                <?php 
                    if(isset($_SESSION["tenDangNhap"])){
                        $tenDangNhap = mysqli_real_escape_string($conn, $_SESSION["tenDangNhap"]);
                        $layThanhVien = "SELECT *
                                        FROM nguoidung
                                        WHERE TenDangNhap = '$tenDangNhap'";
                        $truyVan_LayThanhVien = mysqli_query($conn, $layThanhVien);
                        $thanhVien = mysqli_fetch_assoc($truyVan_LayThanhVien);
                    }
                    if(!isset($_SESSION["tenDangNhap"])) { ?>
                    <li><a href="DangKy.php">Đăng ký</a></li>
                    <li data-toggle="modal" data-target="#largeModal_dn"><a href="#">Đăng nhập</a></li><!-- Button để mở modal -->
                <?php } elseif(isset($_SESSION["tenDangNhap"]) && $_SESSION["quyen"] == "Member" && $thanhVien["TrangThai"] == "Kích hoạt") { ?>
                    <li><a href="../users/LichSuDonHang.php">Đơn hàng</a></li>
                    <?php 
                        $layYeuThich = "SELECT * FROM yeuthich";
                        $truyVan_LayYeuThich = mysqli_query($conn, $layYeuThich);
                        $soSanPhamYeuThich = mysqli_num_rows($truyVan_LayYeuThich);
                    ?>
                    <li><a href="../users/SanPhamYeuThich.php">Yêu thích (<?php echo $soSanPhamYeuThich; ?>)</a></li>
                    <li>
                        <a href="ThongTintaiKhoan.php">
                            <span style="text-transform: none;">Xin chào <?php echo $_SESSION["tenDangNhap"]; ?></span>
                        </a>
                    </li>
                    <li><a href="#" class="btn btn-danger btn-dang-xuat">
                            <i class="fas fa-sign-out-alt me-1"></i>Đăng xuất</a>
                    </li>

                <?php } else{?>
                    <li><a href="DangKy.php">Đăng ký</a></li>
                    <li data-toggle="modal" data-target="#largeModal_dn"><a href="#">Đăng nhập</a></li>
                <?php } ?>
            </ul>
        </div>
        <div class="clearfix"> </div>
    </div>
</div>
<!--bottom-header-->
<script>
    // Gán sự kiện click trực tiếp cho nút "Đăng xuất"
    $(document).ready(function(){
        $('.btn-dang-xuat').on('click', function(e){
            e.preventDefault();
            Swal.fire({
                title: 'Xác nhận đăng xuất',
                text: 'Bạn có chắc chắn muốn đăng xuất?',
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Đăng xuất',
                cancelButtonText: 'Hủy',
                reverseButtons: true 
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '../shared/DangXuat.php';
                }
            });
        });
    });
</script>   

<!-- Large Modal -->
<div id="largeModal_dn" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog">  <!-- Thêm 'modal-lg' để có kích thước lớn -->
        <div class="modal-content" style="padding: 30px 30px 80px;">
            <!-- <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Large Modal</h4>
            </div> -->
            <div class="modal-body">
                <form method="POST" action="../shared/XuLyDangNhap.php">
                    <input type="hidden" name="trangHienTai" value="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>"/>
                    <div class="account-top heading">
                        <h3>Đăng nhập</h3>
                    </div>
                    <div class="address">
                        <span>Tên đăng nhập</span>
                        <input id="tenDangNhap_dn" name="tenDangNhap" type="text">
                    </div>
                    <div class="address">
                        <span>Mật khẩu</span>
                        <input id="matKhau_dn" name="matKhau" type="password">
                    </div>
                    <div style="float: right; margin-top: 20px">
                        <a class="forgot" href="../users/QuenMatKhau.php">Quên mật khẩu?</a>
                        <input class="btn btn-danger" id="dangNhap_dn" type="submit" value="Đăng nhập">
                    </div>
                </form>

                <script>
                    $(document).ready(function(){
                        $('#dangNhap_dn').click(function(){
                            var tenDangNhap = $('#tenDangNhap_dn').val();
                            var matKhau = $('#matKhau_dn').val();

                            if (tenDangNhap === "" || matKhau === "") {
                                toastr.warning("Hãy nhập đầy đủ thông tin!");
                                return false;
                            }
                        });
                    });
                </script>
            </div>
            <!-- <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
            </div> -->
        </div>
    </div>
</div>

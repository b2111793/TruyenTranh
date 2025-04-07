<?php 
	include('../shared/header.php');
	// Kiểm tra trạng thái đăng nhập
	if (isset($_SESSION["tenDangNhap"]) && $_SESSION["quyen"] == "Admin") {
        echo "<script> window.location.href = '../admin/TrangChuAdmin.php'; </script>";
        exit;
	}

    if (!isset($_GET["MaSanPham"])) {
        echo "<script>window.location.href = './TrangChu.php';</script>";
        exit;
    }

    $maSanPham = mysqli_real_escape_string($conn, $_GET["MaSanPham"]);
    $laySanPham = "SELECT * FROM sanpham WHERE MaSanPham = '$maSanPham'";
    $truyVan_LaySanPham = mysqli_query($conn, $laySanPham);
    if (mysqli_num_rows($truyVan_LaySanPham) == 0) {
        $_SESSION['toastr'] = ['type' => 'warning', 'message' => 'Sản phẩm không tồn tại!'];
        echo "<script> window.location.href = './SanPham.php'; </script>";
        exit;
    }
    $cot = mysqli_fetch_assoc($truyVan_LaySanPham);

    // Lấy sản phẩm liên quan
    $laySanPham_LienQuan = "SELECT * FROM sanpham WHERE MaLoaiSP = '" . mysqli_real_escape_string($conn, $cot["MaLoaiSP"]) . "' AND MaSanPham != '$maSanPham' ORDER BY DonGia DESC LIMIT 0,6";
    $truyVan_LaySanPham_LienQuan = mysqli_query($conn, $laySanPham_LienQuan);

    $tenDangNhap = "";
    if (isset($_SESSION["tenDangNhap"]))
        $tenDangNhap = mysqli_real_escape_string($conn, $_SESSION["tenDangNhap"]);

    $choPhepBinhLuan = $choPhepDanhGia = false;
    // Kiểm tra điều điện để đánh giá, bình luận
    $kiemTraDonHang = " SELECT dd.MaDonDat
                        FROM dondat dd
                        INNER JOIN ct_dondat ctdd 
                        ON dd.MaDonDat = ctdd.MaDonDat
                        AND ctdd.MaSanPham = '$maSanPham'
                        AND dd.TrangThai = 'Đã giao'
                        LIMIT 1";        
    $truyVan_KiemTraDonHang = mysqli_query($conn, $kiemTraDonHang);
    if (mysqli_num_rows($truyVan_KiemTraDonHang) > 0) {
        $choPhepBinhLuan = $choPhepDanhGia = true;
    }
    // Đánh giá
    $soNguoiDanhGia = $soSaoTrungBinh = $tongSoSao = 0;
    $layDanhGia = "SELECT * FROM danhgia WHERE MaSanPham = '$maSanPham'";
    $truyVan_LayDanhGia = mysqli_query($conn, $layDanhGia);
    // Đếm số lượt đánh giá
    $soNguoiDanhGia = mysqli_num_rows($truyVan_LayDanhGia);
    // Tính tổng số sao
    if ($soNguoiDanhGia > 0) {
        while ($cotDanhGia = mysqli_fetch_assoc($truyVan_LayDanhGia)) {
            $tongSoSao += $cotDanhGia["NoiDung"];
        }
        $soSaoTrungBinh = $tongSoSao / (float)$soNguoiDanhGia;
    }
    // Lấy nội dung đánh giá của người dùng hiện tại
    $soSao = 0;
    $layNoiDungDanhGia = "SELECT * FROM danhgia WHERE MaSanPham = '$maSanPham' AND TenDangNhap = '$tenDangNhap'";
    $truyVan_LayNoiDungDanhGia = mysqli_query($conn, $layNoiDungDanhGia);

    if (mysqli_num_rows($truyVan_LayNoiDungDanhGia) > 0) {
        $cotNoiDungDanhGia = mysqli_fetch_assoc($truyVan_LayNoiDungDanhGia);
        $soSao = $cotNoiDungDanhGia["NoiDung"];
    }
    
    // Kiểm tra sản phẩm đã có trong danh sách yêu thích chưa
    $daYeuThich = false;
    $kiemTraYeuThich = "SELECT * FROM yeuthich WHERE TenDangNhap = '$tenDangNhap' AND MaSanPham = '$maSanPham'";
    $truyVan_KiemTraYeuThich = mysqli_query($conn, $kiemTraYeuThich);
    if (mysqli_num_rows($truyVan_KiemTraYeuThich) > 0) {
        $daYeuThich = true;
    }  
    
    // Lấy bình luận
    $layBinhLuan = "SELECT * 
                    FROM binhluan INNER JOIN nguoidung
                    ON binhluan.TenDangNhap = nguoidung.TenDangNhap
                    WHERE MaSanPham = '$maSanPham' ORDER BY MaBinhLuan DESC";
    $truyVan_LayBinhLuan = mysqli_query($conn, $layBinhLuan);
?>

<!--start-breadcrumbs-->
<div class="breadcrumbs">
    <div class="container">
        <div class="breadcrumbs-main">
            <ol class="breadcrumb">
                <li><a href="./TrangChu.php">Trang chủ</a></li>
                <li class="active">Chi tiết sản phẩm</li>
            </ol>
        </div>
    </div>
</div>
<!--end-breadcrumbs-->

<!--start-single-->
<div class="single contact">
    <div class="container">
        <div class="single-main">
            <div class="col-md-9 single-main-left">
                <div class="sngl-top">
                    <div class="col-md-5 single-top-left">    
                        <div class="product-slider-main">
                            <?php if (!empty($cot["Anh"])) { ?>
                                <div><img src="<?php echo $cot["Anh"]; ?>" alt="Ảnh sản phẩm" style="width: 100%;"/></div>
                            <?php } ?>
                            <?php if (!empty($cot["AnhMoTa1"])) { ?>
                                <div><img src="<?php echo $cot["AnhMoTa1"]; ?>" alt="Ảnh sản phẩm" style="width: 100%;"/></div>
                            <?php } ?>
                            <?php if (!empty($cot["AnhMoTa2"])) { ?>
                                <div><img src="<?php echo $cot["AnhMoTa2"]; ?>" alt="Ảnh sản phẩm" style="width: 100%;"/></div>
                            <?php } ?>
                            <?php if (!empty($cot["AnhMoTa3"])) { ?>
                                <div><img src="<?php echo $cot["AnhMoTa3"]; ?>" alt="Ảnh sản phẩm" style="width: 100%;"/></div>
                            <?php } ?>
                        </div>
                        <div class="product-slider-nav">
                            <?php if (!empty($cot["Anh"])) { ?>
                                <div><img src="<?php echo $cot["Anh"]; ?>" alt="Ảnh sản phẩm" style="width: 100%;"/></div>
                            <?php } ?>
                            <?php if (!empty($cot["AnhMoTa1"])) { ?>
                                <div><img src="<?php echo $cot["AnhMoTa1"]; ?>" alt="Ảnh sản phẩm" style="width: 100%;"/></div>
                            <?php } ?>
                            <?php if (!empty($cot["AnhMoTa2"])) { ?>
                                <div><img src="<?php echo $cot["AnhMoTa2"]; ?>" alt="Ảnh sản phẩm" style="width: 100%;"/></div>
                            <?php } ?>
                            <?php if (!empty($cot["AnhMoTa3"])) { ?>
                                <div><img src="<?php echo $cot["AnhMoTa3"]; ?>" alt="Ảnh sản phẩm" style="width: 100%;"/></div>
                            <?php } ?>
                        </div>
                    </div> 
                    <div class="col-md-7 single-top-right">
                        <!-- ĐÁNH GIÁ -->
                        <div class="details-left-info simpleCart_shelfItem">
                            <h3><?php echo htmlspecialchars($cot["TenSanPham"]); ?></h3>
                            <?php if (isset($_SESSION["tenDangNhap"]) && $choPhepDanhGia) { ?> 
                            <ul class="saoDanhGia">
                                <li class="sao sao1" data-sao="1" onclick="danh_gia(<?php echo $maSanPham; ?>,'<?php echo $tenDangNhap; ?>', 1)"></li>
                                <li class="sao sao2" data-sao="2" onclick="danh_gia(<?php echo $maSanPham; ?>,'<?php echo $tenDangNhap; ?>', 2)"></li>
                                <li class="sao sao3" data-sao="3" onclick="danh_gia(<?php echo $maSanPham; ?>,'<?php echo $tenDangNhap; ?>', 3)"></li>
                                <li class="sao sao4" data-sao="4" onclick="danh_gia(<?php echo $maSanPham; ?>,'<?php echo $tenDangNhap; ?>', 4)"></li>
                                <li class="sao sao5" data-sao="5" onclick="danh_gia(<?php echo $maSanPham; ?>,'<?php echo $tenDangNhap; ?>', 5)"></li>
                            </ul>
                            <?php } ?>

                            <div class="danhGiaSaoTrungBinh">
                                <span>
                                    <?php 
                                        if ($soSaoTrungBinh == 0)
                                            echo $soSaoTrungBinh;
                                        else
                                            echo number_format($soSaoTrungBinh, 1, '.', ',');
                                    ?>
                                </span>   
                                <span class="sao saoDanhGia" style="background-color: yellow;"></span>
                                <span>(<?php echo mysqli_num_rows($truyVan_LayDanhGia); ?> đánh giá)</span>
                            </div>

                    
                            <div class="price_single">
                                <span class="actual item_price"><?php echo number_format($cot["DonGia"], 0, ',', '.'); ?> đ
                            </div>

                            <h2 class="quick">Giới thiệu: </h2>
                            <p class="quick_desc"><?php echo htmlspecialchars($cot["GioiThieu"]); ?></p>

                            <div class="quantity_box">
                                <ul class="product-qty">
                                    <span>Số lượng:</span> 
                                    
                                    <div class="tangGiamSoLuong">
                                        <button class="giamSoLuong">-</button>
                                        <input type="number" id="soLuongDat" value=1 min=1 max=<?php echo $cot["SoLuong"]; ?>>
                                        <button class="tangSoLuong">+</button>
                                    </div>
                            
                                </ul>
                            </div>
                            <div style="text-align: left;">
                                <b class="availability"><span class="color">  
                                    <?php echo $cot["SoLuong"] == 0 ? 'Hết hàng' : ''; ?></span>
                                </b>
                            </div>
                            <div class="clearfix"></div>
                            <div class="single-but item_add">
                                <?php if (isset($_SESSION["tenDangNhap"])) { ?>
                                    <span> 
                                        <i class="far fa-heart heart-icon" data-product-id="<?php echo $maSanPham; ?>"></i>
                                    </span>
                                    <button 
                                        type="button" 
                                        class="btn btn-primary"
                                        style="<?php echo ($cot["SoLuong"] == 0) ? 'cursor: not-allowed !important; opacity: 0.5;' : ''; ?>" 
                                        <?php echo ($cot["SoLuong"] > 0) ? 'onclick="them_gioHang('.$maSanPham.', $(\'#soLuongDat\').val())"' : ''; ?>
                                    >
                                        Thêm vào giỏ hàng
                                    </button>                   
                                <?php } else { ?>
                                    <a data-toggle="modal" data-target="#largeModal_dn" href="#" class="btn btn-success">
                                        Thêm vào giỏ hàng
                                    </a>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <hr>
            
                <!-- BÌNH LUẬN -->
                <h3>Bình luận sản phẩm:</h3>
                <?php if (isset($_SESSION["tenDangNhap"])) { ?>
                    <?php if($choPhepBinhLuan) { ?>
                    <form id="binhLuanForm" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>?MaSanPham=<?php echo $maSanPham; ?>">
                        <textarea id="noiDungBinhLuan" name="noiDungBinhLuan" class="form-control" rows="4" placeholder="Nhập nội dung bình luận ..."></textarea>
                        <div class="single-but item_add" style="text-align: right;">
                            <input type="submit" value="Bình luận"/>
                        </div>
                    </form>
                <?php } } else { echo "Bạn hãy đăng nhập để xem bình luận về sản phẩm này!"; } ?>
                
                <?php if(isset($_SESSION["tenDangNhap"])) {
                if(mysqli_num_rows($truyVan_LayBinhLuan) == 0){
                    echo "Chưa có bình luận về sản phẩm này!";
                }
                else {
                    $sobinhluan = 0;
                    $soBinhLuanToiDa = 5; 
                while ($cotBinhLuan = mysqli_fetch_assoc($truyVan_LayBinhLuan)) { 
                    $sobinhluan++; 
                    $classAn = ($sobinhluan > $soBinhLuanToiDa) ? 'anDoiTuong' : ''; 
                ?>
                    <div id="binhLuanContainer">
                        <div class="binhLuan <?php echo $classAn; ?>">
                            <hr style="width: 70%;">
                            <div>
                                <span class="binhLuanTen"><?php echo htmlspecialchars($cotBinhLuan["HoTen"]); ?></span>
                                <span class="binhLuanNgay">đã bình luận vào ngày <?php echo date("d/m/Y", strtotime($cotBinhLuan["NgayBinhLuan"])); ?></span>
                                <?php if (isset($_SESSION["tenDangNhap"]) && ($cotBinhLuan["TenDangNhap"] == $_SESSION["tenDangNhap"]) && $choPhepBinhLuan) { ?>
                                    <span class="fas fa-times binhLuan_iconXoa" onclick="xoa_binhLuan(<?php echo $cotBinhLuan['MaBinhLuan']; ?>, <?php echo $cotBinhLuan['MaSanPham']; ?>)"></span>
                                    <span class="fas fa-pencil binhLuan_iconSua" data-toggle="modal" data-target="#largeModal_suaBL"></span>
                                <?php } ?>
                                <input type="hidden" id="lay_maBL" value="<?php echo $cotBinhLuan["MaBinhLuan"]; ?>">
                                <input type="hidden" id="lay_noiDungBL" value="<?php echo $cotBinhLuan["NoiDung"]; ?>">
                                <div class="binhLuanNoiDung">
                                    <?php echo $cotBinhLuan["NoiDung"]; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } if ($sobinhluan > $soBinhLuanToiDa) { ?>
                    <button id="xemThemBinhLuan" class="btn btn-primary">Xem thêm</button>
                    <button id="anBinhLuan" class="btn btn-primary" style="display: none;">Ẩn bớt</button>
                <?php  } } } ?>
                <hr>

                <div class="latest products">
                    <div class="product-one">
                        <!-- DANH SÁCH SẢN PHẨM LIÊN QUAN -->
                        <h2>Truyện tranh liên quan</h2>
                        <div class="col-md-12 p-left">
                            <div class="clearfix"></div>
                            <?php 
                            $index = 0;
                            while ($cotLienQuan = mysqli_fetch_assoc($truyVan_LaySanPham_LienQuan)) {
                                $index++;
                                $maSanPham = $cotLienQuan["MaSanPham"];
                            ?>
                                <div class="product-one">
                                    <div class="col-md-4 product-left single-left"> 
                                        <div class="p-one simpleCart_shelfItem">
                                            <a href="./ChiTietSanPham.php?MaSanPham=<?php echo $cotLienQuan['MaSanPham']; ?>">
                                                <img height="200px" src="<?php echo $cotLienQuan['Anh']; ?>" alt="<?php echo htmlspecialchars($cotLienQuan['Anh']); ?>" />
                                                <div class="mask mask1">
                                                    <span>Xem chi tiết</span>
                                                </div>
                                            </a>
                                            <h4 class="tenSanPham"><?php echo htmlspecialchars($cotLienQuan['TenSanPham']); ?></h4>
                                            <h5>Tác giả: <?php echo htmlspecialchars($cotLienQuan['TacGia']); ?></h5>
                                            <p><a class="item_add" href="./GioHang.php" ><i></i>
                                                <span class=" item_price">
                                                    <?php echo number_format($cotLienQuan["DonGia"], 0, ',', '.'); ?> đ 
                                                </span></a>
                                            </p>
                                            <div class="single-but item_add">
                                                <?php if (isset($_SESSION["tenDangNhap"])) { ?>
                                                    <span> 
                                                        <i class="far fa-heart heart-icon" data-product-id="<?php echo $maSanPham; ?>"></i>
                                                    </span>
                                                    <button 
                                                        type="button" 
                                                        class="btn btn-success btn-them-gio-hang"
                                                        style="<?php echo ($cot["SoLuong"] == 0) ? 'cursor: not-allowed !important; opacity: 0.5;' : ''; ?>" 
                                                        <?php echo ($cot["SoLuong"] > 0) ? 'onclick="them_gioHang('.$maSanPham.', 1)"' : ''; ?>
                                                    >
                                                        Thêm vào giỏ hàng
                                                    </button>                   
                                                <?php } else { ?>
                                                    <a data-toggle="modal" data-target="#largeModal_dn" href="#" class="btn btn-success btn-them-gio-hang" style="margin: auto;">
                                                        Thêm vào giỏ hàng
                                                    </a>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php if ($index % 3 == 0) { ?>
                                    <div class="clearfix"></div>
                                <?php } ?>
                            <?php } ?>
                            <!-- <div class="divTrang"></div> -->
                        </div>
                    </div>
                </div>
            </div>
            <!-- DANH MỤC -->
            <div class="col-md-3 p-right single-right">
                <h3>Thể loại</h3>
                <ul class="product-categories">
                    <?php
                        $layLoaiSP = "SELECT * FROM loaisp";
                        $truyVan_LayLoaiSP = mysqli_query($conn, $layLoaiSP);
                        while($cotDanhMuc = mysqli_fetch_assoc($truyVan_LayLoaiSP)){
                            ?>
                        <li><a href="./DanhMucSanPham.php?loaiSanPham=<?php echo $cotDanhMuc["MaLoaiSP"] ?>">
                            <?php echo $cotDanhMuc["TenLoai"] ?></a></li>  
                    <?php } ?>
                </ul>
                <h3>Tác giả</h3>
                <ul class="product-categories">
                    <?php
                        $layTacGia = "SELECT DISTINCT TacGia FROM sanpham WHERE TacGia IS NOT NULL ORDER BY TacGia";
                        hienThiDanhMuc($conn, $layTacGia, 'tacGia', 'TacGia');
                    ?>
                </ul>
                <h3>Xuất xứ</h3>
                <ul class="product-categories">
                    <?php
                        $layXuatXu = "SELECT DISTINCT XuatXu FROM sanpham WHERE XuatXu IS NOT NULL ORDER BY XuatXu";
                        hienThiDanhMuc($conn, $layXuatXu, 'xuatXu', 'XuatXu');
                    ?>
                </ul>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
</div>
<!--end-single-->

<?php 
    // XỬ LÝ BÌNH LUẬN 
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $ngayBinhLuan = date("Y-m-d");
        $noiDungBinhLuan = mysqli_real_escape_string($conn, $_POST["noiDungBinhLuan"]);
        $themBinhLuan = "INSERT INTO binhluan (TenDangNhap, MaSanPham, NgayBinhLuan, NoiDung) 
                        VALUES ('$tenDangNhap', '$maSanPham', '$ngayBinhLuan', '$noiDungBinhLuan')";

        if (mysqli_query($conn, $themBinhLuan)) {
            $_SESSION['toastr'] = ['type' => 'success', 'message' => 'Bình luận thành công!'];
            echo "<script> window.location.href = './ChiTietSanPham.php?MaSanPham=$maSanPham'; </script>";
            exit;
        } else {
            $_SESSION['toastr'] = ['type' => 'error', 'message' => 'Đã xảy ra lỗi: ' . mysqli_error($conn)];
            echo "<script> window.location.href = './ChiTietSanPham.php?MaSanPham=$maSanPham'; </script>";
            exit;
        }
    }
?>

<!-- Large Modal Bình Luận -->
<div id="largeModal_suaBL" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg"> 
        <div class="modal-content" style="padding: 50px;">
            <div class="modal-body">
                <form id="suaBinhLuanForm" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>?MaSanPham=<?php echo $maSanPham; ?>">
                    <input type="hidden" name="trangHienTai" value="<?php echo $_SERVER["PHP_SELF"]; ?>"/>
                    <div class="account-top heading">
                        <h3>Chỉnh sửa bình luận</h3>
                    </div>
                    <div class="address">
                        <span>Nội dung</span>
                        <input type="hidden" id="maBinhLuan_suaBinhLuan">
                        <input type="hidden" id="maSanPham_suaBinhLuan" value="<?php echo $maSanPham; ?>">
                        <textarea id="noiDung_suaBinhLuan" class="form-control" rows="4" placeholder="Nhập nội dung chỉnh sửa ..." >
                        
                        </textarea>
                    </div>
                    <div class="address">
                        <span id="thongBao_suaBinhLuan" style="color: red;"></span>
                        <input type="submit" value="Lưu" style="float: right;">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Slider sản phẩm
        $('.product-slider-main').slick({
            slidesToShow: 1,
            slidesToScroll: 1,
            arrows: true,
            dots: false,
            asNavFor: '.product-slider-nav',
            adaptiveHeight: true,
            autoplay: true,         
            autoplaySpeed: 3000 
        });
        $('.product-slider-nav').slick({
            slidesToShow: 4,
            slidesToScroll: 1,
            asNavFor: '.product-slider-main',
            dots: false,
            arrows: false,
            centerMode: true,
            focusOnSelect: true,
            autoplay: true,         
            autoplaySpeed: 3000
        });

        // Đánh giá sản phẩm
        for (i = 1; i <= <?php echo $soSao; ?>; i++) {
            $('.sao' + i).addClass('saohover');
        }
        $('.sao').mouseenter(function(){
            for (i = 1; i <= $(this).attr('data-sao'); i++) {
                $('.sao' + i).addClass('saohover');
            }
        });
        $('.sao').mouseleave(function(){
            $('.sao').removeClass('saohover');
        });

        // Tăng giảm số lượng mua sản phẩm
        $(".tangSoLuong").click(function(){
            let soLuong = $("#soLuongDat");
            let value = parseInt(soLuong.val());
            soLuong.val(value + 1);
        });

        $(".giamSoLuong").click(function(){
            let soLuong = $("#soLuongDat");
            let value = parseInt(soLuong.val());
            if (value > 1) {
                soLuong.val(value - 1);
            }
        });

        // Thêm xóa yêu thích
        $('.heart-icon').on('click', function() {
            let icon = $(this);
            let maSanPham = icon.data('product-id');

            if (icon.hasClass('liked')) {
                // Nếu đã yêu thích, gọi hàm xóa
                xoa_yeuThich(maSanPham);
            } else {
                // Nếu chưa yêu thích, gọi hàm thêm
                them_yeuThich(maSanPham);
            }
        });
 
        // Bình luận
        $('#noiDungBinhLuan').on('keydown', function(event) {
            if (event.key === 'Enter') {
                if (event.shiftKey) {
                    // Shift + Enter: Xuống dòng
                    event.preventDefault();
                    const textarea = this;
                    const start = textarea.selectionStart;
                    const end = textarea.selectionEnd;
                    textarea.value = textarea.value.substring(0, start) + '\n' + textarea.value.substring(end);
                    textarea.selectionStart = textarea.selectionEnd = start + 1;
                } else {
                    // Enter: Gửi form
                    event.preventDefault();
                    $('#binhLuanForm').submit();
                }
            }
        });
        // Xử lý sự kiện submit của form
        $('#binhLuanForm').on('submit', function(event) {
            var noiDung = $('#noiDungBinhLuan').val().trim();
            if (noiDung == "") {
                toastr.info('Hãy nhập nội dung bình luận!', 'Thông báo');
                event.preventDefault(); // Ngăn gửi form
                return false;
            }
        });

        // Xem thêm/ Ẩn bớt
        $("#xemThemBinhLuan").click(function () {
            $(".anDoiTuong").slideDown(); 
            $(this).hide();
            $("#anBinhLuan").show(); 
        });

        $("#anBinhLuan").click(function () {
            $(".anDoiTuong").slideUp(); 
            $(this).hide(); 
            $("#xemThemBinhLuan").show(); 
        });

        // Sửa bình luận
        $('.binhLuan_iconSua').click(function(){
            $('#maBinhLuan_suaBinhLuan').val($(this).parent().find('#lay_maBL').val());
            $('#noiDung_suaBinhLuan').val($(this).parent().find('#lay_noiDungBL').val());
        });
        $('#noiDung_suaBinhLuan').on('keydown', function(event) {
            if (event.key == 'Enter') {
                if (event.shiftKey) {
                    event.preventDefault();
                    const textarea = this;
                    const start = textarea.selectionStart;
                    const end = textarea.selectionEnd;
                    textarea.value = textarea.value.substring(0, start) + '\n' + textarea.value.substring(end);
                    textarea.selectionStart = textarea.selectionEnd = start + 1;
                } else {
                    event.preventDefault();
                    $('#suaBinhLuanForm').submit();
                }
            }
        });
        // Xử lý sự kiện submit của form
        $('#suaBinhLuanForm').on('submit', function(event) {
            var noiDung = $('#noiDung_suaBinhLuan').val().trim(); 
            if (noiDung == "") {
                toastr.info('Hãy nhập nội dung bình luận!', 'Thông báo');
                event.preventDefault(); // Ngăn gửi form
                return false;
            }
            else {
                event.preventDefault(); // Ngăn gửi form
                maBinhLuan = $('#maBinhLuan_suaBinhLuan').val();
                maSanPham = $('#maSanPham_suaBinhLuan').val();
                noiDung = $('#noiDung_suaBinhLuan').val();
                sua_binhLuan(maBinhLuan, maSanPham, noiDung);
            }
        });
    });
</script>

<?php include('../shared/footer.php'); ?>
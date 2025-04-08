<?php
	include('../shared/header.php');
	// Kiểm tra trạng thái đăng nhập
	if (isset($_SESSION["tenDangNhap"]) && $_SESSION["quyen"] == "Admin") {
		echo "<script> window.location.href = '../admin/TrangChuAdmin.php'; </script>";
		exit;
	}

	$laySanPhamGiaMax = "SELECT * FROM sanpham ORDER BY DonGia DESC LIMIT 0,1";
	$laySanPhamGiaMin = "SELECT * FROM sanpham ORDER BY DonGia ASC LIMIT 0,1";
	$laySanPham = "SELECT * FROM sanpham ORDER BY SoLuong DESC LIMIT 0,8";
	$truyVan_LaySanPhamGiaMax = mysqli_query($conn, $laySanPhamGiaMax);
	$truyVan_LaySanPhamGiaMin = mysqli_query($conn, $laySanPhamGiaMin);
	$truyVan_LaySanPham = mysqli_query($conn, $laySanPham);
	$cot1 = mysqli_fetch_array($truyVan_LaySanPhamGiaMax);
	$cot2 = mysqli_fetch_array($truyVan_LaySanPhamGiaMin);
	$maSanPham1 = $cot1["MaSanPham"];
	$maSanPham2 = $cot2["MaSanPham"];

?>
<!--banner-starts-->
<!--banner-starts-->
<div class="bnr">
    <div id="top" class="banner-slider">
        <div><div class="banner-1"></div></div>
        <div><div class="banner-2"></div></div>
        <div><div class="banner-3"></div></div>
    </div>
    <div class="clearfix"></div>
</div>
<!--banner-ends-->
<!--banner-ends--> 
<!--Slider-Starts-Here-->
<!-- Khởi tạo Slick Slider -->
<script>
    $(document).ready(function() {
        $('.banner-slider').slick({
            slidesToShow: 1,
            slidesToScroll: 1,
            arrows: true,         // Không hiển thị mũi tên (giống ResponsiveSlides)
            dots: true,           // Hiển thị dots (giống pager trong ResponsiveSlides)
            autoplay: true,       // Tự động chuyển ảnh
            autoplaySpeed: 2000,  // Chuyển ảnh mỗi 3 giây (có thể điều chỉnh)
            speed: 500,           // Tốc độ chuyển ảnh (500ms, giống ResponsiveSlides)
            pauseOnHover: true,   // Tạm dừng khi hover
            pauseOnFocus: true    // Tạm dừng khi click
        });
    });
</script>
<!--End-slider-script-->
<!--start-banner-bottom--> 
	<div class="banner-bottom">
		<div class="container">
			<div class="banner-bottom-top">
				<div class="col-md-6 banner-bottom-left">
					<div class="bnr-one">
						<div class="bnr-left">
							<h1><a href="ChiTietSanPham.php?MaSanPham=<?php echo $maSanPham1; ?>">
								<?php echo $cot1["TenSanPham"]; ?></a></h1>
							<p>Sản phẩm nằm top !!!</p>
							<div class="single-but item_add">
								<?php if (isset($_SESSION["tenDangNhap"])) { ?>
									<span> 
										<i class="far fa-heart heart-icon" data-product-id="<?php echo $maSanPham1; ?>"></i>
									</span>
									<button 
										type="button" 
										class="btn btn-success btn-them-gio-hang"
										style="<?php echo ($cot1["SoLuong"] == 0) ? 'cursor: not-allowed !important; opacity: 0.5;' : ''; ?>" 
										<?php echo ($cot1["SoLuong"] > 0) ? 'onclick="them_gioHang('.$maSanPham1.', 1)"' : ''; ?>
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
						<div class="bnr-right"> 
							<a href="ChiTietSanPham.php?MaSanPham=<?php echo $cot1["MaSanPham"]; ?>">
							<img width="40%" src="<?php echo $cot1["Anh"]; ?>" alt="Ảnh sản phẩm" /></a>
						</div>
						<div class="clearfix"> </div>
					</div>
				</div>

				<div class="col-md-6 banner-bottom-right">
					<div class="bnr-two">
						<div class="bnr-left">
							<h1><a href="ChiTietSanPham.php?MaSanPham=<?php echo $cot2["MaSanPham"]; ?>">
								<?php echo $cot2["TenSanPham"]; ?></a></h1>
							<p>Sản phẩm giá siêu hời !!!</p>
							<div class="single-but item_add">
								<?php if (isset($_SESSION["tenDangNhap"])) { ?>
									<span> 
										<i class="far fa-heart heart-icon" data-product-id="<?php echo $maSanPham2; ?>"></i>
									</span>
									<button 
										type="button" 
										class="btn btn-success btn-them-gio-hang"
										style="<?php echo ($cot2["SoLuong"] == 0) ? 'cursor: not-allowed !important; opacity: 0.5;' : ''; ?>" 
										<?php echo ($cot2["SoLuong"] > 0) ? 'onclick="them_gioHang('.$maSanPham2.', 1)"' : ''; ?>
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
						<div class="bnr-right"> 
							<a href="ChiTietSanPham.php?MaSanPham=<?php echo $cot2["MaSanPham"]; ?>">
							<img width="40%" src="<?php echo $cot2["Anh"]; ?>" alt="Ảnh sản phẩm" /></a>
						</div>
						<div class="clearfix"> </div>
					</div>
				</div>
				<div class="clearfix"> </div>
			</div>
		</div>
	</div>
	<!--end-banner-bottom--> 
	<!--start-shoes--> 
	<div class="shoes"> 
		<div class="container"> 
			<div class="product-one"></div>
			<?php 
				$index = 0;
				while($cot = mysqli_fetch_array($truyVan_LaySanPham)) {
					$index++;
					$maSanPham = $cot["MaSanPham"];
			?>
				<div class="product-one">
					<div class="col-md-3 product-left"> 
						<div class="p-one simpleCart_shelfItem">							
								<a href="./ChiTietSanPham.php?MaSanPham=<?php echo $cot["MaSanPham"]; ?>">
									<img src="<?php echo $cot["Anh"]; ?>" alt="<?php echo $cot["Anh"]; ?>" />
									<div class="mask">
										<span>Xem chi tiết</span>
									</div>
								</a>
							<h4 class="tenSanPham"><?php echo $cot["TenSanPham"]; ?></h4>
							<p><a class="item_add" href="./GioHang.php" ><i></i>
								<span class=" item_price">
									<?php echo number_format($cot["DonGia"], 0, ',', '.'); ?> đ 
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
			<?php 	if($index % 4 == 0){ ?>
						<div class="clearfix"> </div>
			<?php } } ?>
		</div>
	</div>
	<!--end-shoes-->
	<!--start-abt-shoe-->
	<div class="abt-shoe">
		<div class="container"> 
			<div class="abt-shoe-main">
				<div class="col-md-4 abt-shoe-left">
					<div class="abt-one">
						<br>
						<a href="ChiTietSanPham.php?MaSanPham=2001"><img src="../images/Anh_SanPham/doraemon1.png" alt="" /></a>
						<h4><a href="ChiTietSanPham.php?MaSanPham=2001">Doraemon - Chú mèo máy đến từ tương lai</a></h4>
						<p>Doraemon kể về chú mèo máy từ tương lai giúp cậu bé Nobita vượt qua rắc rối
							bằng những bảo bối thần kỳ, mang đến nhiều bài học ý nghĩa và tiếng cười. </p>
					</div>
				</div>

				<div class="col-md-4 abt-shoe-left">
					<div class="abt-one">
						<br>
						<a href="ChiTietSanPham.php?MaSanPham=1001"><img src="../images/Anh_SanPham/conan1.png" alt="" /></a>
						<h4><a href="ChiTietSanPham.php?MaSanPham=1001">Conan - Thám tử lừng danh</a></h4>
						<p>Thám Tử Lừng Danh Conan là bộ truyện nổi tiếng kể về Shinichi Kudo — một 
							thám tử bị hạ độc và biến thành đứa trẻ, lấy tên Conan Edogawa. Cậu tiếp 
							tục phá án và truy tìm tổ chức đã hại mình. </p>
					</div>
				</div>
				
				<div class="col-md-4 abt-shoe-left">
					<div class="abt-one">
						<br>
						<a href="ChiTietSanPham.php?MaSanPham=3009"><img src="../images/Anh_SanPham/vat_ly_vui.png" alt="" /></a>
						<h4><a href="ChiTietSanPham.php?MaSanPham=3009">10 vạn câu hỏi vì sao</a></h4>
						<p>10 Vạn Câu Hỏi Vì Sao là bộ truyện tranh khoa học thú vị, giải thích 
							các hiện tượng tự nhiên và kiến thức đời sống qua những câu hỏi đơn giản,
							 giúp trẻ em khám phá thế giới một cách sinh động và dễ hiểu.</p>
					</div>
				</div>
				<div class="clearfix"> </div>
			</div>
		</div>
	</div>
	<!--end-abt-shoe-->
<script>
	$(document).ready(function(){
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
	});
</script>

<?php include('../shared/footer.php'); ?>
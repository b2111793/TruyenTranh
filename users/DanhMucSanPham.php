<?php 
	include('../shared/header.php');
	// Kiểm tra trạng thái đăng nhập
	if (isset($_SESSION["tenDangNhap"])) {
		if ($_SESSION["quyen"] == "Admin") {
			echo "<script> window.location.href = '../admin/TrangChuAdmin.php'; </script>";
			exit;
		} 
	}

	if(!isset($_GET["loaiSanPham"]) && !isset($_GET["tacGia"]) && !isset($_GET["xuatXu"])){
		echo "<script> window.location.href = './TrangChu.php'; </script>";
		exit;
	}
?>

<?php
	$trang = 0;
	if(isset($_GET["trang"]))
		$trang = $_GET["trang"];

	if(isset($_GET["loaiSanPham"])){

		$dieuKien = "WHERE MaLoaiSP='".$_GET["loaiSanPham"]."'";
		$truyVan_LaySP = phan_trang("*", "sanpham", $dieuKien, 6, $trang, "&loaiSanPham=".$_GET["loaiSanPham"]);
	}
	elseif(isset($_GET["tacGia"])){
		$dieuKien = "WHERE TacGia='".$_GET["tacGia"]."'";
		$truyVan_LaySP = phan_trang("*", "sanpham", $dieuKien, 6, $trang, "&tacGia=".$_GET["tacGia"]);
	}
	elseif(isset($_GET["xuatXu"])){
		$dieuKien = "WHERE XuatXu='".$_GET["xuatXu"]."'";
		$truyVan_LaySP = phan_trang("*", "sanpham", $dieuKien, 6, $trang, "&xuatXu=".$_GET["xuatXu"]);
	}
?>

	<!--start-breadcrumbs-->
	<div class="breadcrumbs">
		<div class="container">
			<div class="breadcrumbs-main">
				<ol class="breadcrumb">
					<li><a href="./TrangChu.php">Trang chủ</a></li>
					<li class="active">Danh mục sản phẩm</li>
				</ol>
			</div>
		</div>
	</div>
	<!--end-breadcrumbs-->
	<!--start-product--> 
	<div class="product">
		<div class="container">
			<div class="product-main">
				<!-- Phần danh sách sản phẩm -->
				<div class="col-md-9 p-left">
				<div class="clearfix"> </div>
					<?php 
						$index = 0;
						while($cot = mysqli_fetch_assoc($truyVan_LaySP)){ 
							$index++;
						 
					?>
					<div class="product-one">
						<div class="col-md-4 product-left single-left"> 
							<div class="p-one simpleCart_shelfItem">
							<a href="./ChiTietSanPham.php?MaSanPham=<?php echo $cot["MaSanPham"]; ?>">
										<img height="200px" src="<?php echo $cot["Anh"]; ?>" alt="<?php echo $cot["Anh"]; ?>" />
										<div class="mask mask1">
											<span>Xem chi tiết</span>
										</div>
							</a>
							<h4 class="tenSanPham"><?php echo $cot["TenSanPham"]; ?></h4>
							<h5>Tác giả: <?php echo $cot["TacGia"]; ?></h5>
								<p><a class="item_add" href="./GioHang.php" ><i></i>
									<span class=" item_price">
										<?php echo number_format($cot["DonGia"], 0, ',', '.'); ?> đ 
									</span></a>
								</p>
								<button type="button" class="btn btn-success" style="margin-top: 10px;"
									onclick="them_gioHang(<?php echo $cot['MaSanPham']; ?>, 1)">Thêm vào giỏ hàng
								</button>
							</div>
						</div>
					</div>
					<?php if($index % 3 == 0){ ?>
				<div class="clearfix"> </div>
				<?php } } ?>
				<div class="divTrang"></div>
			</div>
			
			<!-- Phần danh mục -->
			<div class="col-md-3 p-right single-right">
			<h3>Thể loại</h3>
			<ul class="product-categories">
				<?php
					$layLoaiSP = "SELECT * FROM loaisp";
					$truyVan_LayLoaiSP = mysqli_query($conn, $layLoaiSP);
					while($cot = mysqli_fetch_assoc($truyVan_LayLoaiSP)){
						?>
					<li><a href="./DanhMucSanPham.php?loaiSanPham=<?php echo $cot["MaLoaiSP"] ?>">
						<?php echo $cot["TenLoai"] ?></a></li>  
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
	<!--end-product-->

<?php include('../shared/footer.php'); ?>

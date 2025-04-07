<?php 
	include('../shared/header.php');
	// Kiểm tra trạng thái đăng nhập
	if (isset($_SESSION["tenDangNhap"]) && $_SESSION["quyen"] == "Admin") {
		echo "<script> window.location.href = '../admin/TrangChuAdmin.php'; </script>";
		exit;
	}

    $trang = 0;
    $dieuKienTrang = "";

	$trang = (isset($_GET["trang"])) ? intval($_GET["trang"]) : $trang;
	$dieuKienTrang = (isset($_GET["tenSanPham"])) ? mysqli_real_escape_string($conn, $_GET["tenSanPham"]) : $dieuKienTrang;
	$dieuKienTrang = (($_SERVER["REQUEST_METHOD"] == "POST")) ? mysqli_real_escape_string($conn, $_POST["timKiemTenSanPham"]) : $dieuKienTrang;
   
    $dieuKien = "WHERE TenSanPham LIKE '%".$dieuKienTrang."%'";
	$truyVan_LaySanPham = phan_trang("*", "sanpham", $dieuKien, 6, $trang, "&tenSanPham=".$dieuKienTrang);
	if(mysqli_num_rows($truyVan_LaySanPham) == 0){
		$_SESSION['toastr'] = ['type' => 'warning', 'message' => 'Không tìm thấy sản phẩm nào có chứa tên từ khóa bạn nhập!'];
        echo "<script> window.location.href = './SanPham.php'; </script>";
        exit;
	}

	$layGiaTriLoc =    "SELECT 'TacGia' AS Loai, TacGia AS GiaTri
						FROM sanpham 
						WHERE TacGia IS NOT NULL
						GROUP BY TacGia
						UNION
						SELECT 'XuatXu' AS Loai, XuatXu AS GiaTri
						FROM sanpham 
						WHERE XuatXu IS NOT NULL
						GROUP BY XuatXu
						ORDER BY Loai, GiaTri";
	$truyVan_LayGiaTriLoc = mysqli_query($conn, $layGiaTriLoc);

    $layLoaiSanPham = "SELECT * FROM loaisp";
    $truyVan_LayLoaiSP = mysqli_query($conn, $layLoaiSanPham);

	// Khởi tạo hai mảng
	$tacGiaList = [];
	$xuatXuList = [];

	// Duyệt truy vấn và lưu vào mảng
	while ($cotGiaTriLoc = mysqli_fetch_assoc($truyVan_LayGiaTriLoc)) {
		if ($cotGiaTriLoc["Loai"] == "TacGia") {
			$tacGiaList[] = $cotGiaTriLoc["GiaTri"];
		} elseif ($cotGiaTriLoc["Loai"] == "XuatXu") {
			$xuatXuList[] = $cotGiaTriLoc["GiaTri"];
		}
	}
?>

	<!--start-breadcrumbs-->
	<div class="breadcrumbs">
		<div class="container">
			<div class="breadcrumbs-main">
				<ol class="breadcrumb">
					<li><a href="TrangChu.php">Trang chủ</a></li>
					<li class="active">Tìm kiếm sản phẩm</li>
				</ol>
			</div>
		</div>
	</div>
	<!--end-breadcrumbs-->
	<!--start-product--> 
	<div class="product">
		<div class="container">
			<div class="product-main">
                <div class="col-md-12 p-left">
                    <div class="row">
						<div class="col-md-3 form-inline">
							Tác giả: 
							<select id="locTacGia" class="form-control">
								<option value="">Chọn tác giả</option>
                                <?php
                                    foreach ($tacGiaList as $tacGia) {
										echo "<option value='" . $tacGia . "'>" . $tacGia . "</option>";
									}
                                ?>
                            </select>
						</div>
						<div class="col-md-3 form-inline">
							Xuất xứ: 
							<select id="locXuatXu" class="form-control">
								<option value="">Chọn xuất xứ</option>
                                <?php
                                    foreach ($xuatXuList as $xuatXu) {
										echo "<option value='" . $xuatXu . "'>" . $xuatXu . "</option>";
									}
                                ?>
                            </select>
						</div>
                        <div class="col-md-4 form-inline">
                            Thể loại: 
							<select id="loaiSanPham" class="form-control">
								<option value="">Chọn thể loại truyện tranh</option>
                                <?php
                                    while($cot = mysqli_fetch_assoc($truyVan_LayLoaiSP)){
                                        echo "<option value='".$cot["MaLoaiSP"]."'>".$cot["TenLoai"]."</option>";
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
				<!-- Phần danh sách sản phẩm -->
				<div class="col-md-9 p-left" id="load_sanPham">
				<div class="clearfix"> </div>
					<?php 
						$index = 0;
						while($cot = mysqli_fetch_assoc($truyVan_LaySanPham)){ 
							$index++;
							$maSanPham = $cot["MaSanPham"];
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
					<?php if($index % 3 == 0){ ?>
				<div class="clearfix"> </div>
				<?php } } ?>
				<div class="divTrang"></div>
			</div>
			
			<!-- Phần danh mục -->

			<div class="col-md-3 p-right single-right">
				<br>
				<h3>Thể loại</h3>
				<ul class="product-categories">
					<?php
						$layLoaiSP = "SELECT * FROM loaisp";
						$truyVan_LayLoaiSP = mysqli_query($conn, $layLoaiSP);
						while($cot = mysqli_fetch_assoc($truyVan_LayLoaiSP)){
					?>
						<li><a href="./DanhMucSanPham.php?loaiSanPham=<?php echo $cot["MaLoaiSP"] ?>">
							<?php echo $cot["TenLoai"] ?></a>
						</li>  
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
			<div class="clearfix"> </div>
		</div>
	</div>
</div>
<!--end-product-->

<script>
    $(document).ready(function(){
        $('#locTacGia, #locXuatXu, #loaiSanPham').change(function(){
            var tacGia = $('#locTacGia').val();
            var xuatXu = $('#locXuatXu').val();
            var loaiSanPham = $('#loaiSanPham').val();
            timKiem_sanPham(tacGia, xuatXu, loaiSanPham);
        });

		$(document).on('click', '.heart-icon', function() {
			let icon = $(this);
			let maSanPham = icon.data('product-id');

			if (icon.hasClass('liked')) {
				xoa_yeuThich(maSanPham);
			} else {
				them_yeuThich(maSanPham);
			}
		});
    });
</script>

<?php include('../shared/footer.php'); ?>

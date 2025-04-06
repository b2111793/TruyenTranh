<?php 
	include('../shared/header.php');
	// Kiểm tra trạng thái đăng nhập
	if (isset($_SESSION["tenDangNhap"]) && $_SESSION["quyen"] == "Admin") {
		echo "<script> window.location.href = '../admin/TrangChuAdmin.php'; </script>";
		exit;
	}

	$tenDangNhap = mysqli_real_escape_string($conn, $_SESSION["tenDangNhap"]);
	$layThongTin = "SELECT * FROM nguoidung WHERE TenDangNhap = '$tenDangNhap'";
	$truyVan_LayThongTin = mysqli_query($conn, $layThongTin);
	$cot = mysqli_fetch_assoc($truyVan_LayThongTin);

?>

<!--start-breadcrumbs-->
<div class="breadcrumbs">
	<div class="container">
		<div class="breadcrumbs-main">
			<ol class="breadcrumb">
				<li><a href="TrangChu.php">Trang chủ</a></li>
				<li class="active">Thông tin tài khoản</li>
			</ol>
		</div>
	</div>
</div>
<!--end-breadcrumbs-->
<!--start-account-->
<div class="account">
		<div class="container"> 
			<div class="account-bottom">
				<!-- Thông tin tài khoản -->
				<div class="col-md-5 account-left">
					<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
						<div class="account-top heading">
							<h3>Thông tin tài khoản</h3>
							<br>
							<a class="btn btn-info" id="a_doiMatKhau" href="#">Đổi mật khẩu</a> 
							<a class="btn btn-info" id="a_doiThongTinTaiKhoan" href="#">Chỉnh sửa thông tin tài khoản</a>
						</div>
						<div class="address">
							<span>Tên đăng nhập</span>
							<input id="tenDangNhap" type="hidden" value="<?php echo $cot["TenDangNhap"]; ?>">
							<b><input type="text" value="<?php echo $cot["TenDangNhap"]; ?>" disabled></b>
						</div>
						<div class="address">
							<span>Họ tên</span>
							<b><input type="text" value="<?php echo $cot["HoTen"]; ?>" disabled></b>
						</div>
						<div class="address">
							<span>Ngày sinh</span>
							<b><input type="text" value="<?php echo date("d/m/Y", strtotime($cot["NgaySinh"])); ?>" disabled></b>
						</div>
						<div class="address">
							<span>Giới tính</span>
							<b><input type="text" value="<?php echo $cot["GioiTinh"]; ?>" disabled></b>
						</div>
						<div class="address">
							<span>Địa chỉ</span>
							<b><input type="text" value="<?php echo $cot["DiaChi"]; ?>" disabled></b>
						</div>
						<div class="address">
							<span>Điện thoại</span>
							<b><input type="text" value="<?php echo $cot["DienThoai"]; ?>" disabled></b>
						</div>
						<div class="address">
							<span>Email</span>
							<b><input type="text" value="<?php echo $cot["Email"]; ?>" disabled></b>
						</div>
					</form>
				</div>
				<div class="col-md-2"></div>

				<!-- Đổi mật khẩu -->
				<div class="col-md-5 account-left div_doiMatKhau">
					<div style="margin-top: 55px;" class="account-top heading">
						<h3>Đổi mật khẩu</h3>
					</div>
					<div class="address">
						<span>Mật khẩu cũ</span>
						<input id="matKhauCu" type="password">
					</div>
					<div class="address">
						<span>Mật khẩu mới</span>
						<input id="matKhauMoi" type="password">
					</div>
					<div class="address">
						<span>Nhập lại mật khẩu mới</span>
						<input id="matKhauMoiNhapLai" type="password">
					</div>
					<br>
					<input class="btn btn-danger" id="doiMatKhau" type="submit" value="Đổi mật khẩu">
				</div>
				
				<!-- Chỉnh sửa thông tin -->
				<div class="col-md-5 account-left div_suaThongTinTaiKhoan">
					<form style="margin-top: 55px;" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
						<input name="tenDangNhap" id="tenDangNhap" type="hidden" value="<?php echo $cot["TenDangNhap"]; ?>">
						<div class="account-top heading">
							<h3>Chỉnh sửa thông tin</h3>
						</div>
						<div class="address">
							<span>Họ tên</span>
							<input id="hoTen" name="hoTen" type="text" value="<?php echo $cot["HoTen"]; ?>">
						</div>
						<div class="address">
							<span>Ngày sinh</span>
							<input id="ngaySinh" name="ngaySinh" type="date" value="<?php echo $cot["NgaySinh"]; ?>">
						</div>
						<div class="address">
							<span>Giới tính</span>
							<?php if($cot["GioiTinh"] == "Nam"){ ?>
						
								<input name="gioiTinh" type="radio" value="Nam" checked> Nam 
								<input name="gioiTinh" type="radio" value="Nữ"> Nữ
							<?php } else{ ?>
								<input name="gioiTinh" type="radio" value="Nam"> Nam 
								<input name="gioiTinh" type="radio" value="Nữ" checked> Nữ
							<?php } ?>
							
						</div>
						<div class="address">
							<span>Địa chỉ</span>
							<input id="diaChi" name="diaChi" type="text" value="<?php echo $cot["DiaChi"]; ?>">
						</div>
						<div class="address">
							<span>Điện thoại</span>
							<input id="dienThoai" name="dienThoai" type="text" value="<?php echo $cot["DienThoai"]; ?>">
						</div> <br>
						<input class="btn btn-danger" id="suaThongTinTaiKhoan" type="submit" value="Cập nhật">
					</form>
				</div>
			</div>
		</div>
	</div>
	<!--end-account-->

<script>

	$(('#a_doiMatKhau')).click(function(event){
		event.preventDefault(); // Ngăn chặn hành động mặc định của thẻ a
		$('.div_doiMatKhau').show();
		$('.div_suaThongTinTaiKhoan').hide();
	});

	$(('#a_doiThongTinTaiKhoan')).click(function(event){
		event.preventDefault(); 
		$('.div_doiMatKhau').hide();
		$('.div_suaThongTinTaiKhoan').show();
	});

	$(document).ready(function(){ 
		// ĐỔI MẬT KHẨU
		$('#doiMatKhau').click(function(){
			let matKhauCu = $('#matKhauCu').val();
			let matKhauMoi = $('#matKhauMoi').val();
			let matKhauMoiNhapLai = $('#matKhauMoiNhapLai').val();
			let soLoi = 0;

			if(matKhauCu == "" || matKhauMoi == ""){
				soLoi++;
				toastr.error("Hãy nhập đầy đủ thông tin!");
			}
			else if (matKhauMoi.length < 6) {
				soLoi++;
				toastr.error("Mật khẩu phải có ít nhất 6 ký tự!");
			}
			else if(matKhauMoi != matKhauMoiNhapLai){
				soLoi++;
				toastr.error("Mật khẩu không trùng khớp!");
			}
			else if(matKhauCu == matKhauMoi){
				soLoi++;
				toastr.error("Mật khẩu mới phải khác mật khẩu cũ!");
			}

			if(soLoi != 0){
				return false;
			} else {
				let tenDangNhap = $('#tenDangNhap').val();
				doi_matKhau(tenDangNhap, matKhauCu, matKhauMoi); 
			}
		});

		// CHỈNH SỬA THÔNG TIN TÀI KHOẢN
		$('#suaThongTinTaiKhoan').click(function(){
			let hoTen = $('#hoTen').val();
			let ngaySinh = $('#ngaySinh').val();
			let diaChi = $('#diaChi').val();
			let dienThoai = $('#dienThoai').val();
			let email = $('#email').val();

			if(hoTen == "" || ngaySinh == "" || diaChi == "" || dienThoai == "" || email == ""){
				toastr.error("Hãy nhập đầy đủ thông tin!");
				return false;
			}
			else if(isNaN(dienThoai) || dienThoai.length < 10){
				toastr.error("Số điện thoại không hợp lệ!");
				return false;
			}
		});
	});
</script>

<?php
	if($_SERVER["REQUEST_METHOD"] == "POST"){
		$tenDangNhap = $_POST["tenDangNhap"];
		$hoTen = $_POST["hoTen"]; 
		$ngaySinh = $_POST["ngaySinh"]; 
		$gioiTinh =$_POST["gioiTinh"];
		$diaChi = $_POST["diaChi"];
		$dienThoai = $_POST["dienThoai"]; 

		$capNhatThongTin = "UPDATE nguoidung 		
							SET HoTen = '$hoTen' , NgaySinh = '$ngaySinh' , GioiTinh = '$gioiTinh' , DiaChi = '$diaChi' , DienThoai = '$dienThoai'  
		 					WHERE TenDangNhap = '$tenDangNhap'";
		if(mysqli_query($conn, $capNhatThongTin)){
			$_SESSION['toastr'] = ['type' => 'success', 'message' => 'Chỉnh sửa thông tin tài khoản thành công!'];
			echo "<script> window.location.href = './ThongTinTaiKhoan.php'; </script>";
			exit;
		}
		else{
			$_SESSION['toastr'] = ['type' => 'error', 'message' => 'Đã xảy ra lỗi: ' . mysqli_error($conn)];
			echo "<script> window.location.href = './ThongTinTaiKhoan.php'; </script>";
			exit;
		}
	}
?>

<?php include('../shared/footer.php'); ?>
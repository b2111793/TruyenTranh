<?php 
    include('../shared/header.php');
    // Kiểm tra trạng thái đăng nhập
    if (isset($_SESSION["tenDangNhap"])) {
        if ($_SESSION["quyen"] == "Admin") {
            echo "<script> window.location.href = '../admin/TrangChuAdmin.php'; </script>";
            exit;
        } else {
            echo "<script> window.location.href = './TrangChu.php'; </script>";
            exit;
        }
    }

// Xử lý đăng ký
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tenDangNhap = mysqli_real_escape_string($conn, $_POST["tenDangNhap"]);
    $matKhau = $_POST["matKhau"];
    $matKhauNhapLai = $_POST["matKhauNhapLai"];
    $hoTen = !empty($_POST["hoTen"]) ? mysqli_real_escape_string($conn, $_POST["hoTen"]) : NULL;
    $ngaySinh = !empty($_POST["ngaySinh"]) ? mysqli_real_escape_string($conn, $_POST["ngaySinh"]) : NULL;
    $gioiTinh = !empty($_POST["gioiTinh"]) ? mysqli_real_escape_string($conn, $_POST["gioiTinh"]) : NULL;
    $diaChi = !empty($_POST["diaChi"]) ? mysqli_real_escape_string($conn, $_POST["diaChi"]) : NULL;
    $dienThoai = !empty($_POST["dienThoai"]) ? mysqli_real_escape_string($conn, $_POST["dienThoai"]) : NULL;
    $email = mysqli_real_escape_string($conn, $_POST["email"]);
    $trangThai = "Kích hoạt"; // Trạng thái mặc định
    $quyen = "Member"; // Quyền mặc định cho người đăng ký

    // Mã hóa mật khẩu sau khi kiểm tra
    $matKhau = password_hash($matKhau, PASSWORD_DEFAULT);

    // Kiểm tra trùng TenDangNhap và Email
    $kiemTraTonTai = "SELECT * FROM nguoidung WHERE TenDangNhap = '$tenDangNhap' OR Email = '$email'";
    $truyVan_KiemTraTonTai = mysqli_query($conn, $kiemTraTonTai);

    if (mysqli_num_rows($truyVan_KiemTraTonTai) > 0) {
        $row = mysqli_fetch_assoc($truyVan_KiemTraTonTai);
        if ($row['TenDangNhap'] == $tenDangNhap) {
            $_SESSION['toastr'] = ['type' => 'info', 'message' => 'Tài khoản đã tồn tại!'];
            echo "<script> window.location.href = './DangKy.php'; </script>";
            exit;
        } else {
            $_SESSION['toastr'] = ['type' => 'info', 'message' => 'Email đã được sử dụng!'];
            echo "<script> window.location.href = './DangKy.php'; </script>";
            exit;
        }
		echo "<script> window.location.href = './DangKy.php'; </script>";
		exit;
    } else {
        // Thêm người dùng mới
        $themNguoiDung = "INSERT INTO nguoidung (TenDangNhap, MatKhau, HoTen, NgaySinh, GioiTinh, DiaChi, DienThoai, Email, TrangThai, Quyen) 
                          VALUES ('$tenDangNhap', '$matKhau', " . ($hoTen ? "'$hoTen'" : "NULL") . ", " . ($ngaySinh ? "'$ngaySinh'" : "NULL") . ", 
                          " . ($gioiTinh ? "'$gioiTinh'" : "NULL") . ", " . ($diaChi ? "'$diaChi'" : "NULL") . ", " . ($dienThoai ? "'$dienThoai'" : "NULL") . ", 
                          '$email', '$trangThai', '$quyen')";
        $truyVan_ThemNguoiDung = mysqli_query($conn, $themNguoiDung);
        if ($truyVan_ThemNguoiDung) {
            $_SESSION['toastr'] = ['type' => 'success', 'message' => 'Đăng ký thành công! Vui lòng đăng nhập.'];
            echo "<script> window.location.href = '../shared/DangNhap.php'; </script>";
			exit;
        } else {
            $_SESSION['toastr'] = ['type' => 'error', 'message' => 'Đăng ký thất bại: ' . mysqli_error($conn)];
            echo "<script> window.location.href = './DangKy.php'; </script>";
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
                <li><a href="TrangChu.php">Trang chủ</a></li>
                <li class="active">Đăng ký</li>
            </ol>
        </div>
    </div>
</div>
<!--end-breadcrumbs-->

<!--start-account-->
<div class="account">
    <div class="container"> 
        <div class="account-bottom">
            <!-- Đăng ký -->
            <div class="col-md-1"></div>
            <div class="col-md-6 account-left">
                <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <div class="account-top heading">
                        <h3>Đăng ký tài khoản</h3>
                    </div>
                    <div class="address">
                        <span>Tên đăng nhập <span style="color: red;">*</span></span>
                        <input id="tenDangNhap" name="tenDangNhap" type="text" required>
                    </div>
                    <div class="address">
                        <span>Mật khẩu <span style="color: red;">*</span></span>
                        <input id="matKhau" name="matKhau" type="password" required>
                    </div>
                    <div class="address">
                        <span>Nhập lại mật khẩu <span style="color: red;">*</span></span>
                        <input id="matKhauNhapLai" name="matKhauNhapLai" type="password" required>
                    </div>
                    <div class="address">
                        <span>Họ tên</span>
                        <input id="hoTen" name="hoTen" type="text">
                    </div>
                    <div class="address">
                        <span>Ngày sinh</span>
                        <input id="ngaySinh" name="ngaySinh" type="date">
                    </div>
                    <div class="address">
                        <span>Giới tính</span>
                        <input name="gioiTinh" type="radio" value="Nam" checked> Nam
                        <input name="gioiTinh" type="radio" value="Nữ"> Nữ
                    </div>
                    <div class="address">
                        <span>Địa chỉ</span>
                        <input id="diaChi" name="diaChi" type="text">
                    </div>
                    <div class="address">
                        <span>Điện thoại <span style="color: red;">*</span></span>
                        <input id="dienThoai" name="dienThoai" type="text">
                    </div>
                    <div class="address">
                        <span>Email <span style="color: red;">*</span></span>
                        <input id="email" name="email" type="text" required>
                    </div>
                    <div style="margin-top: 20px; float: right">
                        <input class="btn btn-danger"  id="dangKy" type="submit" value="Đăng ký">
                    </div>
                </form>
            </div>
            <div class="col-md-1"></div>
            <!-- Thông tin đăng nhập -->
            <div class="col-md-4 account-left">
                <div class="account-top heading">
                    <h3>Bạn đã có tài khoản?</h3>
                </div>
                <div class="address">
                    <p>Đăng nhập để trải nghiệm ngay!</p>
                    <a style="color: white;" class="btn btn-danger" href="../shared/DangNhap.php">Đăng nhập</a>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
</div>
<!--end-account-->

<script>
    function isValidEmail(email) {
        const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
        return emailRegex.test(email);
    }

    $(document).ready(function () {
        $('#dangKy').click(function () {
            let tenDangNhap = $('#tenDangNhap').val();
            let matKhau = $('#matKhau').val();
            let matKhauNhapLai = $('#matKhauNhapLai').val();
            let dienThoai = $('#dienThoai').val();
            let email = $('#email').val();

            if (tenDangNhap == "" || matKhau == "" || matKhauNhapLai == "" || email == "" || dienThoai == "") {
                toastr.error("Hãy nhập đầy đủ các trường bắt buộc!");
                return false;
            } else if (matKhau.length < 6) {
                toastr.error("Mật khẩu phải có ít nhất 6 ký tự!");
                return false;
            } else if (matKhau != matKhauNhapLai) {
                toastr.error("Mật khẩu không trùng khớp!");
                return false;
            } else if (isNaN(dienThoai) || dienThoai.length < 10) {
                toastr.error("Số điện thoại không hợp lệ!");
                return false;
            } else if (!isValidEmail(email)) {
                toastr.error("Email không hợp lệ!");
                return false;
            }
        });
    });
</script>

<?php include('../shared/footer.php'); ?>
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

    // Khởi tạo biến để lưu thông báo
    $thongBao = "";
    $thoiGianConLai = 0; // Thời gian còn lại (tính bằng giây)
    $hetHan = false; // Biến kiểm tra xem mã OTP đã hết hạn chưa

    // Kiểm tra xem email có được lưu trong session không
    if (!isset($_SESSION['email_quen_matkhau'])) {
        $thongBao = "Yêu cầu không hợp lệ! Vui lòng yêu cầu mã OTP trước.";
        echo "<script> window.location.href = './QuenMatKhau.php'; </script>";
        exit;
    }

    $email = $_SESSION['email_quen_matkhau'];

    // Tìm TenDangNhap từ email
    $kiemTraEmail = "SELECT TenDangNhap FROM nguoidung WHERE Email = '$email'";
    $truyVan_KiemTraEmail = mysqli_query($conn, $kiemTraEmail);
    if (mysqli_num_rows($truyVan_KiemTraEmail) == 0) {
        $thongBao = "Email không tồn tại trong hệ thống!";
        unset($_SESSION['email_quen_matkhau']);
        echo "<script> window.location.href = './QuenMatKhau.php'; </script>";
        exit;
    }
    $row = mysqli_fetch_assoc($truyVan_KiemTraEmail);
    $tenDangNhap = $row['TenDangNhap'];

    // Kiểm tra mã OTP có tồn tại và lấy thời gian hết hạn
    $kiemTraOtp = "SELECT thoiGianHetHan FROM otp_matkhau WHERE TenDangNhap = '$tenDangNhap'";
    $truyVan_KiemTraOtp = mysqli_query($conn, $kiemTraOtp);

    if (mysqli_num_rows($truyVan_KiemTraOtp) > 0) {
        $row = mysqli_fetch_assoc($truyVan_KiemTraOtp);
        $thoiGianHetHan = strtotime($row['thoiGianHetHan']);
        $thoiGianHienTai = strtotime(date("Y-m-d H:i:s"));

        // Tính thời gian còn lại (tính bằng giây)
        $thoiGianConLai = max(0, $thoiGianHetHan - $thoiGianHienTai);

        // Kiểm tra xem mã OTP đã hết hạn chưa
        if ($thoiGianConLai <= 0) {
            $hetHan = true;
            $thongBao = "Mã OTP đã hết hạn! Vui lòng yêu cầu mã mới.";
            // Xóa OTP hết hạn
            $xoaOtp = "DELETE FROM otp_matkhau WHERE TenDangNhap = '$tenDangNhap'";
            mysqli_query($conn, $xoaOtp);
        }
    } else {
        $thongBao = "Không tìm thấy mã OTP! Vui lòng yêu cầu mã mới.";
        unset($_SESSION['email_quen_matkhau']);
        echo "<script> window.location.href = './QuenMatKhau.php'; </script>";
        exit;
    }

    // Xử lý đặt lại mật khẩu (chỉ nếu mã OTP chưa hết hạn)
    if (!$hetHan && $_SERVER["REQUEST_METHOD"] == "POST") {
        $otp = mysqli_real_escape_string($conn, $_POST["otp"]);
        $matKhauMoi = $_POST["matKhauMoi"];
        $matKhauNhapLai = $_POST["matKhauNhapLai"];

        // Kiểm tra các trường bắt buộc
        if (empty($otp) || empty($matKhauMoi) || empty($matKhauNhapLai)) {
            $thongBao = "Vui lòng nhập đầy đủ thông tin!";
        }
        // Kiểm tra độ dài mật khẩu
        elseif (strlen($matKhauMoi) < 6) {
            $thongBao = "Mật khẩu phải có ít nhất 6 ký tự!";
        }
        // Kiểm tra mật khẩu nhập lại
        elseif ($matKhauMoi !== $matKhauNhapLai) {
            $thongBao = "Mật khẩu không trùng khớp!";
        } else {
            // Kiểm tra mã OTP
            $kiemTraOtp = "SELECT * FROM otp_matkhau WHERE TenDangNhap = '$tenDangNhap' AND otp = '$otp'";
            $truyVan_KiemTraOtp = mysqli_query($conn, $kiemTraOtp);

            if (mysqli_num_rows($truyVan_KiemTraOtp) > 0) {
                $row = mysqli_fetch_assoc($truyVan_KiemTraOtp);
                $thoiGianHetHan = strtotime($row['thoiGianHetHan']);
                $thoiGianHienTai = strtotime(date("Y-m-d H:i:s"));

                // Kiểm tra OTP có hết hạn không
                if ($thoiGianHienTai > $thoiGianHetHan) {
                    $thongBao = "Mã OTP đã hết hạn! Vui lòng yêu cầu mã mới.";
                    // Xóa OTP hết hạn
                    $xoaOtp = "DELETE FROM otp_matkhau WHERE TenDangNhap = '$tenDangNhap'";
                    mysqli_query($conn, $xoaOtp);
                } else {
                    // Mã hóa mật khẩu mới
                    $matKhauMoi = password_hash($matKhauMoi, PASSWORD_DEFAULT);

                    // Cập nhật mật khẩu mới vào bảng nguoidung
                    $capNhatMatKhau = "UPDATE nguoidung SET MatKhau = '$matKhauMoi' WHERE TenDangNhap = '$tenDangNhap'";
                    $truyVan_CapNhatMatKhau = mysqli_query($conn, $capNhatMatKhau);

                    if ($truyVan_CapNhatMatKhau) {
                        // Xóa OTP sau khi đặt lại mật khẩu thành công
                        $xoaOtp = "DELETE FROM otp_matkhau WHERE TenDangNhap = '$tenDangNhap'";
                        mysqli_query($conn, $xoaOtp);

                        // Xóa session email
                        unset($_SESSION['email_quen_matkhau']);
                        $_SESSION['toastr'] = ['type' => 'success', 'message' => 'Đặt lại mật khẩu thành công! Vui lòng đăng nhập.'];
                        echo "<script> window.location.href = '../shared/DangNhap.php'; </script>";
                        exit;
                    } else {
                        $thongBao = "Có lỗi xảy ra. Vui lòng thử lại sau!";
                    }
                }
            } else {
                $thongBao = "Mã OTP không đúng!";
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
                <li class="active">Đặt lại mật khẩu</li>
            </ol>
        </div>
    </div>
</div>
<!--end-breadcrumbs-->

<!--start-account-->
<div class="account">
    <div class="container"> 
        <div class="account-bottom">
            <!-- Đặt lại mật khẩu -->
            <div class="col-md-12 account-left">
                <?php if ($hetHan) { ?>
                    <div class="account-top heading">
                        <h3>Đặt lại mật khẩu</h3>
                    </div>
                    <div class="address">
                        <span style="color: red;"><?php echo $thongBao; ?></span>
                        <div class="address new">
                            <a href="./QuenMatKhau.php" class="btn btn-primary">Yêu cầu mã OTP mới</a>
                        </div>
                    </div>
                <?php } else { ?>
                    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                        <div class="account-top heading">
                            <h3>Đặt lại mật khẩu</h3>
                        </div>
                        <div class="address">
                            <p>Thời gian còn lại: <span id="countdown" style="color: red; font-weight: bold;"></span></p>
                            <label for="otp">Mã OTP:</label>
                            <input style="width: 50%; height: 40px;" type="text" name="otp" id="otp" required>
                        </div>
                        <div class="address">
                            <label for="matKhauMoi">Mật khẩu mới:</label>
                            <input style="width: 50%; height: 40px;" type="password" name="matKhauMoi" id="matKhauMoi" required>
                        </div>
                        <div class="address">
                            <label for="matKhauNhapLai">Nhập lại mật khẩu:</label>
                            <input style="width: 50%; height: 40px;" type="password" name="matKhauNhapLai" id="matKhauNhapLai" required>
                        </div>
                        <div class="address">
                            <span style="color: red;"><?php echo $thongBao; ?></span>
                        </div>
                        <div class="address">
                            <button type="submit" class="btn btn-primary" id="submitBtn">Đặt lại mật khẩu</button>
                        </div>
                    </form>
                <?php } ?>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
</div>
<!--end-account-->

<?php include('../shared/footer.php'); ?>

<script>
    // Lấy thời gian còn lại từ PHP (tính bằng giây)
    let timeLeft = <?php echo $thoiGianConLai; ?>;

    // Lấy phần tử hiển thị thời gian
    const countdownElement = document.getElementById('countdown');
    const submitBtn = document.getElementById('submitBtn');

    // Hàm định dạng thời gian (phút:giây)
    function formatTime(seconds) {
        const minutes = Math.floor(seconds / 60);
        const secs = seconds % 60;
        return `${minutes}:${secs < 10 ? '0' : ''}${secs}`;
    }

    // Hàm đếm ngược
    function startCountdown() {
        if (timeLeft <= 0) {
            countdownElement.innerHTML = "Mã OTP đã hết hạn!";
            if (submitBtn) {
                submitBtn.disabled = true; // Vô hiệu hóa nút submit
            }
            return;
        }

        countdownElement.innerHTML = formatTime(timeLeft);
        timeLeft--;

        setTimeout(startCountdown, 1000); // Cập nhật mỗi giây
    }

    // Bắt đầu đếm ngược
    startCountdown();
</script>
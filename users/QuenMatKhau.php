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

    // Sử dụng PHPMailer
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    require '../PHPMailer/src/Exception.php';
    require '../PHPMailer/src/PHPMailer.php';
    require '../PHPMailer/src/SMTP.php';

    // Khởi tạo biến để lưu thông báo
    $thongBao = "";
    $email = "";

    // Xử lý yêu cầu quên mật khẩu
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = mysqli_real_escape_string($conn, $_POST["email"]);

        // Kiểm tra email có hợp lệ không
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $thongBao = "Email không hợp lệ!";
        } else {
            // Kiểm tra email có tồn tại trong bảng nguoidung không
            $kiemTraEmail = "SELECT * FROM nguoidung WHERE Email = '$email'";
            $truyVan_KiemTraEmail = mysqli_query($conn, $kiemTraEmail);

            if (mysqli_num_rows($truyVan_KiemTraEmail) > 0) {
                $row = mysqli_fetch_assoc($truyVan_KiemTraEmail);
                $tenDangNhap = $row['TenDangNhap'];

                // Tạo mã OTP 6 chữ số
                $otp = str_pad(rand(0, 999999), 6, "0", STR_PAD_LEFT); 
                $thoiGianHetHan = date("Y-m-d H:i:s", strtotime("+3 minutes")); // OTP hết hạn sau 3 phút

                // Xóa các OTP cũ của người dùng (nếu có)
                $xoaOtpCu = "DELETE FROM otp_matkhau WHERE TenDangNhap = '$tenDangNhap'";
                mysqli_query($conn, $xoaOtpCu);

                // Lưu OTP vào bảng otp_matkhau
                $themOtp = "INSERT INTO otp_matkhau (TenDangNhap, otp, thoiGianHetHan) 
                            VALUES ('$tenDangNhap', '$otp', '$thoiGianHetHan')";
                $truyVan_ThemOtp = mysqli_query($conn, $themOtp);

                if ($truyVan_ThemOtp) {
                    // Gửi email chứa mã OTP bằng PHPMailer
                    $mail = new PHPMailer(true);
                    try {
                        // Cấu hình SMTP
                        $mail->isSMTP();
                        $mail->Host = 'smtp.gmail.com'; // SMTP server của Gmail
                        $mail->SMTPAuth = true;
                        $mail->Username = 'theonlytruth1412@gmail.com'; // Email của bạn
                        $mail->Password = 'aevc erya ziqq ukna'; // Mật khẩu ứng dụng (App Password) của Gmail
                        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                        $mail->Port = 587;

                        // Thiết lập thông tin email
                        $mail->CharSet = 'UTF-8'; // Đặt mã hóa UTF-8
                        $mail->setFrom('Theonlytruth1412@gmail.com', 'Website truyện tranh');
                        $mail->addAddress($email);
                        $mail->Subject = "Mã OTP để đặt lại mật khẩu";
                        $mail->Body = "Chào bạn,\n\nMã OTP để đặt lại mật khẩu của bạn là: $otp\n\nMã này sẽ hết hạn sau 3 phút.\n\nTrân trọng,\nHệ thống Website truyện tranh";

                        // Gửi email
                        $mail->send();

                        // Lưu email vào session để sử dụng ở trang DatLaiMatKhau.php
                        $_SESSION['email_quen_matkhau'] = $email;
                        echo "<script> window.location.href = './DatLaiMatKhau.php'; </script>";
                        exit;
                    } catch (Exception $e) {
                        $thongBao = "Gửi email thất bại. Lỗi: {$mail->ErrorInfo}";
                    }
                } else {
                    $thongBao = "Có lỗi xảy ra. Vui lòng thử lại sau!";
                }
            } else {
                $thongBao = "Email không tồn tại trong hệ thống!";
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
                <li class="active">Quên mật khẩu</li>
            </ol>
        </div>
    </div>
</div>
<!--end-breadcrumbs-->

<!--start-account-->
<div class="account">
    <div class="container"> 
        <div class="account-bottom">
            <!-- Lấy lại mật khẩu -->
            <div class="col-md-12 account-left">
                <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <input type="hidden" name="trangHienTai" value="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>"/>
                    <div class="account-top heading">
                        <h3>Quên mật khẩu</h3>
                    </div>
                    <div class="address">
                        <p>Nhập email đăng ký để đặt lại mật khẩu</p>
                        <label for="quenMatKhau">Email đăng ký:</label>
                        <input style="width: 50%; height: 40px;" type="email" name="email" id="quenMatKhau" value="<?php echo htmlspecialchars($email); ?>" required>
                        <div class="address">
                            <span style="color: red;"><?php echo $thongBao; ?></span>
                        </div>
                        <button type="submit" class="btn btn-primary">Gửi Yêu Cầu</button>
                    </div>
                </form>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
</div>
<!--end-account-->

<?php include('../shared/footer.php'); ?>
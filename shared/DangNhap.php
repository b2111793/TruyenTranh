<?php
    session_start();
    include(__DIR__ . '/database.php');

    // Kiểm tra trạng thái đăng nhập
    if (isset($_SESSION["tenDangNhap"])) {
        if ($_SESSION["quyen"] == "Admin") {
            echo "<script> window.location.href = '../admin/TrangChuAdmin.php'; </script>";
            exit;
        } else {
            echo "<script> window.location.href = '../users/TrangChu.php'; </script>";
            exit;
        }
    }
?>

<!DOCTYPE html>
<html>
<head>
    <title>Truyện tranh - Đăng nhập</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="../css/authenticationadmin.css" rel="stylesheet" /> 
    <script src="../js/jquery-1.11.0.min.js"></script>
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
</head>
<body>
    <div class="dangNhap">
        <form method="POST" action="./XuLyDangNhap.php">
            <div class="taiKhoan">
                <h3>Đăng nhập tài khoản</h3>
            </div>
            <div class="diaChi">
                <span>Tên đăng nhập</span>
                <input id="tenDangNhap" name="tenDangNhap" type="text" required>
            </div>
            <div class="diaChi">
                <span>Mật khẩu</span>
                <input id="matKhau" name="matKhau" type="password" required>
            </div>
            <div class="diaChi">
                <a class="quenMatKhau" href="../users/QuenMatKhau.php">Quên mật khẩu?</a>
                <input id="dangNhap" type="submit" value="Đăng nhập">
                <input type="hidden" name="trangHienTai" value="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
            </div>
            <div class="diaChi">
                <span style="float: right">Chưa có tài khoản? <a href="../users/Dangky.php">Đăng ký ngay</a></span>
            </div>
        </form>
    </div>

    <script>
       $(document).ready(function(){
            $('#dangNhap').click(function(){
                var tenDangNhap = $('#tenDangNhap').val();
                var matKhau = $('#matKhau').val();

                if (tenDangNhap == "" || matKhau == "") {
                    toastr.warning("Hãy nhập đầy đủ thông tin!");
                    return false;
                }
            });
        });
    </script>

    	    <!-- Hiển thị thông báo Toastr -->
<?php if (isset($_SESSION['toastr'])): ?>
    <script>
        $(document).ready(function() {
            var type = "<?php echo $_SESSION['toastr']['type']; ?>";
            var message = "<?php echo $_SESSION['toastr']['message']; ?>";
            switch(type) {
                case 'success':
                    toastr.success(message);
                    break;
                case 'error':
                    toastr.error(message);
                    break;
                case 'warning':
                    toastr.warning(message);
                    break;
                case 'info':
                    toastr.info(message);
                    break;
                default:
                    toastr.info(message);
                    break;
            }
        });
    </script>
    <?php unset($_SESSION['toastr']); // Xóa thông báo sau khi hiển thị ?>
<?php endif; ?>

</body>
</html>
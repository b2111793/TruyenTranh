<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Truyện tranh</title>
       
        <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
        <link href="../css/bootstrap523.css" rel="stylesheet" />
        <link href="../css/stylesadmin.css" rel="stylesheet" />
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <!-- <script src="../js/jquery-1.11.0.min.js"></script> -->
        <script src="../js/scriptAdmin.js"></script>

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

    <?php
		session_start();
        include(__DIR__ . '/database.php');
    ?>

    <body class="sb-nav-fixed">
        <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
            <!-- Navbar Brand-->
            <a class="navbar-brand ps-3" href="../admin/Admin.php"><b>Admin</b></a>
            <!-- Sidebar Toggle-->
            <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!">
                <i class="fas fa-bars"></i></button>

            <!-- Navbar-->
            <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4" style="margin-left: auto !important;">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" 
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-user fa-fw"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="./Admin.php"><i class="fas fa-user me-2"></i>Admin</a></li>
                        <li><hr class="dropdown-divider"/></li>
                        <li><a href="#" class="dropdown-item btn btn-danger btn-dang-xuat-admin">
                                <i class="fas fa-sign-out-alt me-2"></i>Đăng xuất</a>
                        </li>
                    </ul>
                </li>
            </ul>
        </nav>
        <script>
            // Gán sự kiện click trực tiếp cho nút "Đăng xuất"
            $(document).ready(function(){
                $('.btn-dang-xuat-admin').on('click', function(e){
                    e.preventDefault(); // Ngăn chuyển hướng mặc định
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
                            // Chuyển hướng đến trang xóa
                            window.location.href = '../shared/DangXuat.php';
                        }
                    });
                });
            });
        </script>
        <div id="layoutSidenav">
            <div id="layoutSidenav_nav">
                <!-- sb-sidenav-light để nền trắng -->
                <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                    <div class="sb-sidenav-menu">
                        <div class="nav">
                            <!-- Trang chủ -->
                            <a class="nav-link" href="TrangChuAdmin.php"><i class="fas fa-home me-1"></i>Trang chủ</a>

                            <!-- QUẢN LÝ  -->
                            <div class="sb-sidenav-menu-heading">Quản lý</div>
                            <a class="nav-link" href="../admin/QuanLyDanhMuc.php"><i class="fas fa-tags me-2"></i>Danh mục</a>
                            <a class="nav-link" href="../admin/QuanLySanPham.php"><i class="fas fa-book me-2"></i>Sản phẩm</a>
                            <a class="nav-link" href="../admin/QuanLyThanhVien.php"><i class="fas fa-users me-2"></i>Thành viên</a>
                            <a class="nav-link" href="../admin/QuanLyDonHang.php"><i class="fas fa-box me-2"></i>Đơn hàng</a>
                            <a class="nav-link" href="../admin/QuanLyBinhLuan.php"><i class="fas fa-comment-dots me-2"></i>Bình luận</a>

                            <!-- THỐNG KÊ -->
                            <div class="sb-sidenav-menu-heading">Thống kê</div>
                            <a class="nav-link" href="../admin/ThongKeDoanhThu.php"><i class="fas fa-dollar-sign me-2"></i>Doanh thu</a>
                            <a class="nav-link" href="../admin/ThongKeBanChay.php"><i class="fas fa-fire me-2"></i>Bán chạy</a>
                            <a class="nav-link" href="../admin/ThongKeDanhGia.php"><i class="fas fa-star me-2"></i>Đánh giá</a>
                            <a class="nav-link" href="../admin/ThongKeYeuThich.php"><i class="fas fa-heart me-2"></i>Yêu thích</a>
                        </div>
                    </div>
                    
                    <!-- <div class="sb-sidenav-footer">
                        <div class="small">Đăng nhập với tư cách:</div>
                        Admin
                    </div> -->
                </nav>
            </div>
            <div id="layoutSidenav_content">
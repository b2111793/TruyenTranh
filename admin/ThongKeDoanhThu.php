<?php
    include('../shared/headerAdmin.php');
    if (!isset($_SESSION["tenDangNhap"])) {
        echo "<script> window.location.href = '../shared/DangNhap.php'; </script>";
        exit;
    }

    if (isset($_SESSION["tenDangNhap"]) && $_SESSION["quyen"] == "Member") {
        echo "<script> window.location.href = '../users/TrangChu.php'; </script>";
        exit;
    }

    // Thống kê doanh thu theo tuần (tuần hiện tại)
    $doanhThuTuan = "SELECT SUM(ct.SoLuong * sp.DonGia) AS DoanhThuTuan 
                    FROM dondat dd 
                    JOIN ct_dondat ct ON dd.MaDonDat = ct.MaDonDat 
                    JOIN sanpham sp ON ct.MaSanPham = sp.MaSanPham 
                    WHERE dd.TrangThai = 'Đã giao' 
                    AND WEEK(dd.NgayDat, 1) = WEEK(CURDATE(), 1) 
                    AND YEAR(dd.NgayDat) = YEAR(CURDATE())";
    $truyVan_DoanhThuTuan = mysqli_query($conn, $doanhThuTuan);
    $doanhThuTuanHienTai = mysqli_fetch_assoc($truyVan_DoanhThuTuan)['DoanhThuTuan'] ?? 0;

    // Dữ liệu doanh thu theo ngày trong tuần hiện tại (cho biểu đồ)
    $doanhThuTheoNgayTrongTuan = [];
    $startOfWeek = date('Y-m-d', strtotime('monday this week')); // Ngày đầu tuần (Thứ 2)
    for ($i = 0; $i < 7; $i++) {
        $ngay = date('Y-m-d', strtotime("$startOfWeek + $i days"));
        $query = "SELECT SUM(ct.SoLuong * sp.DonGia) AS DoanhThu 
                FROM dondat dd 
                JOIN ct_dondat ct ON dd.MaDonDat = ct.MaDonDat 
                JOIN sanpham sp ON ct.MaSanPham = sp.MaSanPham 
                WHERE dd.TrangThai = 'Đã giao' 
                AND DATE(dd.NgayDat) = '$ngay'";
        $result = mysqli_query($conn, $query);
        $doanhThuTheoNgayTrongTuan[] = mysqli_fetch_assoc($result)['DoanhThu'] ?? 0;
    }

    // Thống kê doanh thu theo tháng (tháng hiện tại)
    $doanhThuThang = "SELECT SUM(ct.SoLuong * sp.DonGia) AS DoanhThuThang 
                    FROM dondat dd 
                    JOIN ct_dondat ct ON dd.MaDonDat = ct.MaDonDat 
                    JOIN sanpham sp ON ct.MaSanPham = sp.MaSanPham 
                    WHERE dd.TrangThai = 'Đã giao' 
                    AND MONTH(dd.NgayDat) = MONTH(CURDATE()) 
                    AND YEAR(dd.NgayDat) = YEAR(CURDATE())";
    $truyVan_DoanhThuThang = mysqli_query($conn, $doanhThuThang);
    $doanhThuThangHienTai = mysqli_fetch_assoc($truyVan_DoanhThuThang)['DoanhThuThang'] ?? 0;

    // Dữ liệu doanh thu theo tuần trong tháng hiện tại (cho biểu đồ)
    $doanhThuTheoTuanTrongThang = [];
    $startOfMonth = date('Y-m-01'); // Ngày đầu tháng
    $endOfMonth = date('Y-m-t'); // Ngày cuối tháng
    $startDate = new DateTime($startOfMonth);
    $endDate = new DateTime($endOfMonth);
    $interval = new DateInterval('P1W'); // 1 tuần
    $period = new DatePeriod($startDate, $interval, $endDate);

    $tuans = [];
    $index = 1;
    foreach ($period as $date) {
        $tuans[] = "Tuần $index";
        $start = $date->format('Y-m-d');
        $end = $date->modify('+6 days')->format('Y-m-d');
        $query = "SELECT SUM(ct.SoLuong * sp.DonGia) AS DoanhThu 
                FROM dondat dd 
                JOIN ct_dondat ct ON dd.MaDonDat = ct.MaDonDat 
                JOIN sanpham sp ON ct.MaSanPham = sp.MaSanPham 
                WHERE dd.TrangThai = 'Đã giao' 
                AND dd.NgayDat BETWEEN '$start' AND '$end'";
        $result = mysqli_query($conn, $query);
        $doanhThuTheoTuanTrongThang[] = mysqli_fetch_assoc($result)['DoanhThu'] ?? 0;
        $index++;
    }

    // Thống kê doanh thu theo năm (năm hiện tại)
    $doanhThuNam = "SELECT SUM(ct.SoLuong * sp.DonGia) AS DoanhThuNam 
                    FROM dondat dd 
                    JOIN ct_dondat ct ON dd.MaDonDat = ct.MaDonDat 
                    JOIN sanpham sp ON ct.MaSanPham = sp.MaSanPham 
                    WHERE dd.TrangThai = 'Đã giao' 
                    AND YEAR(dd.NgayDat) = YEAR(CURDATE())";
    $truyVan_DoanhThuNam = mysqli_query($conn, $doanhThuNam);
    $doanhThuNamHienTai = mysqli_fetch_assoc($truyVan_DoanhThuNam)['DoanhThuNam'] ?? 0;

    // Dữ liệu doanh thu theo tháng trong năm hiện tại (cho biểu đồ)
    $doanhThuTheoThangTrongNam = [];
    for ($thang = 1; $thang <= 12; $thang++) {
        $query = "SELECT SUM(ct.SoLuong * sp.DonGia) AS DoanhThu 
                FROM dondat dd 
                JOIN ct_dondat ct ON dd.MaDonDat = ct.MaDonDat 
                JOIN sanpham sp ON ct.MaSanPham = sp.MaSanPham 
                WHERE dd.TrangThai = 'Đã giao' 
                AND MONTH(dd.NgayDat) = $thang 
                AND YEAR(dd.NgayDat) = YEAR(CURDATE())";
        $result = mysqli_query($conn, $query);
        $doanhThuTheoThangTrongNam[] = mysqli_fetch_assoc($result)['DoanhThu'] ?? 0;
    }

    // Truy vấn chi tiết doanh thu (lấy thông tin từng đơn hàng)
    $chiTietDoanhThu = "SELECT dd.MaDonDat, dd.NgayDat, ct.MaSanPham, ct.SoLuong, sp.DonGia 
                        FROM dondat dd 
                        JOIN ct_dondat ct ON dd.MaDonDat = ct.MaDonDat 
                        JOIN sanpham sp ON ct.MaSanPham = sp.MaSanPham 
                        WHERE dd.TrangThai = 'Đã giao'";
    $truyVan_ChiTiet = mysqli_query($conn, $chiTietDoanhThu);
?>

<main>
    <div class="container-fluid px-4">
        <h1 class="mt-4">Thống kê</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="./TrangChuAdmin.php">Trang chủ</a></li>
            <li class="breadcrumb-item active">Doanh thu</li>
        </ol>

        <!-- Thống kê doanh thu tổng quan -->
        <div class="card mb-4">
            <div class="card-header">
                <i class="fa fa-money-bill-wave"></i> Thống kê doanh thu
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <p><strong>Doanh thu tuần này:</strong> <?php echo number_format($doanhThuTuanHienTai, 0, ',', '.'); ?> VNĐ</p>
                    </div>
                    <div class="col-md-4">
                        <p><strong>Doanh thu tháng này:</strong> <?php echo number_format($doanhThuThangHienTai, 0, ',', '.'); ?> VNĐ</p>
                    </div>
                    <div class="col-md-4">
                        <p><strong>Doanh thu năm nay:</strong> <?php echo number_format($doanhThuNamHienTai, 0, ',', '.'); ?> VNĐ</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Biểu đồ doanh thu theo ngày trong tuần -->
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-chart-line me-2"></i>Doanh thu theo ngày trong tuần hiện tại
            </div>
            <div class="card-body">
                <canvas id="doanhThuTuanChart" height="100"></canvas>
            </div>
        </div>

        <!-- Biểu đồ doanh thu theo tuần trong tháng -->
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-chart-line me-2"></i>Doanh thu theo tuần trong tháng hiện tại (Tháng <?php echo date('m/Y'); ?>)
            </div>
            <div class="card-body">
                <canvas id="doanhThuThangChart" height="100"></canvas>
            </div>
        </div>

        <!-- Biểu đồ doanh thu theo tháng trong năm -->
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-chart-line me-2"></i>Doanh thu theo tháng trong năm hiện tại (Năm <?php echo date('Y'); ?>)
            </div>
            <div class="card-body">
                <canvas id="doanhThuNamChart" height="100"></canvas>
            </div>
        </div>

        <!-- Bảng chi tiết doanh thu -->
        <div class="card mb-4">
            <div class="card-header">
                <b><i class="fas fa-box me-2"></i>Bảng chi tiết doanh thu</b>
            </div>
            <div class="card-body">
                <table id="duLieuBang">
                    <thead>
                        <tr>
                            <th>Mã đơn hàng</th>
                            <th>Ngày đặt</th>
                            <th>Mã sản phẩm</th>
                            <th>Số lượng</th>
                            <th>Đơn giá</th>
                            <th>Thành tiền</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($truyVan_ChiTiet)) { ?>
                            <tr>
                                <td class="duLieuSo"><?php echo $row['MaDonDat']; ?></td>
                                <td><?php echo $row['NgayDat']; ?></td>
                                <td class="duLieuSo"><?php echo $row['MaSanPham']; ?></td>
                                <td class="duLieuSo"><?php echo $row['SoLuong']; ?></td>
                                <td class="duLieuSo"><?php echo number_format($row['DonGia'], 0, ',', '.'); ?> VNĐ</td>
                                <td class="duLieuSo"><?php echo number_format($row['SoLuong'] * $row['DonGia'], 0, ',', '.'); ?> VNĐ</td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    $(document).ready(function(){
        // Khởi tạo DataTable
        $('#duLieuBang').DataTable({
            "lengthMenu": [5, 10, 15, 20],
            "pageLength": 5,
            "paging": true,
            "autoWidth": true,
            // "columns": [
            //     { "width": "15%" }, 
            //     { "width": "15%" },
            //     { "width": "15%" }, 
            //     { "width": "15%" },
            //     { "width": "20%" },
            //     { "width": "20%" }  
            // ],
            "language": {
                "lengthMenu": "_MENU_ mục trên mỗi trang. (Bấm vào tiêu đề cột để sắp xếp)",
                "info": "Hiển thị từ _START_ đến _END_ trong tổng số _TOTAL_ mục",
                "search": "Tìm kiếm:",
                "paginate": {
                    "previous": "Trước",
                    "next": "Tiếp theo"
                }
            }
        });

        $('#duLieuBang thead th').on('click', function() {
            $('#duLieuBang thead th').removeClass('active-sorting'); 
            $(this).addClass('active-sorting'); 
        });

        $('#duLieuBang thead th').on('click', function() {
            $(this).addClass('active-sorting'); 
            $('#duLieuBang thead th').removeClass('active-sorting');
        });

        // Hàm định dạng số tiền
        function formatCurrency(value) {
            return value.toLocaleString('vi-VN') + ' VNĐ';
        }

        // Biểu đồ doanh thu theo ngày trong tuần
        const ctxTuan = document.getElementById('doanhThuTuanChart').getContext('2d');
        const doanhThuTuanChart = new Chart(ctxTuan, {
            type: 'bar',
            data: {
                labels: ['Thứ 2', 'Thứ 3', 'Thứ 4', 'Thứ 5', 'Thứ 6', 'Thứ 7', 'Chủ nhật'],
                datasets: [{
                    label: 'Doanh thu (VNĐ)',
                    data: <?php echo json_encode($doanhThuTheoNgayTrongTuan); ?>,
                    backgroundColor: 'rgba(54, 162, 235, 0.6)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Doanh thu (VNĐ)'
                        },
                        ticks: {
                            callback: formatCurrency
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Ngày trong tuần'
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    }
                }
            }
        });

        // Biểu đồ doanh thu theo tuần trong tháng
        const ctxThang = document.getElementById('doanhThuThangChart').getContext('2d');
        const doanhThuThangChart = new Chart(ctxThang, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($tuans); ?>,
                datasets: [{
                    label: 'Doanh thu (VNĐ)',
                    data: <?php echo json_encode($doanhThuTheoTuanTrongThang); ?>,
                    backgroundColor: 'rgba(255, 159, 64, 0.6)',
                    borderColor: 'rgba(255, 159, 64, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Doanh thu (VNĐ)'
                        },
                        ticks: {
                            callback: formatCurrency
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Tuần trong tháng'
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    }
                }
            }
        });

        // Biểu đồ doanh thu theo tháng trong năm
        const ctxNam = document.getElementById('doanhThuNamChart').getContext('2d');
        const doanhThuNamChart = new Chart(ctxNam, {
            type: 'line',
            data: {
                labels: ['Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5', 'Tháng 6', 
                         'Tháng 7', 'Tháng 8', 'Tháng 9', 'Tháng 10', 'Tháng 11', 'Tháng 12'],
                datasets: [{
                    label: 'Doanh thu (VNĐ)',
                    data: <?php echo json_encode($doanhThuTheoThangTrongNam); ?>,
                    borderColor: 'rgba(75, 192, 192, 1)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Doanh thu (VNĐ)'
                        },
                        ticks: {
                            callback: formatCurrency
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Tháng'
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    }
                }
            }
        });
    });
</script>

<?php include('../shared/footerAdmin.php'); ?>
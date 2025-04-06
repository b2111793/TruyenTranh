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

    // Lấy danh sách đơn đặt
    $layDonDat =   "SELECT dd.*, tv.HoTen, tv.DienThoai, tv.Email 
                    FROM dondat dd 
                    JOIN nguoidung tv ON dd.TenDangNhap = tv.TenDangNhap 
                    ORDER BY dd.NgayDat DESC";
    $truyVan_LayDonDat = mysqli_query($conn, $layDonDat);

    // Hàm kiểm tra trạng thái hợp lệ
    function kiemTra_TrangThaiHopLe($hinhThucThanhToan, $trangThaiHienTai, $trangThaiMoi) {
        // Định nghĩa trạng thái theo hình thức thanh toán
        $luongTrangThai = [
            'Momo' => [
                1 => 'Chờ thanh toán',
                2 => 'Đã thanh toán',
                3 => 'Đang giao',
                4 => 'Đã giao',
                5 => 'Hoàn tiền',
                6 => 'Đã hủy'
            ],
            'COD' => [
                1 => 'Chờ xử lý',
                2 => 'Đã xử lý',
                3 => 'Đang giao',
                4 => 'Đã giao',
                5 => 'Hoàn tiền',
                6 => 'Đã hủy'
            ]
        ];

        // Nếu trạng thái hiện tại là "Đã hủy", không cho phép thay đổi
        if ($trangThaiHienTai === 'Đã hủy') {
            return false;
        }

        // Tìm vị trí của trạng thái hiện tại và trạng thái mới
        $viTriTrangThaiHienTai = array_search($trangThaiHienTai, $luongTrangThai[$hinhThucThanhToan]);
        $viTriTrangThaiMoi = array_search($trangThaiMoi, $luongTrangThai[$hinhThucThanhToan]);

        // Không cho phép quay lại trạng thái trước đó
        if ($viTriTrangThaiMoi <= $viTriTrangThaiHienTai && $trangThaiMoi !== 'Đã hủy') {
            return false;
        }

        // Kiểm tra hủy đơn hàng
        if ($trangThaiMoi === 'Đã hủy') {
            if ($hinhThucThanhToan === 'Momo' && $trangThaiHienTai !== 'Chờ thanh toán') {
                return false; // Momo chỉ hủy được ở trạng thái 1
            }
            if ($hinhThucThanhToan === 'COD' && !in_array($trangThaiHienTai, ['Chờ xử lý', 'Đã xử lý'])) {
                return false; // COD chỉ hủy được ở trạng thái 1 hoặc 2
            }
        }

        return true;
    }

    // Xử lý cập nhật trạng thái đơn đặt
    if (isset($_GET['action']) && $_GET['action'] == 'capNhat_trangThai' && isset($_GET['maDonDat']) && isset($_GET['trangThai'])) {
        $maDonDat = mysqli_real_escape_string($conn, $_GET['maDonDat']);
        $trangThaiMoi = mysqli_real_escape_string($conn, $_GET['trangThai']);

        // Lấy thông tin đơn hàng để kiểm tra trạng thái hiện tại và hình thức thanh toán
        $layDonHang = "SELECT TrangThai, HinhThucThanhToan FROM dondat WHERE MaDonDat = '$maDonDat'";
        $truyVan_LayDonHang = mysqli_query($conn, $layDonHang);
        $donHang = mysqli_fetch_assoc($truyVan_LayDonHang);

        $trangThaiHienTai = $donHang['TrangThai'];
        $hinhThucThanhToan = $donHang['HinhThucThanhToan'];

        // Kiểm tra trạng thái mới có hợp lệ không
        if (kiemTra_TrangThaiHopLe($hinhThucThanhToan, $trangThaiHienTai, $trangThaiMoi)) {
            $capNhatTrangThai = "UPDATE dondat SET TrangThai = '$trangThaiMoi' WHERE MaDonDat = '$maDonDat'";
            if (mysqli_query($conn, $capNhatTrangThai)) {
                $_SESSION['toastr'] = ['type' => 'success', 'message' => 'Cập nhật trạng thái đơn đặt thành công!'];
            } else {
                $_SESSION['toastr'] = ['type' => 'error', 'message' => 'Đã xảy ra lỗi khi cập nhật trạng thái: ' . mysqli_error($conn)];
            }
        } else {
            $_SESSION['toastr'] = ['type' => 'error', 'message' => 'Trạng thái không hợp lệ hoặc không được phép thay đổi!'];
        }

        echo "<script> window.location.href = './QuanLyDonHang.php'; </script>";
        exit;
    }
?>

<main>
    <div class="container-fluid px-4">
        <h1 class="mt-4">Đơn hàng</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="./TrangChuAdmin.php">Trang chủ</a></li>
            <li class="breadcrumb-item active">Quản lý đơn hàng</li>
        </ol>
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-table me-1"></i>
                Danh sách đơn đặt
            </div>
            <div class="card-body">
                <table id="duLieuBang">
                    <thead>
                        <tr>
                            <th>Mã đơn đặt</th>
                            <th>Khách hàng</th>
                            <th>Số điện thoại</th>
                            <th>Email</th>
                            <th>Địa chỉ giao</th>
                            <th>Ngày đặt</th>
                            <th>Trạng thái</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($donDat = mysqli_fetch_assoc($truyVan_LayDonDat)) {
                            $hinhThucThanhToan = $donDat['HinhThucThanhToan'];
                            $trangThaiHienTai = $donDat['TrangThai'];

                            // Định nghĩa trạng thái theo hình thức thanh toán
                            $luongTrangThai = [
                                'Momo' => [
                                    1 => 'Chờ thanh toán',
                                    2 => 'Đã thanh toán',
                                    3 => 'Đang giao',
                                    4 => 'Đã giao',
                                    5 => 'Hoàn tiền',
                                    6 => 'Đã hủy'
                                ],
                                'COD' => [
                                    1 => 'Chờ xử lý',
                                    2 => 'Đã xử lý',
                                    3 => 'Đang giao',
                                    4 => 'Đã giao',
                                    5 => 'Hoàn tiền',
                                    6 => 'Đã hủy'
                                ]
                            ];

                            // Xác định trạng thái hiện tại và các trạng thái có thể chọn
                            $viTriTrangThaiHienTai = array_search($trangThaiHienTai, $luongTrangThai[$hinhThucThanhToan]);
                            $trangThaiCoThe = [];

                            if ($trangThaiHienTai === 'Đã hủy') {
                                $trangThaiCoThe = ['Đã hủy'];
                            } else {
                                // Chỉ hiển thị trạng thái tiếp theo và trạng thái "Đã hủy" nếu hợp lệ
                                if ($hinhThucThanhToan === 'Momo' && $trangThaiHienTai === 'Chờ thanh toán') {
                                    $trangThaiCoThe[] = 'Đã hủy'; // Momo có thể hủy ở trạng thái 1
                                } elseif ($hinhThucThanhToan === 'COD' && in_array($trangThaiHienTai, ['Chờ xử lý', 'Đã xử lý'])) {
                                    $trangThaiCoThe[] = 'Đã hủy'; // COD có thể hủy ở trạng thái 1 hoặc 2
                                }

                                // Thêm trạng thái tiếp theo
                                if ($viTriTrangThaiHienTai < 5) { // Không thêm trạng thái tiếp theo nếu đã là "Hoàn tiền"
                                    $nextIndex = $viTriTrangThaiHienTai + 1;
                                    $trangThaiCoThe[] = $luongTrangThai[$hinhThucThanhToan][$nextIndex];
                                }
                            }
                        ?>
                            <tr>
                                <td class="duLieuSo"><?php echo $donDat['MaDonDat']; ?></td>
                                <td><?php echo $donDat['HoTen']; ?></td>
                                <td><?php echo $donDat['DienThoai']; ?></td>
                                <td><?php echo $donDat['Email']; ?></td>
                                <td><?php echo $donDat['NoiGiao']; ?></td>
                                <td><?php echo $donDat['NgayDat']; ?></td>
                                <td>
                                    <select onchange="window.location.href='./QuanLyDonHang.php?action=capNhat_trangThai&maDonDat=<?php echo $donDat['MaDonDat']; ?>&trangThai=' + this.value">
                                        <option value="<?php echo $trangThaiHienTai; ?>" selected><?php echo $trangThaiHienTai; ?></option>
                                        <?php foreach ($trangThaiCoThe as $trangThai) {
                                            if ($trangThai !== $trangThaiHienTai) { ?>
                                                <option value="<?php echo $trangThai; ?>"><?php echo $trangThai; ?></option>
                                        <?php }
                                        } ?>
                                    </select>
                                </td>
                                <td class="duLieuSo">
                                    <a href="ChiTietDonHang.php?maDonDat=<?php echo $donDat['MaDonDat']; ?>" class="btn btn-info btn-sm">
                                        <i class="fas fa-angle-double-down"></i> Xem chi tiết
                                    </a>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<script>
    $(document).ready(function(){
        $('#duLieuBang').DataTable({
            "lengthMenu": [5, 10, 15, 20],
            "pageLength": 5,
            "paging": true,
            "autoWidth": true,
            "language":{
                "lengthMenu": "_MENU_ mục trên mỗi trang. (Bấm vào tiêu đề cột để sắp xếp)",
                "info": "Hiển thị từ _START_ đến _END_ trong tổng số _TOTAL_ mục",
                "search": "Tìm kiếm:",
                "paginate":{
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
    });
</script>

<?php include('../shared/footerAdmin.php'); ?>
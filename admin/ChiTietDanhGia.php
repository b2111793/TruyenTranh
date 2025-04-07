<?php
    include('../shared/headerAdmin.php');

    if(!isset($_SESSION["tenDangNhap"])){
        echo "<script> window.location.href = '../shared/DangNhap.php'; </script>";
        exit;
    }

    if(isset($_SESSION["tenDangNhap"]) && $_SESSION["quyen"] == "Member"){
        echo "<script> window.location.href = '../users/TrangChu.php'; </script>";
        exit;
    }

    // Kiểm tra MaSanPham từ URL
    if (!isset($_GET['maSanPham']) || !is_numeric($_GET['maSanPham'])) {
        $_SESSION['toastr'] = ['type' => 'warning', 'message' => 'Mã sản phẩm không tồn tại'];
        echo "<script> window.location.href = './ThongKeDanhGia.php'; </script>";
        exit;
    }

    $maSanPham = intval($_GET['maSanPham']);

    // Lấy thông tin sản phẩm
    $laySanPham = "SELECT * FROM sanpham WHERE MaSanPham = $maSanPham";
    $truyVan_LaySanPham = mysqli_query($conn, $laySanPham);
    if (mysqli_num_rows($truyVan_LaySanPham) == 0) {
        echo "<script> window.location.href = './ThongKeDanhGia.php'; </script>";
        exit;
    }
    $sanPham = mysqli_fetch_assoc($truyVan_LaySanPham);

    // Lấy danh sách người dùng đã đánh giá sản phẩm
    $layNguoiDanhGia = "SELECT *
                        FROM danhgia dg 
                        JOIN nguoidung nd 
                        ON dg.TenDangNhap = nd.TenDangNhap 
                        WHERE dg.MaSanPham = $maSanPham 
                        ORDER BY dg.TenDangNhap";
    $truyVan_LayNguoiDanhGia = mysqli_query($conn, $layNguoiDanhGia);
?>

<main>
    <div class="container-fluid px-4">
        <h1 class="mt-4">Chi tiết đánh giá</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="./TrangChuAdmin.php">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="./ThongKeDanhGia.php">Thống kê đánh giá</a></li>
            <li class="breadcrumb-item active">Chi tiết đánh giá</li>
        </ol>
    
        <div class="card mb-4">
            <div class="card-header">
                <h5>Thông tin cơ bản sản phẩm</h5>
                <p><strong>Mã:</strong> <?php echo $sanPham["MaSanPham"]; ?></p>
                <p><strong>Tên:</strong> <?php echo $sanPham["TenSanPham"]; ?></p>
                <p><strong>Tác giả:</strong> <?php echo $sanPham["TacGia"]; ?></p>
                <p><strong>Xuất xứ:</strong> <?php echo $sanPham["XuatXu"]; ?></p>
                <a href="./ChiTietSanPhamAdmin.php?maSanPham=<?php echo $sanPham["MaSanPham"]; ?>">
                    <img height="200px" src="<?php echo $sanPham["Anh"]; ?>" alt="Ảnh sản phẩm">
                    <br>(Xem chi tiết hơn về sản phẩm)
                </a>
            </div>
            <div class="card-body">
                <b><i class="fas fa-star me-2"></i>Danh sách người dùng đánh giá sản phẩm:</b>
                <table id="duLieuBang">
                    <thead>
                        <tr>
                            <th>Tên đăng nhập</th>
                            <th>Họ tên</th>
                            <th>Ngày sinh</th>
                            <th>Giới tính</th>
                            <th>Địa chỉ</th>
                            <th>Số điện thoại</th>
                            <th>Email</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($truyVan_LayNguoiDanhGia)) { ?>
                            <tr>
                                <td><?php echo $row["TenDangNhap"]; ?></td>
                                <td><?php echo $row["HoTen"]; ?></td>
                                <td class="duLieuSo"><?php echo $row["NgaySinh"]; ?></td>
                                <td><?php echo $row["GioiTinh"]; ?></td>
                                <td><?php echo $row["DiaChi"]; ?></td>
                                <td class="duLieuSo"><?php echo $row["DienThoai"]; ?></td>
                                <td><?php echo $row["Email"]; ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <a href="./ThongKeDanhGia.php" class="btn btn-secondary">Quay lại</a>
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
            // "columns": [
            //     { "width": "40%" }, 
            //     { "width": "60%" }  
            // ],
            "language": {
                "lengthMenu": "_MENU_ mục trên mỗi trang",
                "info": "Hiển thị từ _START_ đến _END_ trong tổng số _TOTAL_ mục",
                "search": "Tìm kiếm:",
                "paginate": {
                    "previous": "Trước",
                    "next": "Tiếp theo"
                }
            }
        });
    });
</script>

<?php include('../shared/footerAdmin.php'); ?>
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

    // Truy vấn danh sách thành viên
    $layNguoiDung = "SELECT * FROM nguoidung";
    $truyVan_LayNguoiDung = mysqli_query($conn, $layNguoiDung);
?>

<main>
    <div class="container-fluid px-4">
        <h1 class="mt-4">Quản lý thành viên</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="./TrangChuAdmin.php">Trang chủ</a></li>
            <li class="breadcrumb-item active">Thành viên</li>
        </ol>

        <div class="card mb-4">
            <div class="card-header">
                <b><i class="fas fa-users me-1"></i> Danh sách thành viên</b>
            </div>
            <div class="card-body">
                <table id="duLieuBang">
                    <thead>
                        <tr>
                            <th>Tên đăng nhập</th>
                            <th>Họ tên</th>
                            <th>Email</th>
                            <th>Ngày sinh</th>
                            <th>Giới tính</th>
                            <th>Địa chỉ</th>
                            <th>Số điện thoại</th>
                            <th>Trạng thái</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($cot = mysqli_fetch_assoc($truyVan_LayNguoiDung)) {
                                if($cot["Quyen"] == "Admin")
                                    continue;
                        ?>
                            <tr>
                                <td class="duLieuSo"><?php echo $cot["TenDangNhap"]; ?></td>
                                <td><?php echo $cot["HoTen"]; ?></td>
                                <td><?php echo $cot["Email"]; ?></td>
                                <td class="duLieuSo"><?php echo date("d/m/Y", strtotime($cot["NgaySinh"])); ?></td>
                                <td class="duLieuSo"><?php echo $cot["GioiTinh"]; ?></td>
                                <td><?php echo $cot["DiaChi"]; ?></td>
                                <td class="duLieuSo"><?php echo $cot["DienThoai"]; ?></td>
                                <td class="duLieuSo"><?php echo $cot["TrangThai"]; ?></td>
                                <td class="duLieuSo">

                                    <a href="#" class="btn btn-danger btn-xoa-thanh-vien" data-id="<?php echo $cot["TenDangNhap"]; ?>">
                                        <i class="fas fa-trash me-1"></i> Xóa
                                    </a>
                                    <br> <br>
                                    <?php if ($cot['TrangThai'] == 'Kích hoạt') { ?>
                                        <a href="./KhoaThanhVien.php?tenDangNhap=<?php echo $cot['TenDangNhap']; ?>" class="btn btn-secondary">
                                            <i class="fas fa-lock"></i> Khóa
                                        </a>
                                    <?php } else { ?>
                                        <a href="./MoKhoaThanhVien.php?tenDangNhap=<?php echo $cot['TenDangNhap']; ?>" class="btn btn-success">
                                            <i class="fas fa-unlock"></i> Mở khóa
                                        </a>
                                    <?php } ?>
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
            "language": {
                "lengthMenu": "_MENU_ mục trên mỗi trang (Bấm vào tiêu đề cột để sắp xếp)",
                "info": "Hiển thị từ _START_ đến _END_ trong tổng số _TOTAL_ mục",
                "search": "Tìm kiếm:",
                "paginate": {
                    "previous": "Trước",
                    "next": "Tiếp theo"
                }
            }
        });

        // Sử dụng event delegation để gán sự kiện click vào nút "Xóa"
        $(document).on('click', '.btn-xoa-thanh-vien', function(e) {
            e.preventDefault(); // Ngăn chuyển hướng mặc định
            var tenDangNhap = $(this).data('id');
            Swal.fire({
                title: 'Xác nhận xóa',
                text: 'Bạn có chắc chắn muốn xóa thành viên này?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Xóa',
                cancelButtonText: 'Hủy',
                reverseButtons: true 
            }).then((result) => {
                if (result.isConfirmed) {
                    // Chuyển hướng đến trang xóa
                    window.location.href = './XoaThanhVien.php?tenDangNhap=' + tenDangNhap;
                }
            });
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
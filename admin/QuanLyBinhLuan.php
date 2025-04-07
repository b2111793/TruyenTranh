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

    // Lấy danh sách bình luận
    $layBinhLuan = "SELECT bl.MaBinhLuan, bl.NoiDung, bl.NgayBinhLuan, sp.TenSanPham, nd.HoTen 
                    FROM binhluan bl 
                    JOIN sanpham sp ON bl.MaSanPham = sp.MaSanPham 
                    JOIN nguoidung nd ON bl.TenDangNhap = nd.TenDangNhap 
                    ORDER BY bl.NgayBinhLuan DESC";
    $truyVan_LayBinhLuan = mysqli_query($conn, $layBinhLuan);
?>

<main>
    <div class="container-fluid px-4">
        <h1 class="mt-4">Bình luận</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="./TrangChuAdmin.php">Trang chủ</a></li>
            <li class="breadcrumb-item active">Quản lý bình luận</li>
        </ol>

        <div class="card mb-4">
            <div class="card-header">
                <b><i class="fas fa-comments me-2"></i>Danh sách bình luận</b>
            </div>
            <div class="card-body">
                <table id="duLieuBang">
                    <thead>
                        <tr>
                            <th>Mã bình luận</th>
                            <th>Tên sản phẩm</th>
                            <th>Người đăng</th>
                            <th>Nội dung</th>
                            <th>Ngày đăng</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($binhLuan = mysqli_fetch_assoc($truyVan_LayBinhLuan)) { ?>
                            <tr>
                                <td class="duLieuSo"><?php echo $binhLuan["MaBinhLuan"]; ?></td>
                                <td><?php echo htmlspecialchars($binhLuan["TenSanPham"]); ?></td>
                                <td><?php echo htmlspecialchars($binhLuan["HoTen"]); ?></td>
                                <td><?php echo htmlspecialchars($binhLuan["NoiDung"]); ?></td>
                                <td class="duLieuSo"><?php echo date("d/m/Y", strtotime($binhLuan["NgayBinhLuan"])); ?></td>
                                <td class="duLieuSo">
                                    <a href="#" class="btn btn-danger btn-xoa-binh-luan" data-id="<?php echo $binhLuan["MaBinhLuan"]; ?>">
                                        <i class="fas fa-trash me-2"></i>Xóa
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
            "autoWidth": false,
            "columns": [
                { "width": "10%" }, 
                { "width": "20%" }, 
                { "width": "15%" }, 
                { "width": "35%" }, 
                { "width": "15%" }, 
                { "width": "5%" }   
            ],
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

        // Sử dụng event delegation để gán sự kiện click vào nút "Xóa"
        $(document).on('click', '.btn-xoa-binh-luan', function(e) {
            e.preventDefault(); // Ngăn chuyển hướng mặc định
            var maBinhLuan = $(this).data('id');

            Swal.fire({
                title: 'Xác nhận xóa',
                text: 'Bạn có chắc chắn muốn xóa bình luận này?',
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
                    window.location.href = './XoaBinhLuan.php?maBinhLuan=' + maBinhLuan;
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

<?php 
include('../shared/footerAdmin.php');
?>
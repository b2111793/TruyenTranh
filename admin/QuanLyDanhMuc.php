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

    $layLoaiSP = "SELECT * FROM loaisp";
    $truyVan_LayLoaiSP = mysqli_query($conn, $layLoaiSP);

?>

<main>
    <div class="container-fluid px-4">
        <h1 class="mt-4">Danh mục</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="./TrangChuAdmin.php">Trang chủ</a></li>
            <li class="breadcrumb-item active">Quản lý danh mục</li>
        </ol>

        <div class="card-body mb-2">
            <a href="./ThemDanhMuc.php" class="btn btn-success">
                <i class="fas fa-plus me-2"></i>Thêm danh mục</a>
        </div>
                            
        <div class="card mb-4">
            <div class="card-header">
                <b><i class="fas fa-tags me-2"></i>Các thể loại truyện tranh</b>
            </div>
            <div class="card-body">
                <table id="duLieuBang">
                    <thead>
                        <tr>
                            <th>Mã loại</th>
                            <th>Tên loại</th>
                            <th>Mô tả</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($loaiSanPham = mysqli_fetch_array($truyVan_LayLoaiSP)){ ?>
                            <tr>
                                <td class="duLieuSo"><?php echo $loaiSanPham["MaLoaiSP"]; ?></td>
                                <td><?php echo $loaiSanPham["TenLoai"]; ?></td>
                                <td><?php echo $loaiSanPham["MoTa"]; ?></td>
                                <td class="duLieuSo">
                                    <a href="./SuaDanhMuc.php?maLoaiSP=<?php echo $loaiSanPham["MaLoaiSP"]; ?>" class="btn btn-warning">
                                        <i class="fas fa-edit me-2"></i>Sửa</a>
                                    <a href="#" class="btn btn-danger btn-xoa-danh-muc" data-id="<?php echo $loaiSanPham["MaLoaiSP"]; ?>">
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
                { "width": "25%" }, 
                { "width": "40%" }, 
                { "width": "25%" }  
            ],
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

        // Sử dụng event delegation để gán sự kiện click vào nút "Xóa"
        $(document).on('click', '.btn-xoa-danh-muc', function(e) {
            e.preventDefault(); // Ngăn chuyển hướng mặc định
            var maLoaiSP = $(this).data('id');
            Swal.fire({
                title: 'Xác nhận xóa',
                text: 'Bạn có chắc chắn muốn xóa danh mục này?',
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
                    window.location.href = './XoaDanhMuc.php?maLoaiSP=' + maLoaiSP;
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
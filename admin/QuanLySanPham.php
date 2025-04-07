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
    
    $laySanPham =  "SELECT * 
                    FROM sanpham
                    INNER JOIN loaisp
                    ON sanpham.MaLoaiSP = loaisp.MaLoaiSP";
    $truyVan_LaySanPham = mysqli_query($conn, $laySanPham);
?>

<main>
    <div class="container-fluid px-4">
        <h1 class="mt-4">Sản phẩm</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="./TrangChuAdmin.php">Trang chủ</a></li>
            <li class="breadcrumb-item active">Sản phẩm</li>
        </ol>
        <div class="card-body mb-2">
            <a href="./ThemSanPham.php" class="btn btn-success">
                <i class="fas fa-plus me-2"></i>Thêm sản phẩm
            </a>
        </div>
        <div class="card mb-4">
            <div class="card-header">
            <i class="fas fa-scroll me-2"></i>Các thông tin chi tiết truyện tranh</b>
        </div>
            <div class="card-body">
                <table id="duLieuBang">
                    <thead>
                        <tr>
                            <th>Mã</th>
                            <th>Ảnh</th>
                            <th>Tên</th>
                            <th>Thể loại</th>
                            <th>Số lượng</th>
                            <th>Đơn giá</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($cot = mysqli_fetch_array($truyVan_LaySanPham)){ ?>
                            <tr>
                                <td class="duLieuSo"><?php echo $cot["MaSanPham"]; ?></td>
                                <td class="duLieuSo"><img height="100px" src="<?php echo $cot["Anh"]; ?>" alt="Ảnh sản phẩm" /></td>
                                <td><?php echo $cot["TenSanPham"]; ?></td>
                                <td><?php echo $cot["TenLoai"]; ?></td>
                                <td class="duLieuSo"><?php echo $cot["SoLuong"]; ?></td>
                                <td class="duLieuSo"><?php echo number_format($cot["DonGia"], 0, ',', '.'); ?></td>
                                <td class="duLieuSo">
                                    <a href="./SuaSanPham.php?maSanPham=<?php echo $cot["MaSanPham"]; ?>" class="btn btn-warning">
                                        <i class="fas fa-edit me-2"></i>Sửa</a>
                                    <a href="#" class="btn btn-danger btn-xoa-san-pham" data-id="<?php echo $cot["MaSanPham"]; ?>">
                                        <i class="fas fa-trash me-2"></i>Xóa
                                    </a>
                                    <a href="./ChiTietSanPhamAdmin.php?maSanPham=<?php echo $cot["MaSanPham"]; ?>"
                                        class="btn btn-info"><i class="fas fa-angle-double-down me-2"></i>Xem chi tiết</a>
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
            "pageLength": 10,
            "paging": true,
            "autoWidth": false,
            "columns": [
                { "width": "8%" }, 
                { "width": "10%" },
                { "width": "17%" }, 
                { "width": "17%" },  
                { "width": "8%" }, 
                { "width": "10%" },
                { "width": "30%" }  
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
        $(document).on('click', '.btn-xoa-san-pham', function(e) {
            e.preventDefault(); // Ngăn chuyển hướng mặc định
            var maSanPham = $(this).data('id');
            Swal.fire({
                title: 'Xác nhận xóa',
                text: 'Bạn có chắc chắn muốn xóa sản phẩm này?',
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
                    window.location.href = './XoaSanPham.php?maSanPham=' + maSanPham;
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
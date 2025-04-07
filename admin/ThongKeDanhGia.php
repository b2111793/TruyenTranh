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

    // Thống kê sản phẩm được đánh giá nhiều nhất
    $sanPhamDanhGia = "SELECT sp.MaSanPham, sp.TenSanPham, COUNT(*) AS TongDanhGia 
                    FROM danhgia dg 
                    JOIN sanpham sp ON dg.MaSanPham = sp.MaSanPham 
                    GROUP BY sp.MaSanPham, sp.TenSanPham 
                    ORDER BY TongDanhGia DESC";
    $truyVan_SanPhamDanhGia = mysqli_query($conn, $sanPhamDanhGia);

?>

<main>
    <div class="container-fluid px-4">
        <h1 class="mt-4">Thống kê</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="./TrangChuAdmin.php">Trang chủ</a></li>
            <li class="breadcrumb-item">Thống kê</li>
            <li class="breadcrumb-item active">Sản phẩm được đánh giá nhiều nhất</li>
        </ol>
    
        <div class="card mb-4">
            <div class="card-header">
                <b><i class="fas fa-star me-2"></i>Sản phẩm được đánh giá nhiều nhất</b>
            </div>
            <div class="card-body">
                <table id="duLieuBang">
                    <thead>
                        <tr>
                            <th>Mã sản phẩm</th>
                            <th>Tên sản phẩm</th>
                            <th>Tổng số đánh giá</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($truyVan_SanPhamDanhGia)) { ?>
                            <tr>
                                <td class="duLieuSo"><?php echo $row['MaSanPham']; ?></td>
                                <td><?php echo htmlspecialchars($row['TenSanPham']); ?></td>
                                <td class="duLieuSo"><?php echo $row['TongDanhGia']; ?></td>
                                <td class="duLieuSo">
                                    <a href="./ChiTietDanhGia.php?maSanPham=<?php echo $row['MaSanPham']; ?>" class="btn btn-info btn-sm">
                                    <i class="fas fa-angle-double-down me-2"></i>Xem chi tiết
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
                { "width": "20%" }, 
                { "width": "60%" }, 
                { "width": "20%" }, 
                { "width": "20%" }  
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
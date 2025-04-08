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

    // Thống kê sản phẩm bán chạy
    $sanPhamBanChay = "SELECT sp.MaSanPham, sp.TenSanPham, sp.Anh, SUM(ct.SoLuong) AS TongSoLuong 
                    FROM ct_dondat ct 
                    JOIN sanpham sp ON ct.MaSanPham = sp.MaSanPham 
                    GROUP BY sp.MaSanPham, sp.TenSanPham 
                    ORDER BY TongSoLuong DESC ";
                    // LIMIT 5";
    $truyVan_SanPhamBanChay = mysqli_query($conn, $sanPhamBanChay);
?>

<main>
    <div class="container-fluid px-4">
        <h1 class="mt-4">Thống kê</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="./TrangChuAdmin.php">Trang chủ</a></li>
            <li class="breadcrumb-item">Thống kê</li>
            <li class="breadcrumb-item active">Sản phẩm bán chạy</li>
        </ol>
    
        <div class="card mb-4">
            <div class="card-header">
                <b><i class="fas fa-fire me-2"></i>Sản phẩm bán chạy</b>
        </div>
        <div class="card-body">
            <table id="duLieuBang">
                <thead>
                    <tr>
                        <th>Mã sản phẩm</th>
                        <th>Tên sản phẩm</th>
                        <th>Ảnh</th>
                        <th>Tổng số lượng bán</th>
                    </tr>
                </thead>
                <tbody>
                <?php while ($row = mysqli_fetch_assoc($truyVan_SanPhamBanChay)) { ?>
                        <tr>
                            <td class="duLieuSo"><?php echo $row['MaSanPham']; ?></td>
                            <td><?php echo $row['TenSanPham']; ?></td>
                            <td class="duLieuSo"><img height="100px" src="<?php echo $row['Anh']; ?>" alt="Ảnh sản phẩm"></td>
                            <td class="duLieuSo"><?php echo $row['TongSoLuong']; ?></td>
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
            // "columns": [
            //     { "width": "8%" }, 
            //     { "width": "8%" },
            //     { "width": "17%" }  
            // ],
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
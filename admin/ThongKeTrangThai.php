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

    // Thống kê số lượng đơn đặt theo trạng thái
    $donDat = "SELECT TrangThai, COUNT(*) AS SoLuong 
            FROM dondat 
            GROUP BY TrangThai";
    $truyVan_DonDat = mysqli_query($conn, $donDat);
?>

<main>
    <div class="container-fluid px-4">
        <h1 class="mt-4">Đơn hàng</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="./TrangChuAdmin.php">Trang chủ</a></li>
            <li class="breadcrumb-item">Đơn hàng</li>
            <li class="breadcrumb-item active">Trạng thái đơn hàng</li>
        </ol>
    
        <div class="card mb-4">
            <div class="card-header">
                <b><i class="fas fa-list me-2"></i>Trạng thái các đơn đặt hàng</b>
        </div>
        <div class="card-body">
            <table id="duLieuBang">
                <thead>
                    <tr>
                        <th>Trạng thái</th>
                        <th>Số lượng</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($truyVan_DonDat)) { ?>
                        <tr>
                            <td><?php echo $row['TrangThai']; ?></td>
                            <td class="duLieuSo"><?php echo $row['SoLuong']; ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            <a href="./QuanLyDonHang.php" class="btn btn-secondary">Quay lại</a>
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
            //     { "width": "50%" },
            //     { "width": "50%" }  
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
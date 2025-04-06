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

    $tenDangNhap = mysqli_real_escape_string($conn, $_SESSION["tenDangNhap"]);
    // Lấy thông tin Admin
    $layAdmin = "SELECT * 
                FROM nguoidung
                WHERE TenDangNhap = '$tenDangNhap'";
    $truyVan_LayAdmin = mysqli_query($conn, $layAdmin);
    $admin = mysqli_fetch_assoc($truyVan_LayAdmin);
?>

<main>
    <div class="container-fluid px-4">
        <h1 class="mt-4">Thông tin Admin</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="./TrangChuAdmin.php">Trang chủ</a></li>
            <li class="breadcrumb-item active">Thông tin Admin</li>
        </ol>
        <div class="card mb-4">
            <div class="card-body">
                <p><strong>Tên đăng nhập:</strong> <?php echo $admin["TenDangNhap"]; ?></p>
                <p><strong>Họ tên:</strong> <?php echo $admin["HoTen"]; ?></p>
                <p><strong>Ngày sinh:</strong> <?php echo date("d/m/Y", strtotime($admin["NgaySinh"])); ?></p>
                <p><strong>Giới tính:</strong> <?php echo $admin["GioiTinh"]; ?></p>
                <p><strong>Địa chỉ:</strong> <?php echo $admin["DiaChi"]; ?></p>
                <p><strong>Số điện thoại:</strong> <?php echo $admin["DienThoai"];?></p>
                <p><strong>Email:</strong> <?php echo $admin["Email"]; ?></p>

                <a href="./TrangChuAdmin.php" class="btn btn-secondary">Quay lại</a>
            </div>
        </div>
    </div>
</main>

<?php include('../shared/footerAdmin.php'); ?>
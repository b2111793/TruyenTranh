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

    if (!isset($_GET["maSanPham"])) {
        $_SESSION['toastr'] = ['type' => 'warning', 'message' => 'Không tìm thấy mã sản phẩm!'];
        echo "<script> window.location.href = './QuanLySanPham.php';</script>";
        exit;
    }

    $maSanPham = mysqli_real_escape_string($conn, $_GET["maSanPham"]);

    // Lấy thông tin sản phẩm
    $laySanPham =  "SELECT *
                    FROM sanpham sp
                    JOIN loaisp lsp 
                    ON sp.MaLoaiSP = lsp.MaLoaiSP
                    WHERE sp.MaSanPham = '$maSanPham'";
    $truyVan_LaySanPham = mysqli_query($conn, $laySanPham);
    if (mysqli_num_rows($truyVan_LaySanPham) == 0) {
        $_SESSION['toastr'] = ['type' => 'warning', 'message' => 'Sản phẩm không tồn tại!'];
        echo "<script> window.location.href = './QuanLySanPham.php'; </script>";
        exit;
    }
    $sanPham = mysqli_fetch_assoc($truyVan_LaySanPham);
?>

<main>
    <div class="container-fluid px-4">
        <h1 class="mt-4">Chi tiết sản phẩm</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="./TrangChuAdmin.php">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="./QuanLySanPham.php">Quản lý sản phẩm</a></li>
            <li class="breadcrumb-item active">Chi tiết sản phẩm</li>
        </ol>
        <div class="card mb-4">
            <div class="card-body">
                <h5>Thông tin sản phẩm</h5>
                <p><strong>Mã:</strong> <?php echo $sanPham["MaSanPham"]; ?></p>
                <p><strong>Tên:</strong> <?php echo $sanPham["TenSanPham"]; ?></p>
                <p><strong>Thể loại:</strong> <?php echo $sanPham["TenLoai"]; ?></p>
                <p><strong>Tác giả:</strong> <?php echo $sanPham["TacGia"]; ?></p>
                <p><strong>Xuất xứ:</strong> <?php echo $sanPham["XuatXu"]; ?></p>
                <p><strong>Giới thiệu:</strong> <?php echo $sanPham["GioiThieu"]; ?></p>
                <p><strong>Ảnh mô tả:</strong></p>
                <div>
                    <img height="400px" src="<?php echo $sanPham["Anh"]; ?>" alt="Ảnh sản phẩm">
                    <img height="400px" src="<?php echo $sanPham["AnhMoTa1"]; ?>" alt="Ảnh sản phẩm">
                    <img height="400px" src="<?php echo $sanPham["AnhMoTa2"]; ?>" alt="Ảnh sản phẩm">
                    <img height="400px" src="<?php echo $sanPham["AnhMoTa3"]; ?>" alt="Ảnh sản phẩm">
                </div>
                <br>
                <a href="./QuanLySanPham.php" class="btn btn-secondary">Quay lại</a>
            </div>
        </div>
    </div>
</main>

<?php include('../shared/footerAdmin.php'); ?>
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

    if (!isset($_GET["maLoaiSP"])){
        echo "<script>window.location.href = './QuanLyDanhMuc.php'; </script>";
    }
        
    $maLoaiSP = intval($_GET["maLoaiSP"]);

    // Lấy thông tin danh mục hiện tại
    $layDanhMuc = "SELECT * FROM loaisp WHERE MaLoaiSP = $maLoaiSP";
    $truyVan_LayDanhMuc = mysqli_query($conn, $layDanhMuc);
    if(mysqli_num_rows($truyVan_LayDanhMuc) == 0){
        $_SESSION['toastr'] = ['type' => 'warning', 'message' => 'Danh mục không tồn tại!'];
        echo "<script> window.location.href = './QuanLyDanhMuc.php'; </script>";
        exit;
    }
    $danhMuc = mysqli_fetch_assoc($truyVan_LayDanhMuc);

    // Xử lý khi form được gửi
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $tenLoai = mysqli_real_escape_string($conn, $_POST["tenLoai"]);
        $moTa = mysqli_real_escape_string($conn, $_POST["moTa"]);

        // Cập nhật danh mục
        $truyVan_LayDanhMuc = "UPDATE loaisp SET TenLoai = '$tenLoai', MoTa = '$moTa' WHERE MaLoaiSP = $maLoaiSP";
        if(mysqli_query($conn, $truyVan_LayDanhMuc)){
            $_SESSION['toastr'] = ['type' => 'success', 'message' => 'Cập nhật danh mục thành công!'];
            echo "<script> window.location.href = './QuanLyDanhMuc.php'; </script>";
            exit;
        } 
        else{
            $_SESSION['toastr'] = ['type' => 'error', 'message' => 'Đã xảy ra lỗi khi cập nhật danh mục!: '. mysqli_error($conn)];
            echo "<script> window.location.href = './QuanLyDanhMuc.php'; </script>";
            exit;
        }
    }
?>

<main>
    <div class="container-fluid px-4">
        <h1 class="mt-4">Sửa danh mục</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="./TrangChuAdmin.php">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="./QuanLyDanhMuc.php">Danh mục sản phẩm</a></li>
            <li class="breadcrumb-item active">Sửa danh mục</li>
        </ol>
        <div class="card mb-4">
            <div class="card-body">
                <form method="POST">
                    <div class="mb-3">
                        <label for="MaLoaiSP" class="form-label">Mã thể loại</label>
                        <input type="text" class="form-control" id="maLoaiSP" value="<?php echo $danhMuc['MaLoaiSP']; ?>" disabled>
                    </div>
                    <div class="mb-3">
                        <label for="TenLoai" class="form-label">Tên thể loại</label>
                        <input type="text" class="form-control" id="tenLoai" name="tenLoai" value="<?php echo $danhMuc['TenLoai']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="MoTa" class="form-label">Mô tả</label>
                        <textarea class="form-control" id="moTa" name="moTa" rows="3"><?php echo $danhMuc['MoTa']; ?></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Cập nhật</button>
                    <a href="./QuanLyDanhMuc.php" class="btn btn-secondary">Hủy</a>
                </form>
            </div>
        </div>
    </div>
</main>

<?php include('../shared/footerAdmin.php'); ?>
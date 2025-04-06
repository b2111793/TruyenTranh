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

    // Xử lý khi form được gửi
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $tenLoai = mysqli_real_escape_string($conn, $_POST["tenLoai"]);
        $moTa = mysqli_real_escape_string($conn, $_POST["moTa"]);

        // Thêm danh mục mới vào bảng loaisp
        $themDanhMuc = "INSERT INTO loaisp(TenLoai, MoTa) VALUES('$tenLoai', '$moTa')";
        if (mysqli_query($conn, $themDanhMuc)) {
            $_SESSION['toastr'] = ['type' => 'success', 'message' => 'Thêm danh mục thành công!'];
            echo "<script> window.location.href = './QuanLyDanhMuc.php'; </script>";
            exit;
        } else {
            $_SESSION['toastr'] = ['type' => 'error', 'message' => 'Đã xảy ra lỗi khi thêm danh mục!'];
            echo "<script> window.location.href = './QuanLyDanhMuc.php'; </script>";
            exit;
        }
    }
?>

<main>
    <div class="container-fluid px-4">
        <h1 class="mt-4">Thêm danh mục</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="./TrangChuAdmin.php">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="./QuanLyDanhMuc.php">Danh mục sản phẩm</a></li>
            <li class="breadcrumb-item active">Thêm danh mục</li>
        </ol>
        <div class="card mb-4">
            <div class="card-body">
                <form method="POST">
                    <div class="mb-3">
                        <label for="tenLoai" class="form-label">Tên thể loại <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="tenLoai" name="tenLoai" required>
                    </div>
                    <div class="mb-3">
                        <label for="moTa" class="form-label">Mô tả</label>
                        <textarea class="form-control" id="moTa" name="moTa" rows="3"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Thêm danh mục</button>
                    <a href="./QuanLyDanhMuc.php" class="btn btn-secondary">Hủy</a>
                </form>
            </div>
        </div>
    </div>
</main>

<?php include('../shared/footerAdmin.php'); ?>
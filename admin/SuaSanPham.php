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
    echo "<script>window.location.href = './QuanLySanPham.php';</script>";
}

$maSanPham = mysqli_real_escape_string($conn, $_GET["maSanPham"]);

// Lấy thông tin sản phẩm hiện tại
$laySanPham = "SELECT * FROM sanpham WHERE MaSanPham = '$maSanPham'";
$truyVan_LaySanPham = mysqli_query($conn, $laySanPham);
if (mysqli_num_rows($truyVan_LaySanPham) == 0) {
    $_SESSION['toastr'] = ['type' => 'warning', 'message' => 'Sản phẩm không tồn tại!'];
    echo "<script> window.location.href = './QuanLySanPham.php'; </script>";
    exit;
}
$sanPham = mysqli_fetch_assoc($truyVan_LaySanPham);

// Lấy danh sách thể loại để hiển thị trong dropdown
$layLoaiSP = "SELECT * FROM loaisp";
$truyVan_LayLoaiSP = mysqli_query($conn, $layLoaiSP);

// Hàm xử lý upload ảnh
function uploadImage($file, $targetDir, $conn, $fieldName) {
    $result = ["success" => false, "path" => "", "error" => ""];

    if (!empty($file["name"])) {
        // Kiểm tra lỗi upload từ phía client
        if ($file["error"] !== UPLOAD_ERR_OK) {
            switch ($file["error"]) {
                case UPLOAD_ERR_INI_SIZE:
                case UPLOAD_ERR_FORM_SIZE:
                    $result["error"] = "$fieldName quá lớn! Kích thước tối đa cho phép là " . ini_get('upload_max_filesize') . ".";
                    break;
                case UPLOAD_ERR_PARTIAL:
                    $result["error"] = "$fieldName chỉ được tải lên một phần! Vui lòng thử lại.";
                    break;
                case UPLOAD_ERR_NO_TMP_DIR:
                    $result["error"] = "Không tìm thấy thư mục tạm để lưu ảnh! Vui lòng liên hệ quản trị viên.";
                    break;
                case UPLOAD_ERR_CANT_WRITE:
                    $result["error"] = "Không thể ghi file ảnh vào đĩa! Vui lòng liên hệ quản trị viên.";
                    break;
                case UPLOAD_ERR_EXTENSION:
                    $result["error"] = "Tải ảnh thất bại do lỗi extension! Vui lòng liên hệ quản trị viên.";
                    break;
                default:
                    $result["error"] = "Đã xảy ra lỗi không xác định khi tải $fieldName lên! Mã lỗi: " . $file["error"];
                    break;
            }
            return $result;
        }

        // Kiểm tra loại file
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($file["type"], $allowedTypes)) {
            $result["error"] = "Loại file của $fieldName không hợp lệ! Chỉ chấp nhận JPG, PNG, GIF.";
            return $result;
        }

        // Kiểm tra kích thước file (giới hạn 5MB)
        $maxSize = 5 * 1024 * 1024; // 5MB
        if ($file["size"] > $maxSize) {
            $result["error"] = "$fieldName quá lớn! Kích thước tối đa là 5MB.";
            return $result;
        }

        // Xử lý tên file để tránh trùng lặp
        $fileName = pathinfo($file["name"], PATHINFO_FILENAME);
        $extension = pathinfo($file["name"], PATHINFO_EXTENSION);
        $newFileName = $fileName . '_' . time() . '.' . $extension;
        $targetFile = $targetDir . $newFileName;

        // Kiểm tra quyền ghi của thư mục
        if (!is_writable($targetDir)) {
            $result["error"] = "Thư mục lưu ảnh không có quyền ghi! Vui lòng liên hệ quản trị viên.";
            return $result;
        }

        // Di chuyển file từ thư mục tạm sang thư mục đích
        if (move_uploaded_file($file["tmp_name"], $targetFile)) {
            $result["success"] = true;
            $result["path"] = mysqli_real_escape_string($conn, $targetFile);
        } else {
            $result["error"] = "Lỗi khi di chuyển $fieldName vào thư mục đích! Vui lòng thử lại.";
        }
    }

    return $result;
}

// Xử lý khi form được gửi
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tenSanPham = mysqli_real_escape_string($conn, $_POST["tenSanPham"]);
    $soLuong = mysqli_real_escape_string($conn, $_POST["soLuong"]);
    $donGia = mysqli_real_escape_string($conn, $_POST["donGia"]);
    $gioiThieu = mysqli_real_escape_string($conn, $_POST["gioiThieu"]);
    $maLoaiSP = mysqli_real_escape_string($conn, $_POST["maLoaiSP"]);
    $tacGia = mysqli_real_escape_string($conn, $_POST["tacGia"]);
    $xuatXu = mysqli_real_escape_string($conn, $_POST["xuatXu"]);

    // Kiểm tra dữ liệu đầu vào
    $errors = [];
    if (empty($tenSanPham)) {
        $errors[] = "Tên sản phẩm không được để trống!";
    }
    if (empty($maLoaiSP)) {
        $errors[] = "Vui lòng chọn thể loại!";
    }
    if (empty($tacGia)) {
        $errors[] = "Tác giả không được để trống!";
    }
    if (empty($xuatXu)) {
        $errors[] = "Xuất xứ không được để trống!";
    }
    if (!is_numeric($soLuong) || $soLuong < 0) {
        $errors[] = "Số lượng phải là số không âm!";
    }
    if (!is_numeric($donGia) || $donGia < 0) {
        $errors[] = "Đơn giá phải là số không âm!";
    }

    // Xử lý upload ảnh
    $targetDir = "../images/Anh_SanPham/"; // Thư mục lưu ảnh
    $anh = $sanPham['Anh']; // Giữ ảnh cũ nếu không upload ảnh mới
    $anhMoTa1 = $sanPham['AnhMoTa1'];
    $anhMoTa2 = $sanPham['AnhMoTa2'];
    $anhMoTa3 = $sanPham['AnhMoTa3'];

    // Upload ảnh chính
    $uploadAnh = uploadImage($_FILES["anh"], $targetDir, $conn, "Ảnh chính");
    if (!$uploadAnh["success"] && !empty($uploadAnh["error"])) {
        $errors[] = $uploadAnh["error"];
    } elseif ($uploadAnh["success"]) {
        $anh = $uploadAnh["path"];
    }

    // Upload ảnh mô tả 1
    $uploadAnhMoTa1 = uploadImage($_FILES["anhMoTa1"], $targetDir, $conn, "Ảnh mô tả 1");
    if (!$uploadAnhMoTa1["success"] && !empty($uploadAnhMoTa1["error"])) {
        $errors[] = $uploadAnhMoTa1["error"];
    } elseif ($uploadAnhMoTa1["success"]) {
        $anhMoTa1 = $uploadAnhMoTa1["path"];
    }

    // Upload ảnh mô tả 2
    $uploadAnhMoTa2 = uploadImage($_FILES["anhMoTa2"], $targetDir, $conn, "Ảnh mô tả 2");
    if (!$uploadAnhMoTa2["success"] && !empty($uploadAnhMoTa2["error"])) {
        $errors[] = $uploadAnhMoTa2["error"];
    } elseif ($uploadAnhMoTa2["success"]) {
        $anhMoTa2 = $uploadAnhMoTa2["path"];
    }

    // Upload ảnh mô tả 3
    $uploadAnhMoTa3 = uploadImage($_FILES["anhMoTa3"], $targetDir, $conn, "Ảnh mô tả 3");
    if (!$uploadAnhMoTa3["success"] && !empty($uploadAnhMoTa3["error"])) {
        $errors[] = $uploadAnhMoTa3["error"];
    } elseif ($uploadAnhMoTa3["success"]) {
        $anhMoTa3 = $uploadAnhMoTa3["path"];
    }

    // Kiểm tra ảnh chính có tồn tại không
    if (empty($anh)) {
        $errors[] = "Ảnh chính là bắt buộc!";
    }

    // Nếu không có lỗi, thực hiện cập nhật sản phẩm
    if (empty($errors)) {
        $truyVan_LaySanPham = "UPDATE sanpham 
                SET TenSanPham = '$tenSanPham', SoLuong = '$soLuong', Anh = '$anh', AnhMoTa1 = '$anhMoTa1', 
                    AnhMoTa2 = '$anhMoTa2', AnhMoTa3 = '$anhMoTa3', DonGia = '$donGia', GioiThieu = '$gioiThieu', 
                    MaLoaiSP = '$maLoaiSP', TacGia = '$tacGia', XuatXu = '$xuatXu' 
                WHERE MaSanPham = '$maSanPham'";
        if (mysqli_query($conn, $truyVan_LaySanPham)) {
            $_SESSION['toastr'] = ['type' => 'success', 'message' => 'Cập nhật sản phẩm thành công!'];
            echo "<script> window.location.href = './QuanLySanPham.php'; </script>";
            exit;
        } else {
            $_SESSION['toastr'] = ['type' => 'error', 'message' => 'Đã xảy ra lỗi khi cập nhật sản phẩm: ' . mysqli_error($conn)];
            echo "<script> window.location.href = './QuanLySanPham.php'; </script>";
            exit;
        }
    } else {
        // Hiển thị tất cả lỗi
        $errorMessage = implode("\\n", $errors);
        $_SESSION['toastr'] = ['type' => 'error', 'message' => "$errorMessage"];
        echo "<script> window.location.href = './QuanLySanPham.php'; </script>";
        exit;
    }
}
?>

<main>
    <div class="container-fluid px-4">
        <h1 class="mt-4">Sửa sản phẩm</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="./TrangChuAdmin.php">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="./QuanLySanPham.php">Sản phẩm</a></li>
            <li class="breadcrumb-item active">Sửa sản phẩm</li>
        </ol>
        <div class="card mb-4">
            <div class="card-body">
                <form method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="maSanPham" class="form-label">Mã sản phẩm</label>
                        <input type="text" class="form-control" id="maSanPham" value="<?php echo $sanPham['MaSanPham']; ?>" disabled>
                    </div>
                    <div class="mb-3">
                        <label for="tenSanPham" class="form-label">Tên sản phẩm</label>
                        <input type="text" class="form-control" id="tenSanPham" name="tenSanPham" value="<?php echo $sanPham['TenSanPham']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="maLoaiSP" class="form-label">Thể loại</label>
                        <select class="form-control" id="maLoaiSP" name="maLoaiSP" required>
                            <option value="">Chọn thể loại</option>
                            <?php while ($loai = mysqli_fetch_array($truyVan_LayLoaiSP)) { ?>
                                <option value="<?php echo $loai['MaLoaiSP']; ?>" 
                                    <?php if ($loai['MaLoaiSP'] == $sanPham['MaLoaiSP']) echo 'selected'; ?>>
                                    <?php echo $loai['TenLoai']; ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="tacGia" class="form-label">Tác giả</label>
                        <input type="text" class="form-control" id="tacGia" name="tacGia" value="<?php echo $sanPham['TacGia']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="xuatXu" class="form-label">Xuất xứ</label>
                        <input type="text" class="form-control" id="xuatXu" name="xuatXu" value="<?php echo $sanPham['XuatXu']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="soLuong" class="form-label">Số lượng</label>
                        <input type="number" class="form-control" id="soLuong" name="soLuong" value="<?php echo $sanPham['SoLuong']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="donGia" class="form-label">Đơn giá</label>
                        <input type="number" class="form-control" id="donGia" name="donGia" step="1" min="0" value="<?php echo $sanPham['DonGia']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="anh" class="form-label">Ảnh chính <span class="text-danger">*</span></label>
                        <input type="file" class="form-control" id="anh" name="anh">
                        <?php if (!empty($sanPham['Anh'])) { ?>
                            <img src="<?php echo $sanPham['Anh']; ?>" alt="Ảnh chính" style="max-width: 200px; margin-top: 10px;">
                        <?php } ?>
                    </div>
                    <div class="mb-3">
                        <label for="anhMoTa1" class="form-label">Ảnh mô tả 1</label>
                        <input type="file" class="form-control" id="anhMoTa1" name="anhMoTa1">
                        <?php if (!empty($sanPham['AnhMoTa1'])) { ?>
                            <img src="<?php echo $sanPham['AnhMoTa1']; ?>" alt="Ảnh mô tả 1" style="max-width: 200px; margin-top: 10px;">
                        <?php } ?>
                    </div>
                    <div class="mb-3">
                        <label for="anhMoTa2" class="form-label">Ảnh mô tả 2</label>
                        <input type="file" class="form-control" id="anhMoTa2" name="anhMoTa2">
                        <?php if (!empty($sanPham['AnhMoTa2'])) { ?>
                            <img src="<?php echo $sanPham['AnhMoTa2']; ?>" alt="Ảnh mô tả 2" style="max-width: 200px; margin-top: 10px;">
                        <?php } ?>
                    </div>
                    <div class="mb-3">
                        <label for="anhMoTa3" class="form-label">Ảnh mô tả 3</label>
                        <input type="file" class="form-control" id="anhMoTa3" name="anhMoTa3">
                        <?php if (!empty($sanPham['AnhMoTa3'])) { ?>
                            <img src="<?php echo $sanPham['AnhMoTa3']; ?>" alt="Ảnh mô tả 3" style="max-width: 200px; margin-top: 10px;">
                        <?php } ?>
                    </div>
                    <div class="mb-3">
                        <label for="gioiThieu" class="form-label">Giới thiệu</label>
                        <textarea class="form-control" id="gioiThieu" name="gioiThieu" rows="3"><?php echo $sanPham['GioiThieu']; ?></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Cập nhật sản phẩm</button>
                    <a href="./QuanLySanPham.php" class="btn btn-secondary">Hủy</a>
                </form>
            </div>
        </div>
    </div>
</main>

<?php
include('../shared/footerAdmin.php');
?>
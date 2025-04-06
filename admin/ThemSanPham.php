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
        $anh = $anhMoTa1 = $anhMoTa2 = $anhMoTa3 = "";

        // Upload ảnh chính (bắt buộc)
        $uploadAnh = uploadImage($_FILES["anh"], $targetDir, $conn, "Ảnh chính");
        if (!$uploadAnh["success"]) {
            if (empty($uploadAnh["error"])) {
                $errors[] = "Ảnh chính là bắt buộc!";
            } else {
                $errors[] = $uploadAnh["error"];
            }
        } else {
            $anh = $uploadAnh["path"];
        }

        // Upload ảnh mô tả 1
        $uploadAnhMoTa1 = uploadImage($_FILES["anhMoTa1"], $targetDir, $conn, "Ảnh mô tả 1");
        if (!$uploadAnhMoTa1["success"] && !empty($uploadAnhMoTa1["error"])) {
            $errors[] = $uploadAnhMoTa1["error"];
        } else {
            $anhMoTa1 = $uploadAnhMoTa1["path"];
        }

        // Upload ảnh mô tả 2
        $uploadAnhMoTa2 = uploadImage($_FILES["anhMoTa2"], $targetDir, $conn, "Ảnh mô tả 2");
        if (!$uploadAnhMoTa2["success"] && !empty($uploadAnhMoTa2["error"])) {
            $errors[] = $uploadAnhMoTa2["error"];
        } else {
            $anhMoTa2 = $uploadAnhMoTa2["path"];
        }

        // Upload ảnh mô tả 3
        $uploadAnhMoTa3 = uploadImage($_FILES["anhMoTa3"], $targetDir, $conn, "Ảnh mô tả 3");
        if (!$uploadAnhMoTa3["success"] && !empty($uploadAnhMoTa3["error"])) {
            $errors[] = $uploadAnhMoTa3["error"];
        } else {
            $anhMoTa3 = $uploadAnhMoTa3["path"];
        }

        // Nếu không có lỗi, thực hiện thêm sản phẩm
        if (empty($errors)) {
            $themSanPham = "INSERT INTO sanpham(TenSanPham, SoLuong, Anh, AnhMoTa1, AnhMoTa2, AnhMoTa3, DonGia, GioiThieu, MaLoaiSP, TacGia, XuatXu) 
                            VALUES ('$tenSanPham', '$soLuong', '$anh', '$anhMoTa1', '$anhMoTa2', '$anhMoTa3', '$donGia', '$gioiThieu', '$maLoaiSP', '$tacGia', '$xuatXu')";
            if (mysqli_query($conn, $themSanPham)) {
                $_SESSION['toastr'] = ['type' => 'success', 'message' => 'Thêm sản phẩm thành công!'];
                echo "<script> window.location.href = './QuanLySanPham.php'; </script>";
                exit;
            } else {
                $_SESSION['toastr'] = ['type' => 'error', 'message' => 'Đã xảy ra lỗi khi thêm sản phẩm: ' . mysqli_error($conn)];
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
        <h1 class="mt-4">Thêm sản phẩm</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="./TrangChuAdmin.php">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="./QuanLySanPham.php">Sản phẩm</a></li>
            <li class="breadcrumb-item active">Thêm sản phẩm</li>
        </ol>
        <div class="card mb-4">
            <div class="card-body">
                <form method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="tenSanPham" class="form-label">Tên sản phẩm <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="tenSanPham" name="tenSanPham" required>
                    </div>
                    <div class="mb-3">
                        <label for="maLoaiSP" class="form-label">Thể loại <span class="text-danger">*</span></label>
                        <select class="form-control" id="maLoaiSP" name="maLoaiSP" required>
                            <option value="">Chọn thể loại</option>
                            <?php while ($loai = mysqli_fetch_array($truyVan_LayLoaiSP)) { ?>
                                <option value="<?php echo $loai['MaLoaiSP']; ?>"><?php echo $loai['TenLoai']; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="tacGia" class="form-label">Tác giả <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="tacGia" name="tacGia" required>
                    </div>
                    <div class="mb-3">
                        <label for="xuatXu" class="form-label">Xuất xứ <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="xuatXu" name="xuatXu" required>
                    </div>
                    <div class="mb-3">
                        <label for="soLuong" class="form-label">Số lượng <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="soLuong" name="soLuong" required>
                    </div>
                    <div class="mb-3">
                        <label for="donGia" class="form-label">Đơn giá <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="donGia" name="donGia" step="1" required>
                    </div>
                    <div class="mb-3">
                        <label for="anh" class="form-label">Ảnh chính <span class="text-danger">*</span></label>
                        <input type="file" class="form-control" id="anh" name="anh" required>
                    </div>
                    <div class="mb-3">
                        <label for="anhMoTa1" class="form-label">Ảnh mô tả 1</label>
                        <input type="file" class="form-control" id="anhMoTa1" name="anhMoTa1">
                    </div>
                    <div class="mb-3">
                        <label for="anhMoTa2" class="form-label">Ảnh mô tả 2</label>
                        <input type="file" class="form-control" id="anhMoTa2" name="anhMoTa2">
                    </div>
                    <div class="mb-3">
                        <label for="anhMoTa3" class="form-label">Ảnh mô tả 3</label>
                        <input type="file" class="form-control" id="anhMoTa3" name="anhMoTa3">
                    </div>
                    <div class="mb-3">
                        <label for="gioiThieu" class="form-label">Giới thiệu</label>
                        <textarea class="form-control" id="gioiThieu" name="gioiThieu" rows="3"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Thêm sản phẩm</button>
                    <a href="./QuanLySanPham.php" class="btn btn-secondary">Hủy</a>
                </form>
            </div>
        </div>
    </div>
</main>

<?php
    include('../shared/footerAdmin.php');
?>
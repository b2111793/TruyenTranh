function timKiem_sanPham(tacGia, xuatXu, loaiSanPham) {
    $.ajax({
        url: "../ajax/TimKiemAjax.php",
        type: "POST",
        data: {
            tacGia: tacGia,
            xuatXu: xuatXu,
            loaiSanPham: loaiSanPham
        },
        success: function(giaTri) {
            // Chèn nội dung từ TimKiemAjax.php vào DOM
            $('#load_sanPham').html(giaTri);

            // Sau khi nội dung được chèn, gọi hàm kiểm tra trạng thái yêu thích
            kiemTra_trangThaiYeuThich();

            // Gọi hàm cập nhật số lượng yêu thích
            capNhatSoLuongYeuThich();

            // Xử lý logic "Xem tiếp"/"Ẩn bớt"
            var soSanPhamMoiLanHienThi = 6;
            var soSanPhamHienTai = 6;
            var tongSoSanPham = $('.product-one').length; // Tổng số sản phẩm
            var soSanPhamBanDau = 6;

            $("#xemTiepButton").click(function() {
                var soSanPhamTiepTheo = Math.min(soSanPhamHienTai + soSanPhamMoiLanHienThi, tongSoSanPham);
                for (var i = soSanPhamHienTai + 1; i <= soSanPhamTiepTheo; i++) {
                    $(".product-one[data-index='" + i + "']").removeClass("hidden");
                }
                soSanPhamHienTai = soSanPhamTiepTheo;
                if (soSanPhamHienTai > soSanPhamBanDau) {
                    $("#anBotButton").show();
                }
                if (soSanPhamHienTai >= tongSoSanPham) {
                    $("#xemTiepButton").hide();
                }
            });

            $("#anBotButton").click(function() {
                for (var i = soSanPhamBanDau + 1; i <= tongSoSanPham; i++) {
                    $(".product-one[data-index='" + i + "']").addClass("hidden");
                }
                soSanPhamHienTai = soSanPhamBanDau;
                $("#xemTiepButton").show();
                $("#anBotButton").hide();
            });
        },
        error: function(xhr, status, error) {
            console.log("Lỗi Ajax: " + error);
        }
    });
}

function doi_matKhau(tenDangNhap, matKhauCu, matKhauMoi) {
    $.ajax({
        url: "../ajax/DoiMatKhauAjax.php",
        type: "POST",
        data: {
            tenDangNhap: tenDangNhap,
            matKhauCu: matKhauCu,
            matKhauMoi: matKhauMoi
        },
        success: function (giaTri) {
            // Nếu đổi mật khẩu thành công
            if (giaTri === "Đổi mật khẩu thành công!") {
                Swal.fire({
                    title: 'Thông báo',
                    text: giaTri, // Thông báo thành công
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then(() => {
                    // Sau khi nhấn OK, có thể chuyển hướng trang (ví dụ: về trang đăng nhập)
                    window.location.href = "../shared/DangNhap.php"; // Chuyển hướng về trang đăng nhập
                });
            } else {
                // Nếu có lỗi, hiển thị thông báo lỗi
                Swal.fire({
                    title: 'Lỗi!',
                    text: giaTri, // Lỗi trả về từ server (ví dụ: "Mật khẩu cũ không đúng")
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        },
        error: function (xhr, status, error) {
            // Hiển thị thông báo lỗi khi có sự cố với AJAX
            Swal.fire({
                title: 'Lỗi!',
                text: 'Có lỗi xảy ra khi đổi mật khẩu. Vui lòng thử lại.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        }
    });
}


function them_gioHang(maSanPham, soLuong) {
    soLuong = Number(soLuong);
    if(!Number.isInteger(soLuong) || (soLuong <= 0)){
        Swal.fire({
            title: 'Cảnh báo',
            text: "Số lượng sản phẩm bạn nhập không hợp lệ!",
            icon: 'warning',
            confirmButtonText: 'OK'
        }).then(() => {
            window.location.href = "./ChiTietSanPham.php?MaSanPham=" + maSanPham;
        });
    }
    else{
        $.ajax({
            url: "../ajax/ThemGioHangAjax.php",
            type: "POST",
            data: {
                maSanPham: maSanPham,
                soLuong: soLuong
            },
            success: function(giaTri) {
                // Tách thông báo và HTML
                var parts = giaTri.split("|");
                var message = parts[0];
                var html = parts[1];

                if (message.includes("Thêm sản phẩm vào giỏ hàng thành công!")) {
                    // Cập nhật giao diện giỏ hàng
                    $('.divGioHang').html(html);

                    // Hiển thị thông báo thành công bằng Toastr
                    toastr.success(message, 'Thông báo');
                } else {
                    // Hiển thị thông báo lỗi bằng Toastr
                    toastr.error(message, 'Lỗi');
                }
            },
            error: function() {
                toastr.error('Đã xảy ra lỗi khi thêm vào giỏ hàng!', 'Lỗi');
            }
        });
    }
}

function kiemTra_trangThaiYeuThich() {
    $('.heart-icon').each(function() {
        let icon = $(this);
        let maSanPham = icon.data('product-id');
        
        $.ajax({
            url: "../ajax/KiemTraYeuThichAjax.php", 
            type: "POST",
            data: {
                maSanPham: maSanPham
            },
            success: function(giaTri) {
                if (giaTri === "Đã yêu thích") {
                    icon.removeClass('far').addClass('fas');
                    icon.addClass('liked');
                }
            },
            error: function() {
                console.log("Lỗi khi kiểm tra trạng thái yêu thích");
            }
        });
    });
}

function them_yeuThich(maSanPham) {
    $.ajax({
        url: "../ajax/ThemYeuThichAjax.php",
        type: "POST",
        data: {
            maSanPham: maSanPham
        },
        success: function (giaTri) {
            if(giaTri === "Đã thêm sản phẩm vào danh sách yêu thích!"){
                toastr.success(giaTri, 'Thông báo');
                // Cập nhật trạng thái icon thành "đã yêu thích"
                $(`.heart-icon[data-product-id="${maSanPham}"]`).removeClass('far').addClass('fas').addClass('liked');
                // Cập nhật số lượng yêu thích ở header
                capNhatSoLuongYeuThich();
            } else {
                toastr.error(giaTri, 'Lỗi');
            }
        },
        error: function () {
            Swal.fire({
                title: 'Lỗi!',
                text: 'Có lỗi xảy ra khi thêm vào yêu thích. Vui lòng thử lại.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        }
    });
}

function xoa_yeuThich(maSanPham) {
    $.ajax({
        url: "../ajax/XoaYeuThichAjax.php",
        type: "POST",
        data: {
            maSanPham: maSanPham
        },
        success: function (giaTri) {
            if(giaTri === "Đã xóa sản phẩm khỏi danh sách yêu thích!"){
                toastr.info(giaTri, 'Thông báo');
                // Xóa sản phẩm khỏi giao diện
                $(`.cart-header[data-product-id="${maSanPham}"]`).remove();
                $(`.heart-icon[data-product-id="${maSanPham}"]`).removeClass('fas').addClass('far').removeClass('liked');
                // Cập nhật số lượng yêu thích ở header
                capNhatSoLuongYeuThich();
                // Cập nhật trạng thái nút "Xem thêm"/"Ẩn bớt"
                capNhatTrangThaiNutXemThem();
                // Kiểm tra nếu danh sách trống thì hiển thị thông báo
                if ($('.cart-header').length === 0) {
                    $('.in-check').html('<p>Bạn chưa có sản phẩm yêu thích nào.</p>');
                }
            } else {
                toastr.error(giaTri, 'Lỗi');
            }
        },
        error: function () {
            Swal.fire({
                title: 'Lỗi!',
                text: 'Có lỗi xảy ra khi xóa yêu thích. Vui lòng thử lại.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        }
    });  
}

function capNhatSoLuongYeuThich() {
    $.ajax({
        url: "../ajax/LaySoLuongYeuThichAjax.php",
        type: "POST",
        success: function(total) {
            // Cập nhật số lượng hiển thị ở header
            $('#soLuongYeuThich').text(total);
        },
        error: function() {
            console.log("Lỗi khi lấy số lượng yêu thích");
        }
    });
}

function capNhatTrangThaiNutXemThem() {
    const soSanPhamToiDa = 5;
    const soSanPhamHienTai = $('.cart-header').length;

    if (soSanPhamHienTai <= soSanPhamToiDa) {
        // Nếu số sản phẩm còn lại <= 5, ẩn cả hai nút "Xem thêm" và "Ẩn bớt"
        $('#xemThemYeuThich').hide();
        $('#anYeuThich').hide();
    } else {
        // Nếu số sản phẩm > 5, kiểm tra trạng thái hiện tại của danh sách
        if ($('.anDoiTuong:visible').length > 0) {
            // Nếu đang hiển thị các sản phẩm ẩn, giữ nút "Ẩn bớt"
            $('#xemThemYeuThich').hide();
            $('#anYeuThich').show();
        } else {
            // Nếu các sản phẩm đang ẩn, giữ nút "Xem thêm"
            $('#xemThemYeuThich').show();
            $('#anYeuThich').hide();
        }
    }
}

function capNhat_gioHang(maSanPham, soLuong){
    soLuong = Number(soLuong);
    if(!Number.isInteger(soLuong) || (soLuong <= 0)){
        Swal.fire({
            title: 'Cảnh báo',
            text: "Số lượng sản phẩm bạn nhập không hợp lệ!",
            icon: 'warning',
            confirmButtonText: 'OK'
        }).then(() => {
            window.location.href = "./GioHang.php"
        });
    }
    else{
        $.ajax({
            url: "../ajax/CapNhatGioHangAjax.php",
            type: "POST",
            data: {
                maSanPham: maSanPham,
                soLuong: soLuong
            },
            success: function(giaTri){
                $('.in-check').html(giaTri);
                location.reload();
            }
        });
    }
}

function xoa_sanPham(maSanPham){
    Swal.fire({
        title: 'Xóa sản phẩm',
        text: "Bạn có chắc chắn muốn xóa sản phẩm này khỏi giỏ hàng?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Xóa',
        cancelButtonText: 'Hủy',
        reverseButtons: true 
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "../ajax/XoaSanPhamAjax.php",
                type: "POST",
                data: {
                    maSanPham: maSanPham
                },
                success: function(giaTri){
                    Swal.fire({
                        title: 'Thông báo',
                        text: 'Đã xóa sản phẩm khỏi giỏ hàng', 
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        $('.in-check').html(giaTri);
                    });
                },
                error: function () {
                    Swal.fire({
                        title: 'Lỗi!',
                        text: 'Có lỗi xảy ra khi xóa sản phẩm này khỏi giỏ hàng. Vui lòng thử lại.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            });
        }
    });
}

function danh_gia(maSanPham, tenDangNhap, noiDung) {
    $.ajax({
        url: "../ajax/DanhGiaSanPhamAjax.php",
        type: "POST",
        data: {
            maSanPham: maSanPham,
            tenDangNhap: tenDangNhap,
            noiDung: noiDung
        },
        success: function (giaTri) {
            if(giaTri === "Bạn đã đánh giá sản phẩm này rồi!"){
                Swal.fire({
                    title: 'Thông báo',
                    text: giaTri,  
                    icon: 'info',
                    confirmButtonText: 'OK'
                }).then(() => {
                    window.location.href = "./ChiTietSanPham.php?MaSanPham=" + maSanPham;
                });
            }
            else if(giaTri === "Đánh giá thành công!"){
                Swal.fire({
                    title: 'Thông báo',
                    text: giaTri,  
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then(() => {
                    window.location.href = "./ChiTietSanPham.php?MaSanPham=" + maSanPham;
                });
            }
            else{
                Swal.fire({
                    title: 'Thông báo',
                    text: giaTri,  
                    icon: 'warning',
                    confirmButtonText: 'OK'
                }).then(() => {
                    window.location.href = "./ChiTietSanPham.php?MaSanPham=" + maSanPham;
                });
            }
        },
        error: function (xhr, status, error) {
            // Hiển thị lỗi nếu có vấn đề với AJAX
            Swal.fire({
                title: 'Lỗi!',
                text: 'Có lỗi xảy ra khi gửi đánh giá. Vui lòng thử lại.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        }
    });
}


function xoa_binhLuan(maBinhLuan, maSanPham) {
    // Sử dụng SweetAlert2 để hiển thị hộp thoại xác nhận
    Swal.fire({
        title: 'Bạn có chắc chắn muốn xóa bình luận?',
        text: "Bạn sẽ không thể khôi phục lại bình luận này!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Xóa',
        cancelButtonText: 'Hủy',
        reverseButtons: true // Đổi vị trí nút xác nhận và hủy
    }).then((result) => {
        if (result.isConfirmed) {
            // Nếu người dùng nhấn "Xóa"
            $.ajax({
                url: "../ajax/XoaBinhLuanAjax.php",
                type: "POST",
                data: {
                    maBinhLuan: maBinhLuan
                },
                success: function (giaTri) {
                    // Hiển thị thông báo khi xóa thành công
                    Swal.fire(
                        'Thông báo',
                        giaTri,
                        'success'
                    ).then(() => {
                        // Sau khi thông báo, chuyển hướng trang
                        window.location.href = "./ChiTietSanPham.php?MaSanPham=" + maSanPham;
                    });
                },
                error: function (xhr, status, error) {
                    // Hiển thị lỗi nếu có vấn đề với AJAX
                    Swal.fire(
                        'Lỗi!',
                        'Có lỗi xảy ra khi xóa bình luận. Vui lòng thử lại.',
                        'error'
                    );
                }
            });
        }
    });
}

function sua_binhLuan(maBinhLuan, maSanPham, noiDung) {
    $.ajax({
        url: "../ajax/SuaBinhLuanAjax.php",
        type: "POST",
        data: {
            maBinhLuan: maBinhLuan,
            noiDung: noiDung
        },
        success: function (giaTri) {
            // Nếu cập nhật thành công
            if (giaTri === "Cập nhật bình luận thành công!") {
                Swal.fire({
                    title: 'Thông báo',
                    text: giaTri, // Nội dung từ server
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then(() => {
                    // Tải lại trang sản phẩm sau khi sửa bình luận thành công
                    window.location.href = "./ChiTietSanPham.php?MaSanPham=" + maSanPham;
                });
            } else {
                // Nếu có lỗi, hiển thị thông báo lỗi
                Swal.fire({
                    title: 'Lỗi!',
                    text: giaTri, // Nội dung lỗi từ server
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        },
        error: function () {
            // Hiển thị thông báo lỗi khi có sự cố với AJAX
            Swal.fire({
                title: 'Lỗi!',
                text: 'Có lỗi xảy ra khi sửa bình luận. Vui lòng thử lại.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        }
    });
}

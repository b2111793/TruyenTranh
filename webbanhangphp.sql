-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th4 08, 2025 lúc 05:41 PM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `webbanhangphp`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `binhluan`
--

CREATE TABLE `binhluan` (
  `MaBinhLuan` int(11) NOT NULL,
  `TenDangNhap` varchar(255) NOT NULL,
  `MaSanPham` int(11) NOT NULL,
  `NgayBinhLuan` date NOT NULL,
  `NoiDung` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `binhluan`
--

INSERT INTO `binhluan` (`MaBinhLuan`, `TenDangNhap`, `MaSanPham`, `NgayBinhLuan`, `NoiDung`) VALUES
(45, 'Conan', 1002, '2025-03-26', 'Hấp dẫn '),
(46, 'Conan', 1002, '2025-03-26', 'Xin chào'),
(47, 'Kid', 1002, '2025-03-26', 'Màn trình diễn'),
(54, 'Conan', 1006, '2025-04-02', 'Hay quá à'),
(74, 'Conan', 1001, '2025-04-02', 'Rất hấp dẫn nhé!!!!!!!!!'),
(77, 'Conan', 1001, '2025-04-02', 'Truyện tranh hay lắm nè 123'),
(78, 'Conan', 5004, '2025-04-04', 'Hay à nhe'),
(79, 'Conan', 5006, '2025-04-04', 'Hello'),
(80, 'Conan', 4002, '2025-04-05', 'Rất hay nhé!'),
(81, 'Conan', 1022, '2025-04-08', 'Hay á'),
(82, 'Conan', 1022, '2025-04-08', 'Kịch tính quá!!!'),
(83, 'Conan', 1022, '2025-04-08', 'Kịch tính quá!!!'),
(84, 'Conan', 1022, '2025-04-08', 'Idol!!!'),
(85, 'Kid', 1022, '2025-04-08', 'Hay'),
(86, 'Kid', 1022, '2025-04-08', 'Bị lỗi hả'),
(87, 'Kid', 1022, '2025-04-08', 'kkk'),
(88, 'Kid', 1022, '2025-04-08', 'Hay nhé'),
(89, 'Conan', 1022, '2025-04-08', 'a'),
(90, 'Conan', 1022, '2025-04-08', 'hay á'),
(91, 'Conan', 1022, '2025-04-08', 'hay nhé'),
(92, 'Conan', 1001, '2025-04-08', 'Phá án '),
(93, 'Conan', 1001, '2025-04-08', 'Phá án trinh thám kịch tính!!!!'),
(94, 'Conan', 1001, '2025-04-08', 'Hấp dẫn !!!!!!!!!!!!!!!!!!!!!!!!!!!'),
(95, 'Conan', 1001, '2025-04-08', 'ok lắm nhe');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `ct_dondat`
--

CREATE TABLE `ct_dondat` (
  `MaDonDat` int(11) NOT NULL,
  `MaSanPham` int(11) NOT NULL,
  `SoLuong` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `ct_dondat`
--

INSERT INTO `ct_dondat` (`MaDonDat`, `MaSanPham`, `SoLuong`) VALUES
(37, 1006, 4),
(38, 1001, 8),
(39, 1002, 1),
(40, 1002, 6),
(40, 1003, 2),
(41, 3002, 1),
(42, 5004, 1),
(43, 4002, 1),
(44, 2004, 1),
(45, 3009, 1),
(46, 2006, 1),
(47, 4002, 1),
(48, 5006, 1),
(49, 1001, 1),
(49, 1002, 1),
(50, 1001, 1),
(50, 1002, 1),
(51, 1003, 1),
(52, 1003, 1),
(53, 2003, 1),
(54, 2003, 1),
(55, 2003, 1),
(56, 2003, 1),
(57, 2003, 1),
(58, 1001, 1),
(58, 2003, 1),
(59, 1001, 1),
(59, 2003, 1),
(60, 1001, 1),
(60, 2003, 1),
(61, 1003, 1),
(62, 1001, 1),
(63, 1003, 1),
(66, 5005, 7),
(67, 1001, 1),
(68, 1001, 1),
(68, 2001, 1),
(69, 1004, 1),
(69, 1024, 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `danhgia`
--

CREATE TABLE `danhgia` (
  `MaSanPham` int(11) NOT NULL,
  `TenDangNhap` varchar(255) NOT NULL,
  `NoiDung` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `danhgia`
--

INSERT INTO `danhgia` (`MaSanPham`, `TenDangNhap`, `NoiDung`) VALUES
(1001, 'Conan', '5'),
(1002, 'Conan', '3'),
(1006, 'Conan', '4'),
(4002, 'Conan', '5');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `dondat`
--

CREATE TABLE `dondat` (
  `MaDonDat` int(11) NOT NULL,
  `TenDangNhap` varchar(255) NOT NULL,
  `TrangThai` text NOT NULL,
  `NoiGiao` text NOT NULL,
  `NgayDat` date NOT NULL,
  `HinhThucThanhToan` varchar(50) NOT NULL DEFAULT 'COD'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `dondat`
--

INSERT INTO `dondat` (`MaDonDat`, `TenDangNhap`, `TrangThai`, `NoiGiao`, `NgayDat`, `HinhThucThanhToan`) VALUES
(37, 'Conan', 'Hoàn tiền', 'BeiKa Tokyo', '2025-03-26', 'COD'),
(38, 'Conan', 'Hoàn tiền', 'BeiKa Tokyo', '2025-03-26', 'Momo'),
(39, 'Conan', 'Hoàn tiền', 'BeiKa Tokyo', '2025-03-26', 'Momo'),
(40, 'Conan', 'Hoàn tiền', 'BeiKa Tokyo', '2025-04-03', 'COD'),
(41, 'Conan', 'Hoàn tiền', 'BeiKa Tokyo', '2025-04-03', 'COD'),
(42, 'Conan', 'Đã giao', 'BeiKa Tokyo', '2025-04-03', 'COD'),
(43, 'Conan', 'Đã giao', 'BeiKa Tokyo', '2025-04-03', 'COD'),
(44, 'Conan', 'Đã giao', 'BeiKa Tokyo', '2025-04-03', 'COD'),
(45, 'Conan', 'Đã giao', 'BeiKa Tokyo', '2025-04-03', 'COD'),
(46, 'Conan', 'Đã giao', 'BeiKa Tokyo', '2025-04-03', 'COD'),
(47, 'Conan', 'Đã giao', 'BeiKa Tokyo', '2025-04-03', 'COD'),
(48, 'Conan', 'Hoàn tiền', 'BeiKa Tokyo', '2025-04-04', 'COD'),
(49, 'Conan', 'Đã hủy', 'BeiKa Tokyo', '2025-04-04', 'Momo'),
(50, 'Conan', 'Chờ xử lý', 'BeiKa Tokyo', '2025-04-04', 'COD'),
(51, 'Conan', 'Đang giao', 'BeiKa Tokyo', '2025-04-04', 'Momo'),
(52, 'Conan', 'Đã xử lý', 'BeiKa Tokyo', '2025-04-04', 'COD'),
(53, 'Conan', 'Đã hủy', 'BeiKa Tokyo', '2025-04-04', 'Momo'),
(54, 'Conan', 'Đã hủy', 'BeiKa Tokyo', '2025-04-04', 'Momo'),
(55, 'Conan', 'Chờ thanh toán', 'BeiKa Tokyo', '2025-04-04', 'Momo'),
(56, 'Conan', 'Chờ thanh toán', 'BeiKa Tokyo', '2025-04-04', 'Momo'),
(57, 'Conan', 'Đã thanh toán', 'BeiKa Tokyo', '2025-04-04', 'Momo'),
(58, 'Conan', 'Đã hủy', 'BeiKa Tokyo', '2025-04-04', 'Momo'),
(59, 'Conan', 'Chờ thanh toán', 'BeiKa Tokyo', '2025-04-04', 'Momo'),
(60, 'Conan', 'Chờ xử lý', 'BeiKa Tokyo', '2025-04-04', 'COD'),
(61, 'Conan', 'Đã thanh toán', 'BeiKa Tokyo', '2025-04-04', 'Momo'),
(62, 'Conan', 'Đã hủy', 'BeiKa Tokyo', '2025-04-05', 'COD'),
(63, 'Conan', 'Hoàn tiền', 'BeiKa Tokyo', '2025-04-05', 'Momo'),
(66, 'Conan', 'Chờ xử lý', 'BeiKa Tokyo', '2025-04-07', 'COD'),
(67, 'Conan', 'Đã giao', 'BeiKa Tokyo', '2025-04-07', 'Momo'),
(68, 'Conan', 'Chờ xử lý', 'BeiKa Tokyo', '2025-04-08', 'COD'),
(69, 'Conan', 'Đã giao', 'BeiKa Tokyo', '2025-04-08', 'Momo');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `loaisp`
--

CREATE TABLE `loaisp` (
  `MaLoaiSP` int(11) NOT NULL,
  `TenLoai` text NOT NULL,
  `MoTa` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `loaisp`
--

INSERT INTO `loaisp` (`MaLoaiSP`, `TenLoai`, `MoTa`) VALUES
(1000, 'Bí ẩn Trinh thám Hình sự', 'Khai thác những vụ án ly kỳ, điều tra tội phạm và các bí ẩn bất ngờ. Thường xoay quanh các thám tử, cảnh sát, hoặc nhân vật tài năng phá án.'),
(2000, 'Hài kịch Khoa học Viễn tưởng', 'Kết hợp yếu tố hài hước với bối cảnh tương lai, công nghệ tiên tiến, vũ trụ hoặc thế giới giả tưởng. Mang lại tiếng cười và sự thích thú qua các tình huống oái ăm.'),
(3000, 'kiến thức Kỹ Năng', 'Chú trọng cung cấp thông tin, kỹ năng thực hành trong đời sống. Thường là những hướng dẫn hoặc chia sẻ kinh nghiệm hữu ích, mang tính giáo dục cao.'),
(4000, 'Cổ tích Ngụ ngôn', 'Thể loại truyện dân gian, truyền thuyết, mang tính giáo huấn hoặc giải thích nguồn gốc sự vật. Thường có nhân vật thần tiên, động vật, hoặc phép màu và kết thúc với bài học nhân văn.'),
(5000, 'Kinh dị', 'Tạo bầu không khí rùng rợn, căng thẳng với các yếu tố siêu nhiên, ma quái hay tâm lý đáng sợ. Mục đích chính là gây hồi hộp, thót tim cho người đọc.');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `nguoidung`
--

CREATE TABLE `nguoidung` (
  `TenDangNhap` varchar(50) NOT NULL,
  `MatKhau` varchar(255) NOT NULL,
  `HoTen` varchar(50) DEFAULT NULL,
  `NgaySinh` date DEFAULT NULL,
  `GioiTinh` varchar(10) DEFAULT NULL,
  `DiaChi` varchar(255) DEFAULT NULL,
  `DienThoai` varchar(15) DEFAULT NULL,
  `Email` varchar(100) NOT NULL,
  `TrangThai` varchar(20) DEFAULT 'Kích hoạt',
  `Quyen` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `nguoidung`
--

INSERT INTO `nguoidung` (`TenDangNhap`, `MatKhau`, `HoTen`, `NgaySinh`, `GioiTinh`, `DiaChi`, `DienThoai`, `Email`, `TrangThai`, `Quyen`) VALUES
('Conan', '$2y$10$n.y.WYRKXna.XbG9eNyBPuCnh0qWaU5OXdZpBTpHo0mIK.R6BTjrq', 'Edogawa Conan', '1994-05-04', 'Nam', 'BeiKa Tokyo', '0369369369', 'toantrinhtham00@gmail.com', 'Kích hoạt', 'Member'),
('Ha', '$2y$10$nlfFWWX1B263mblL8XGRCOmmUwwriILSBrDYfOZoKWzmMKDRYmcze', 'Lê Trần Phạm Anh Ha', '2003-12-16', 'Nam', 'Sóc Trăng', '0333907252', 'hab2111793@student.ctu.edu.vn', 'Kích hoạt', 'Admin'),
('Kid', '$2y$10$nZ161qUPrtWxNPDqJ4MHOuUJC9jOaHj4XzzDBc7V4tGX1Nz8r8bbW', 'Kaito Kid', '2025-03-26', 'Nam', 'Nhật Bản', '1412141203', 'Kid@gmail.com', 'Kích hoạt', 'Member');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `otp_matkhau`
--

CREATE TABLE `otp_matkhau` (
  `id` int(11) NOT NULL,
  `TenDangNhap` varchar(50) NOT NULL,
  `otp` varchar(6) NOT NULL,
  `thoiGianHetHan` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `sanpham`
--

CREATE TABLE `sanpham` (
  `MaSanPham` int(11) NOT NULL,
  `TenSanPham` text NOT NULL,
  `SoLuong` int(11) NOT NULL,
  `Anh` text NOT NULL,
  `AnhMoTa1` text NOT NULL,
  `AnhMoTa2` text NOT NULL,
  `AnhMoTa3` text NOT NULL,
  `DonGia` decimal(10,0) NOT NULL,
  `GioiThieu` text NOT NULL,
  `MaLoaiSP` int(11) NOT NULL,
  `TacGia` text NOT NULL,
  `XuatXu` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `sanpham`
--

INSERT INTO `sanpham` (`MaSanPham`, `TenSanPham`, `SoLuong`, `Anh`, `AnhMoTa1`, `AnhMoTa2`, `AnhMoTa3`, `DonGia`, `GioiThieu`, `MaLoaiSP`, `TacGia`, `XuatXu`) VALUES
(1001, 'Thám tử lừng danh Conan 1', 90, '../images/Anh_SanPham/conan1.png', '../images/Anh_SanPham/conan1_1.png', '../images/Anh_SanPham/conan1_2.png', '../images/Anh_SanPham/conan1_3.png', 25000, 'Thông tin truyện thám tử lừng danh Conan tập 1', 1000, 'Aoyama Gosho', 'Nhật Bản'),
(1002, 'Thám tử lừng danh Conan 2', 0, '../images/Anh_SanPham/conan2.png', '', '', '', 25000, 'Thông tin truyện thám tử lừng danh Conan tập 2', 1000, 'Aoyama Gosho', 'Nhật Bản'),
(1003, 'Thám tử lừng danh Conan 3', 0, '../images/Anh_SanPham/conan3.png', '', '', '', 25000, 'Thông tin truyện thám tử lừng danh Conan tập 3', 1000, 'Aoyama Gosho', 'Nhật Bản'),
(1004, 'Thám tử lừng danh Conan 4', 9, '../images/Anh_SanPham/conan4.png', '', '', '', 25000, 'Thông tin truyện thám tử lừng danh Conan tập 4', 1000, 'Aoyama Gosho', 'Nhật Bản'),
(1005, 'Thám tử lừng danh Conan 5', 9, '../images/Anh_SanPham/conan5.png', '', '', '', 25000, 'Thông tin truyện thám tử lừng danh Conan tập 5', 1000, 'Aoyama Gosho', 'Nhật Bản'),
(1006, 'Thám tử lừng danh Conan 6', 2, '../images/Anh_SanPham/conan6.png', '', '', '', 25000, 'Thông tin truyện thám tử lừng danh Conan tập 6', 1000, 'Aoyama Gosho', 'Nhật Bản'),
(1007, 'Thám tử lừng danh Conan 7', 10, '../images/Anh_SanPham/conan7.png', '', '', '', 25000, 'Thông tin truyện thám tử lừng danh Conan tập 7', 1000, 'Aoyama Gosho', 'Nhật Bản'),
(1008, 'Thám tử lừng danh Conan 8', 10, '../images/Anh_SanPham/conan8.png', '', '', '', 25000, 'Thông tin truyện thám tử lừng danh Conan tập 8', 1000, 'Aoyama Gosho', 'Nhật Bản'),
(1009, 'Thám tử lừng danh Conan 9', 10, '../images/Anh_SanPham/conan9.png', '', '', '', 25000, 'Thông tin truyện thám tử lừng danh Conan tập 9', 1000, 'Aoyama Gosho', 'Nhật Bản'),
(1010, 'Thám tử lừng danh Conan 10', 10, '../images/Anh_SanPham/conan10.png', '', '', '', 25000, 'Thông tin truyện thám tử lừng danh Conan tập 10', 1000, 'Aoyama Gosho', 'Nhật Bản'),
(1011, 'Học viện cảnh sát 1', 2, '../images/Anh_SanPham/hoc_vien_canh_sat_1.png', '', '', '', 70000, 'Thông tin truyện tranh Học viện cảnh sát 1', 1000, 'Aoyama Gosho', 'Nhật Bản'),
(1012, 'Học viện cảnh sát 2', 1, '../images/Anh_SanPham/hoc_vien_canh_sat_2.png', '', '', '', 70000, 'Thông tin truyện tranh Học viện cảnh sát 2', 1000, 'Aoyama Gosho', 'Nhật Bản'),
(1021, 'Magic Kaito 1', 5, '../images/Anh_SanPham/magic_kaito_1.png', '', '', '', 29000, 'Thông tin truyện tranh Magic Kaito 1', 1000, 'Aoyama Gosho', 'Nhật Bản'),
(1022, 'Magic Kaito 2', 3, '../images/Anh_SanPham/magic_kaito_2.png', '', '', '', 29000, 'Thông tin truyện tranh Magic Kaito 2', 1000, 'Aoyama Gosho', 'Nhật Bản'),
(1023, 'Magic Kaito 3', 5, '../images/Anh_SanPham/magic_kaito_3.png', '', '', '', 29000, 'Thông tin truyện tranh Magic Kaito 3', 1000, 'Aoyama Gosho', 'Nhật Bản'),
(1024, 'Magic Kaito 4', 4, '../images/Anh_SanPham/magic_kaito_4.png', '', '', '', 29000, 'Thông tin truyện tranh Magic Kaito 4', 1000, 'Aoyama Gosho', 'Nhật Bản'),
(1025, 'Magic Kaito 5', 5, '../images/Anh_SanPham/magic_kaito_5.png', '', '', '', 29000, 'Thông tin truyện tranh Magic Kaito 5', 1000, 'Aoyama Gosho', 'Nhật Bản'),
(2001, 'Doraemon chú mèo máy đến từ tương lai 1', 19, '../images/Anh_SanPham/doraemon1.png', '', '', '', 22000, 'Thông tin truyện tranh Doraemon chú mèo máy đến từ tương lai tập 1', 2000, 'Fujiko Fujio', 'Nhật Bản'),
(2002, 'Doraemon chú mèo máy đến từ tương lai 2', 0, '../images/Anh_SanPham/doraemon2.png', '', '', '', 22000, 'Thông tin truyện tranh Doraemon chú mèo máy đến từ tương lai tập 2', 2000, 'Fujiko Fujio', 'Nhật Bản'),
(2003, 'Doraemon chú mèo máy đến từ tương lai 3', 12, '../images/Anh_SanPham/doraemon3.png', '', '', '', 22000, 'Thông tin truyện tranh Doraemon chú mèo máy đến từ tương lai tập 3', 2000, 'Fujiko Fujio', 'Nhật Bản'),
(2004, 'Doraemon chú mèo máy đến từ tương lai 4', 19, '../images/Anh_SanPham/doraemon4.png', '', '', '', 22000, 'Thông tin truyện tranh Doraemon chú mèo máy đến từ tương lai tập 4', 2000, 'Fujiko Fujio', 'Nhật Bản'),
(2005, 'Doraemon chú mèo máy đến từ tương lai 5', 20, '../images/Anh_SanPham/doraemon5.png', '', '', '', 22000, 'Thông tin truyện tranh Doraemon chú mèo máy đến từ tương lai tập 5', 2000, 'Fujiko Fujio', 'Nhật Bản'),
(2006, 'Doraemon chú mèo máy đến từ tương lai 6', 19, '../images/Anh_SanPham/doraemon6.png', '', '', '', 22000, 'Thông tin truyện tranh Doraemon chú mèo máy đến từ tương lai tập 6', 2000, 'Fujiko Fujio', 'Nhật Bản'),
(2007, 'Doraemon chú mèo máy đến từ tương lai 7', 20, '../images/Anh_SanPham/doraemon7.png', '', '', '', 22000, 'Thông tin truyện tranh Doraemon chú mèo máy đến từ tương lai tập 7', 2000, 'Fujiko Fujio', 'Nhật Bản'),
(2008, 'Doraemon chú mèo máy đến từ tương lai 8', 18, '../images/Anh_SanPham/doraemon8.png', '', '', '', 22000, 'Thông tin truyện tranh Doraemon chú mèo máy đến từ tương lai tập 8', 2000, 'Fujiko Fujio', 'Nhật Bản'),
(2009, 'Doraemon chú mèo máy đến từ tương lai 9', 20, '../images/Anh_SanPham/doraemon9.png', '', '', '', 22000, 'Thông tin truyện tranh Doraemon chú mèo máy đến từ tương lai tập 9', 2000, 'Fujiko Fujio', 'Nhật Bản'),
(2010, 'Doraemon chú mèo máy đến từ tương lai 10', 20, '../images/Anh_SanPham/doraemon10.png', '', '', '', 22000, 'Thông tin truyện tranh Doraemon chú mèo máy đến từ tương lai tập 10', 2000, 'Fujiko Fujio', 'Nhật Bản'),
(3001, '10 vạn câu hỏi vì sao - Các hiện tượng tự nhiên kỳ thú', 15, '../images/Anh_SanPham/cac_hien_tuong_tu_nhien_ky_thu.png', '', '', '', 52000, 'Thông tin truyện tranh 10 vạn câu hỏi vì sao - Các hiện tượng tự nhiên kỳ thú', 3000, 'Tôn Nguyên Vĩ', 'Trung Quốc'),
(3002, '10 vạn câu hỏi vì sao - Cuộc sống muôn màu', 14, '../images/Anh_SanPham/cuoc_song_muon_mau.png', '', '', '', 52000, 'Thông tin truyện tranh 10 vạn câu hỏi vì sao - Cuộc sống muôn màu', 3000, 'Tôn Nguyên Vĩ', 'Trung Quốc'),
(3003, '10 vạn câu hỏi vì sao - Hóa học vui', 15, '../images/Anh_SanPham/hoa_hoc_vui.png', '', '', '', 52000, 'Thông tin truyện tranh 10 vạn câu hỏi vì sao - Hóa học vui', 3000, 'Tôn Nguyên Vĩ', 'Trung Quốc'),
(3004, '10 vạn câu hỏi vì sao - Khám phá thế giới đại dương 1', 15, '../images/Anh_SanPham/kham_pha_the_gioi_dai_duong_1.png', '', '', '', 52000, 'Thông tin truyện tranh 10 vạn câu hỏi vì sao - Khám phá thế giới đại dương 1', 3000, 'Tôn Nguyên Vĩ', 'Trung Quốc'),
(3005, '10 vạn câu hỏi vì sao - Khám phá thế giới đại dương 2', 15, '../images/Anh_SanPham/kham_pha_the_gioi_dai_duong_2.png', '', '', '', 52000, 'Thông tin truyện tranh 10 vạn câu hỏi vì sao - Khám phá thế giới đại dương 2', 3000, 'Tôn Nguyên Vĩ', 'Trung Quốc'),
(3006, '10 vạn câu hỏi vì sao - Khám phá thế giới thực vật', 15, '../images/Anh_SanPham/kham_pha_the_gioi_thuc_vat.png', '', '', '', 52000, 'Thông tin truyện tranh 10 vạn câu hỏi vì sao - Khám phá thế giới thực vật', 3000, 'Tôn Nguyên Vĩ', 'Trung Quốc'),
(3007, '10 vạn câu hỏi vì sao - Khám phá thế giới vi sinh vật', 15, '../images/Anh_SanPham/kham_pha_the_gioi_vi_sinh_vat.png', '', '', '', 52000, 'Thông tin truyện tranh 10 vạn câu hỏi vì sao - Khám phá thế giới vi sinh vật', 3000, 'Tôn Nguyên Vĩ', 'Trung Quốc'),
(3008, '10 vạn câu hỏi vì sao - Khám phá Trái đất', 15, '../images/Anh_SanPham/kham_pha_trai_dat.png', '', '', '', 52000, 'Thông tin truyện tranh 10 vạn câu hỏi vì sao - Khám phá Trái đất', 3000, 'Tôn Nguyên Vĩ', 'Trung Quốc'),
(3009, '10 vạn câu hỏi vì sao - Vật lý vui', 13, '../images/Anh_SanPham/vat_ly_vui.png', '', '', '', 52000, 'Thông tin truyện tranh 10 vạn câu hỏi vì sao - Vật lý vui', 3000, 'Tôn Nguyên Vĩ', 'Trung Quốc'),
(3010, '10 vạn câu hỏi vì sao - Vũ trụ thần bí', 15, '../images/Anh_SanPham/vu_tru_than_bi.png', '', '', '', 52000, 'Thông tin truyện tranh 10 vạn câu hỏi vì sao - Vũ trụ thần bí', 3000, 'Tôn Nguyên Vĩ', 'Trung Quốc'),
(4002, 'Sự tích cây nêu ngày Tết', 2, '../images/Anh_SanPham/su_tich_cay_neu_ngay_tet.png', '', '', '', 129000, 'Thông tin truyện tranh Sự tích cây nêu ngày Tết', 4000, 'Hồng Hà', 'Việt Nam'),
(5001, 'Ác mộng kinh hoàng', 10, '../images/Anh_SanPham/ac_mong_kinh_hoang.png', '', '', '', 230000, 'Thông tin truyện tranh Ác mộng kinh hoàng', 5000, 'Trần Vũ Sinh', 'Trung Quốc'),
(5002, 'Cộng sự lúc nữa đêm', 9, '../images/Anh_SanPham/cong_su_luc_nua_dem.png', '', '', '', 295000, 'Thông tin truyện tranh Cộng sự lúc nữa đêm', 5000, 'Kim Minhee', 'Hàn Quốc'),
(5003, 'Kịch rối', 10, '../images/Anh_SanPham/kich_roi.png', '', '', '', 70000, 'Thông tin truyện tranh Kịch rối', 5000, 'Tum Ulit', 'Thái Lan'),
(5004, 'Lullabye - giai điệu cuối', 8, '../images/Anh_SanPham/lullabye_giai_dieu_cuoi.png', '', '', '', 75000, 'Thông tin truyện tranh Lullabye - giai điệu cuối', 5000, 'Chellin', 'Việt Nam'),
(5005, 'Midnight city', 0, '../images/Anh_SanPham/midnight_city.png', '', '', '', 16000, 'Thông tin truyện tranh Midnight city', 5000, 'Nấm đùi gà', 'Nhật Bản'),
(5006, 'Nam đình cốc vi', 9, '../images/Anh_SanPham/nam_dinh_coc_vi.png', '', '', '', 550000, 'Thông tin truyện tranh Nam đình cốc vi', 5000, 'Mo Fei', 'Trung Quốc');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `yeuthich`
--

CREATE TABLE `yeuthich` (
  `TenDangNhap` varchar(255) NOT NULL,
  `MaSanPham` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `yeuthich`
--

INSERT INTO `yeuthich` (`TenDangNhap`, `MaSanPham`) VALUES
('Conan', 1001),
('Conan', 1002),
('Conan', 1003),
('Conan', 1004),
('Conan', 1005),
('Conan', 1006),
('Conan', 1007),
('Conan', 1008),
('Conan', 1009),
('Conan', 1010),
('Conan', 1011),
('Conan', 1012),
('Conan', 1022),
('Conan', 1024),
('Conan', 1025),
('Conan', 2001),
('Conan', 2002),
('Conan', 2003),
('Conan', 2004),
('Conan', 2005),
('Conan', 2006),
('Conan', 2007),
('Conan', 2009),
('Conan', 2010),
('Conan', 3002),
('Conan', 5006),
('Kid', 1001);

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `binhluan`
--
ALTER TABLE `binhluan`
  ADD PRIMARY KEY (`MaBinhLuan`),
  ADD KEY `binhluan_nguoidung` (`TenDangNhap`),
  ADD KEY `binhluan_sanpham` (`MaSanPham`);

--
-- Chỉ mục cho bảng `ct_dondat`
--
ALTER TABLE `ct_dondat`
  ADD PRIMARY KEY (`MaDonDat`,`MaSanPham`) USING BTREE,
  ADD KEY `ctdondat_sanpham` (`MaSanPham`),
  ADD KEY `MaDonDat` (`MaDonDat`);

--
-- Chỉ mục cho bảng `danhgia`
--
ALTER TABLE `danhgia`
  ADD PRIMARY KEY (`MaSanPham`,`TenDangNhap`),
  ADD KEY `danhgia_nguoidung` (`TenDangNhap`),
  ADD KEY `MaSanPham` (`MaSanPham`);

--
-- Chỉ mục cho bảng `dondat`
--
ALTER TABLE `dondat`
  ADD PRIMARY KEY (`MaDonDat`),
  ADD KEY `dondat_nguoidung` (`TenDangNhap`);

--
-- Chỉ mục cho bảng `loaisp`
--
ALTER TABLE `loaisp`
  ADD PRIMARY KEY (`MaLoaiSP`);

--
-- Chỉ mục cho bảng `nguoidung`
--
ALTER TABLE `nguoidung`
  ADD PRIMARY KEY (`TenDangNhap`),
  ADD UNIQUE KEY `Email` (`Email`);

--
-- Chỉ mục cho bảng `otp_matkhau`
--
ALTER TABLE `otp_matkhau`
  ADD PRIMARY KEY (`id`),
  ADD KEY `otp_matkhau_nguoidung` (`TenDangNhap`);

--
-- Chỉ mục cho bảng `sanpham`
--
ALTER TABLE `sanpham`
  ADD PRIMARY KEY (`MaSanPham`),
  ADD KEY `sanpham_loaisp` (`MaLoaiSP`);

--
-- Chỉ mục cho bảng `yeuthich`
--
ALTER TABLE `yeuthich`
  ADD PRIMARY KEY (`TenDangNhap`,`MaSanPham`),
  ADD KEY `yeuthich_sanpham` (`MaSanPham`),
  ADD KEY `TenDangNhap` (`TenDangNhap`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `binhluan`
--
ALTER TABLE `binhluan`
  MODIFY `MaBinhLuan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=96;

--
-- AUTO_INCREMENT cho bảng `dondat`
--
ALTER TABLE `dondat`
  MODIFY `MaDonDat` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

--
-- AUTO_INCREMENT cho bảng `loaisp`
--
ALTER TABLE `loaisp`
  MODIFY `MaLoaiSP` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5004;

--
-- AUTO_INCREMENT cho bảng `otp_matkhau`
--
ALTER TABLE `otp_matkhau`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `sanpham`
--
ALTER TABLE `sanpham`
  MODIFY `MaSanPham` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5012;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `binhluan`
--
ALTER TABLE `binhluan`
  ADD CONSTRAINT `binhluan_nguoidung` FOREIGN KEY (`TenDangNhap`) REFERENCES `nguoidung` (`TenDangNhap`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `binhluan_sanpham` FOREIGN KEY (`MaSanPham`) REFERENCES `sanpham` (`MaSanPham`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `ct_dondat`
--
ALTER TABLE `ct_dondat`
  ADD CONSTRAINT `ctdondat_dondat` FOREIGN KEY (`MaDonDat`) REFERENCES `dondat` (`MaDonDat`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `ctdondat_sanpham` FOREIGN KEY (`MaSanPham`) REFERENCES `sanpham` (`MaSanPham`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `danhgia`
--
ALTER TABLE `danhgia`
  ADD CONSTRAINT `danhgia_nguoidung` FOREIGN KEY (`TenDangNhap`) REFERENCES `nguoidung` (`TenDangNhap`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `danhgia_sanpham` FOREIGN KEY (`MaSanPham`) REFERENCES `sanpham` (`MaSanPham`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `dondat`
--
ALTER TABLE `dondat`
  ADD CONSTRAINT `dondat_nguoidung` FOREIGN KEY (`TenDangNhap`) REFERENCES `nguoidung` (`TenDangNhap`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `otp_matkhau`
--
ALTER TABLE `otp_matkhau`
  ADD CONSTRAINT `otp_matkhau_nguoidung` FOREIGN KEY (`TenDangNhap`) REFERENCES `nguoidung` (`TenDangNhap`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `sanpham`
--
ALTER TABLE `sanpham`
  ADD CONSTRAINT `sanpham_loaisp` FOREIGN KEY (`MaLoaiSP`) REFERENCES `loaisp` (`MaLoaiSP`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `yeuthich`
--
ALTER TABLE `yeuthich`
  ADD CONSTRAINT `yeuthich_nguoidung` FOREIGN KEY (`TenDangNhap`) REFERENCES `nguoidung` (`TenDangNhap`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `yeuthich_sanpham` FOREIGN KEY (`MaSanPham`) REFERENCES `sanpham` (`MaSanPham`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

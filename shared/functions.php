<?php
    function hienThiDanhMuc($conn, $query, $paramName, $fieldName) {
        $truyVan = mysqli_query($conn, $query);
        if (!$truyVan) {
            echo "Lỗi truy vấn: " . mysqli_error($conn);
            return;
        }
        
        while ($cot = mysqli_fetch_assoc($truyVan)) {
            $value = urlencode($cot[$fieldName]);
            $display = htmlspecialchars($cot[$fieldName]);
            echo "<li><a href=\"DanhMucSanPham.php?$paramName=$value\">$display</a></li>";
        }
    }

    function phan_trang($tenCot, $tenBang, $dieuKien, $soLuongSanPham, $trang, $dieuKienTrang){
        $sanPhamBatDau = $trang * $soLuongSanPham;
        $laySanPham = sprintf("SELECT %s FROM %s %s LIMIT %d, %d", 
                      $tenCot, $tenBang, $dieuKien, $sanPhamBatDau, $soLuongSanPham);
        $truyVan_LaySanPham = mysqli_query($GLOBALS['conn'], $laySanPham);
    
        $tongSoLuongSanPham = mysqli_num_rows(mysqli_query($GLOBALS['conn'], 
            sprintf("SELECT %s FROM %s %s", $tenCot, $tenBang, $dieuKien)));
        $tongSoTrang = ceil($tongSoLuongSanPham / $soLuongSanPham);
    
        $danhSachTrang = "<div>";
        
        // Nút Trang trước
        if($trang > 0){
            $trangTruoc = $trang - 1;
            $danhSachTrang .= "<a href='".$_SERVER["PHP_SELF"]."?trang=".$trangTruoc.$dieuKienTrang."'>Trước</a> ";
        }
    
        $soTrangHienThi = 5;
        $nuaSoTrang = floor($soTrangHienThi/2);
        $trangBatDau = max(0, $trang - $nuaSoTrang);
        $trangKetThuc = min($tongSoTrang - 1, $trang + $nuaSoTrang);
    
        if($trangKetThuc - $trangBatDau + 1 < $soTrangHienThi) {
            $trangKetThuc = ($trang < $nuaSoTrang) ? min($tongSoTrang - 1, $soTrangHienThi - 1) : $trangKetThuc;
            $trangBatDau = ($trang >= $nuaSoTrang) ? max(0, $tongSoTrang - $soTrangHienThi) : $trangBatDau;
        }        
    
        if($trangBatDau > 0){
            $danhSachTrang .= "<a href='".$_SERVER["PHP_SELF"]."?trang=0".$dieuKienTrang."' class='divTrang_0'>1</a> ";
            if($trangBatDau > 1) $danhSachTrang .= "<span>...</span> ";
        }
    
        // Sử dụng class divTrang_[số] cho từng trang
        for($index = $trangBatDau; $index <= $trangKetThuc; $index++){
            $soTrang = $index + 1;
            $danhSachTrang .= "<a href='".$_SERVER["PHP_SELF"]."?trang=".$index.$dieuKienTrang."' class='divTrang_".$index."'>".$soTrang."</a> ";
        }
    
        if($trangKetThuc < $tongSoTrang - 1){
            if($trangKetThuc < $tongSoTrang - 2) $danhSachTrang .= "<span>...</span> ";
            $danhSachTrang .= "<a href='".$_SERVER["PHP_SELF"]."?trang=".($tongSoTrang-1).$dieuKienTrang."' class='divTrang_".($tongSoTrang-1)."'>".$tongSoTrang."</a> ";
        }
    
        if($trang < $tongSoTrang - 1){
            $trangSau = $trang + 1;
            $danhSachTrang .= "<a href='".$_SERVER["PHP_SELF"]."?trang=".$trangSau.$dieuKienTrang."'>Tiếp theo</a>";
        }

        $danhSachTrang .= "</div>";
    
        echo "<script>
                $(document).ready(function(){
                    $('.divTrang').html(\"".$danhSachTrang."\");
                    $('.divTrang_".$trang."').addClass('divTrangActive');
                });
            </script>";
        return $truyVan_LaySanPham;
    }
?>
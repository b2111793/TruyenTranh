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
?>

<main>
    <div class="container-fluid px-4">
        <h1 class="mt-4">Sidenav Light</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="./TrangChuAdmin.php">Trang chủ</a></li>
            <li class="breadcrumb-item active">Sidenav Light</li>
        </ol>
        <div class="card mb-4">
            <div class="card-body">
                This page is an example of using the light side navigation option. By appending the
                <code>.sb-sidenav-light</code>
                class to the
                <code>.sb-sidenav</code>
                class, the side navigation will take on a light color scheme. The
                <code>.sb-sidenav-dark</code>
                is also available for a darker option.
            </div>
        </div>
    </div>
</main>
                
<?php 
    include('../shared/footerAdmin.php');
?>
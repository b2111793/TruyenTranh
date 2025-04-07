<footer class="py-4 bg-light mt-auto">
                    <div class="container-fluid px-4">
                        <div class="d-flex align-items-center justify-content-between small">
                            <div class="text-muted">Copyright &copy;  2025</div>
                            <div>
                                <a href="../admin/TrangChuAdmin.php?thamQuan=1">Bí ẩn chưa có hồi kết - Tự do trong thế giới truyện tranh</a>
                                | Thiết kế bởi
                                    <a href="#">Ha Lê</a>
                            </div>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
<?php
    if(isset($_GET["thamQuan"])){
        $_GET["thamQuan"] = null;
        session_unset(); 
        session_destroy(); 
        echo "<script> window.location.href = '../users/TrangChu.php'; </script>";
        exit;
    }
?>

    <!-- Hiển thị thông báo Toastr -->
<?php if (isset($_SESSION['toastr'])): ?>
    <script>
        $(document).ready(function() {
            var type = "<?php echo $_SESSION['toastr']['type']; ?>";
            var message = "<?php echo $_SESSION['toastr']['message']; ?>";
            switch(type) {
                case 'success':
                    toastr.success(message);
                    break;
                case 'error':
                    toastr.error(message);
                    break;
                case 'warning':
                    toastr.warning(message);
                    break;
                case 'info':
                    toastr.info(message);
                    break;
                default:
                    toastr.info(message);
                    break;
            }
        });
    </script>
    <?php unset($_SESSION['toastr']); // Xóa thông báo sau khi hiển thị ?>
<?php endif; ?>
</body>
</html>
        <!-- Bao gồm jQuery và DataTables JS -->
        <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
        <!-- Font Awesome (cho các biểu tượng) -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    </body>
</html>

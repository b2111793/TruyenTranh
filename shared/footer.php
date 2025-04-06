	<!--start-footer-->
	<div class="footer">
		<div class="container">
			<div class="footer-top">
				<div class="col-md-4 footer-left">
					<h3>Liên hệ</h3>
					<ul>
						<li><a href="#"><i class="fas fa-envelope-open-text iconLienHe"></i> Theonlytruth1412@gmail.com</a></li>
						<li><a href="#"><i class="fab fa-facebook iconLienHe"></i> Ha Lê</a></li>
						<li><a href="#"><i class="fas fa-mobile-screen-button iconLienHe"></i> 0333907252</a></li>
						<li><a href="#"><i class="fas fa-location-dot iconLienHe"></i> Đường 3/2, Xuân Khánh,  Ninh Kiều, Cần Thơ</a></li>			 
					</ul>
				</div>
				<div class="col-md-4 footer-left">
					<h3>Mạng xã hội</h3>
					<ul>
						<li><a href="https://youtube.com"><i class="fab fa-youtube iconLienHe"></i> Youtube</a></li>
						<li><a href="https://Skype.com"><i class="fab fa-skype iconLienHe"></i> Skype</a></li>
						<li><a href="https://instagram.com"><i class="fab fa-instagram iconLienHe"></i> Instagram</a></li>
						<li><a href="https://twitter.com"><i class="fab fa-twitter iconLienHe"></i> Twitter</a></li>		 
					</ul>
				</div>
				<div class="col-md-4 footer-left">
					<h3>Chính sách</h3>
					<ul>
						<!-- <li><a href="#"><i class="fas fa-file-contract iconLienHe"></i> Điều khoản sử dụng</a></li> -->
						<li><a href="#"><i class="fas fa-credit-card iconLienHe"></i> Chính sách Thanh toán</a></li>
						<li><a href="#"><i class="fas fa-shield-alt iconLienHe"></i> Chính sách bảo mật</a></li>
						<li><a href="#"><i class="fas fa-truck iconLienHe"></i> Chính sách giao hàng</a></li>
						<li><a href="#"><i class="fas fa-sync-alt iconLienHe"></i> Chính sách đổi trả</a></li>					 					 
					</ul>
				</div>
				<div class="clearfix"> </div>
			</div>
		</div>
	</div>
	<!--end-footer-->
	<script src="../js/functions.js"></script>
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
	<!--end-footer-text-->
	<div class="footer-text">
		<div class="container">
			<div class="footer-main">
				<p class="footer-class">Copyright © 2025 Bí ẩn chưa có hồi kết - Tự do trong thế giới truyện tranh | Thiết kế bởi
					<a href="#" target="_blank">Ha Lê</a> </p>
			</div>
		</div>
	</div>
	<!--end-footer-text-->	
	<!-- Nút scroll to top -->
<button id="scrollToTopBtn" title="Lên đầu trang">
  <i class="fas fa-chevron-up"></i>
</button>

<!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>  -->

<script>
	$(document).ready(function(){
		// Hiện nút khi cuộn xuống 100px
		$(window).scroll(function(){
		if ($(this).scrollTop() > 100) {
			$('#scrollToTopBtn').fadeIn();
		} else {
			$('#scrollToTopBtn').fadeOut();
		}
		});

		// Cuộn lên đầu trang khi click nút
		$('#scrollToTopBtn').click(function(){
		$('html, body').animate({scrollTop : 0}, 600);
		return false;
		});
	});
</script>


</body>
</html>
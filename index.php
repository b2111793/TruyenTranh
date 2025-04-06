<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Chọn chế độ đăng nhập</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
		body{
			background: url('./images/background.png') no-repeat center center fixed;
			background-size: cover;
			display: flex;
			justify-content: center; /* Căn giữa theo chiều ngang */
			align-items: center; /* Căn giữa theo chiều dọc */
			height: 100vh; /* Chiều cao toàn màn hình */
			margin: 0;
		}

		.container{
			text-align: center;
		}

		.row{
			display: flex;
			justify-content: center;
			gap: 20px; /* Khoảng cách giữa hai khung */
		}

		.card{
			background: rgba(0, 0, 0, 0.2); /* Nền tối hơn để tăng độ tương phản */
			backdrop-filter: blur(8px); /* Hiệu ứng làm mờ */
			padding: 20px;
			border-radius: 10px;
			transition: 0.3s;
			box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
			color: white; /* Đổi màu chữ thành trắng */
		}

		.card:hover{
			transform: scale(1.05);
		}

		.card-title{
			font-weight: bold;
		}

		.card.border-danger .card-title{
			color: #ff4d4d; /* Màu đỏ nổi bật cho Admin */
		}

		.card.border-primary .card-title{
			color: #4da6ff; /* Màu xanh nổi bật cho Khách hàng */
		}

		.card-text{
			color: #f8f9fa; /* Màu chữ xám sáng giúp dễ đọc hơn */
		}

		.btn{
			width: 100%;
		}

		h3{
			font-size: 2rem;
			font-weight: bold;
			letter-spacing: 2px;
			color: white; /* Đổi màu chữ thành trắng */
			text-shadow: 3px 3px 10px rgba(255, 0, 0, 0.8); /* Đổ bóng màu đỏ mạnh */
		}

    </style>
</head>
<body>
	<div class="container text-center">
		<h3>Bí ẩn chưa có hồi kết... </h3>
		<h3>...Tự do trong thế giới truyện tranh!!!</h3>
		<p style="color: #ffcc00;">Chào mừng bạn đến với website bán truyện tranh</p>
		
		<div class="row justify-content-center">
			<!-- Đăng nhập Admin -->
			<!-- <div class="col-md-4">
				<div class="card border-danger">
					<div class="card-body">
						<h5 class="card-title text-danger">Admin</h5>
						<p class="card-text">Dành cho quản trị viên quản lý hệ thống.</p>
						<a href="./admin/DangNhapAdmin.php" class="btn btn-danger">Tham gia</a>
					</div>
				</div>
			</div> -->
			<!-- Đăng nhập Khách hàng -->
			<div class="col-md-4">
				<div class="card border-primary">
					<div class="card-body">
						<h5 class="card-title text-primary">Thế giới truyện tranh</h5>
						<p class="card-text">Cùng tham quan và mua sắm.</p>
						<a href="./users/TrangChu.php" class="btn btn-primary">Tham gia</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
</html>

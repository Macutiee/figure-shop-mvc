<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng Nhập & Đăng Ký - Ma's Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background: linear-gradient(135deg, #fff5f7 0%, #ffe0e6 100%); min-height: 100vh; display: flex; align-items: center; justify-content: center; font-family: 'Segoe UI', sans-serif; }
        .auth-card { background: white; border-radius: 25px; box-shadow: 0 15px 35px rgba(216, 27, 96, 0.1); width: 100%; max-width: 450px; padding: 40px; overflow: hidden; }
        
        /* Chỉnh tab Đăng nhập / Đăng ký */
        .nav-pills .nav-link { color: #ff758f; font-weight: bold; border-radius: 15px; padding: 12px; transition: 0.3s; }
        .nav-pills .nav-link.active { background-color: #ff4d6d; color: white; box-shadow: 0 4px 10px rgba(255, 77, 109, 0.3); }
        
        .form-control { border: 2px solid #ffe0e6; border-radius: 12px; padding: 12px 15px; }
        .form-control:focus { border-color: #ff758f; box-shadow: 0 0 10px rgba(255, 117, 143, 0.2); }
        
        .btn-pink { background: linear-gradient(to right, #ff758f, #ff4d6d); color: white; border: none; border-radius: 12px; font-weight: bold; padding: 12px; transition: 0.3s; }
        .btn-pink:hover { transform: translateY(-2px); box-shadow: 0 6px 15px rgba(255, 77, 109, 0.4); color: white; }
        
        .btn-google { background: white; color: #444; border: 2px solid #eee; border-radius: 12px; font-weight: 600; padding: 10px; transition: 0.3s; }
        .btn-google:hover { background: #f8f9fa; border-color: #ddd; transform: translateY(-1px); }
    </style>
</head>
<body>

    <div class="auth-card">
        <div class="text-center mb-4">
            <h3 class="fw-bold" style="color: #d81b60;"><i class="fa-solid fa-rose me-2"></i>Ma's Store</h3>
            <p class="text-muted small">Thiên đường Waifu chính hãng</p>
        </div>

        <!-- 2 CÁI TAB CHUYỂN QUA LẠI -->
        <ul class="nav nav-pills nav-justified mb-4" id="authTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="login-tab" data-bs-toggle="pill" data-bs-target="#login" type="button" role="tab">Đăng Nhập</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="register-tab" data-bs-toggle="pill" data-bs-target="#register" type="button" role="tab">Đăng Ký</button>
            </li>
        </ul>

        <div class="tab-content" id="authTabsContent">
            
            <!-- FORM ĐĂNG NHẬP -->
            <div class="tab-pane fade show active" id="login" role="tabpanel">
                <form action="index.php?action=process_login" method="POST">
                    <div class="mb-3">
                        <input type="email" name="email" class="form-control" placeholder="Email của bạn" required>
                    </div>
                    <div class="mb-3">
                        <input type="password" name="password" class="form-control" placeholder="Mật khẩu" required>
                    </div>
                    <div class="d-flex justify-content-end mb-3">
                    <a href="index.php?action=forgot_password" class="text-decoration-none small" style="color: #ff758f;">Quên mật khẩu?</a>
                    </div>
                    <button type="submit" class="btn btn-pink w-100 mb-3">Đăng Nhập Ngay</button>
                </form>
            </div>

            <!-- FORM ĐĂNG KÝ -->
            <div class="tab-pane fade" id="register" role="tabpanel">
                <form action="index.php?action=process_register" method="POST">
                    <div class="mb-3">
                        <input type="text" name="fullname" class="form-control" placeholder="Họ và tên" required>
                    </div>
                    <div class="mb-3">
                        <input type="email" name="email" class="form-control" placeholder="Email" required>
                    </div>
                    <div class="mb-3">
                        <input type="text" name="phone" class="form-control" placeholder="Số điện thoại" required>
                    </div>
                    <div class="mb-3">
                        <!-- Chỗ này tui tự động BĂM mật khẩu bằng PHP luôn nha Sếp -->
                        <input type="password" name="password" class="form-control" placeholder="Tạo mật khẩu" required>
                    </div>
                    <button type="submit" class="btn btn-pink w-100 mb-3">Tạo Tài Khoản</button>
                </form>
            </div>
            
        </div>

        <!-- NÚT GOOGLE SANG CHẢNH -->
        <div class="position-relative text-center my-4">
            <hr style="border-color: #ddd;">
            <span class="position-absolute top-50 start-50 translate-middle bg-white px-3 text-muted small">Hoặc tiếp tục với</span>
        </div>
        <button class="btn btn-google w-100" onclick="alert('Tính năng đang được Sếp Loan đàm phán với Google Cloud! Quay lại sau nha! 😜')">
            <img src="https://upload.wikimedia.org/wikipedia/commons/c/c1/Google_%22G%22_logo.svg" width="20" class="me-2"> Google
        </button>
        
        <div class="text-center mt-4">
            <a href="index.php" class="text-decoration-none text-muted small"><i class="fa-solid fa-arrow-left me-1"></i> Quay lại trang chủ</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // JS xịn: Nếu URL có chữ #register, tự động mở thẻ Đăng Ký cho khách
        if(window.location.hash === '#register') {
            var registerTab = new bootstrap.Tab(document.getElementById('register-tab'));
            registerTab.show();
        }
    </script>
</body>
</html>